<?php
/**
 * Multi-currency price display: Naira (base), Pounds, Dollars.
 *
 * Course prices are stored in NAIRA (courses.price / compare_price = what Selar
 * charges). Visitors see the amount converted into the currency that fits their
 * location, with a switcher to override. Shown amounts are approximate
 * conversions; checkout on Selar is the authority.
 *
 * Detection order: the visitor's own choice (cookie, set by ?currency=GBP or the
 * switcher) -> Cloudflare's CF-IPCountry header -> an IP-to-country lookup
 * (cached in the session) -> Naira. Nigeria -> NGN, United Kingdom -> GBP,
 * everywhere else -> USD, unknown/local -> NGN.
 *
 * Rates live in the currency_rates table (migration 108), refreshed from a free
 * public rate feed at most once a day, editable in sls-admin -> Currency Rates.
 * If the table or feed is unavailable the built-in fallback rates are used.
 * Never throws; a failure here must not break a page.
 */

if (defined('SLS_CURRENCY_LOADED')) return;
define('SLS_CURRENCY_LOADED', true);

const SLS_CURRENCIES     = ['NGN' => ['symbol' => '&#8358;', 'name' => 'Naira'],
                            'GBP' => ['symbol' => '&pound;', 'name' => 'Pounds'],
                            'USD' => ['symbol' => '$',       'name' => 'Dollars']];
const SLS_DEFAULT_RATES  = ['NGN' => 1.0, 'GBP' => 0.00055, 'USD' => 0.000749]; // fallback only (per 1 NGN)
const SLS_RATE_FEED      = 'https://open.er-api.com/v6/latest/NGN';
const SLS_GEO_FEED       = 'https://get.geojs.io/v1/ip/country.json?ip=';

// ── Pure helpers (no I/O; unit-testable) ────────────────────────────────────

function currency_for_country(?string $cc): string {
    $cc = strtoupper(trim((string) $cc));
    if ($cc === 'NG') return 'NGN';
    if (in_array($cc, ['GB', 'IM', 'JE', 'GG'], true)) return 'GBP';
    if (preg_match('/^[A-Z]{2}$/', $cc) && !in_array($cc, ['XX', 'T1'], true)) return 'USD';
    return 'NGN'; // unknown / local: show the price as listed on Selar
}

function currency_convert(float $ngn, string $code, array $rates): float {
    return $ngn * (float) ($rates[$code] ?? SLS_DEFAULT_RATES[$code] ?? 1.0);
}

function currency_format(float $ngn, string $code, array $rates): string {
    $sym = SLS_CURRENCIES[$code]['symbol'] ?? '&#8358;';
    return $sym . number_format(round(currency_convert($ngn, $code, $rates)), 0);
}

// ── Rates (DB-backed, self-refreshing) ──────────────────────────────────────

/** Fetch live rates and store them. Returns true on success. */
function currency_refresh_rates(PDO $db): bool {
    try {
        $db->exec("UPDATE currency_rates SET checked_at = NOW()");
        $ctx  = stream_context_create(['http' => ['timeout' => 4, 'ignore_errors' => true]]);
        $json = @file_get_contents(SLS_RATE_FEED, false, $ctx);
        $d    = $json ? json_decode($json, true) : null;
        if (!$d || ($d['result'] ?? '') !== 'success') return false;
        $up = $db->prepare("UPDATE currency_rates SET rate_per_ngn = ?, updated_at = NOW() WHERE code = ?");
        $n = 0;
        foreach (['GBP', 'USD'] as $code) {
            $r = (float) ($d['rates'][$code] ?? 0);
            if ($r > 0) { $up->execute([$r, $code]); $n++; }
        }
        return $n === 2;
    } catch (Throwable $e) {
        error_log('currency_refresh_rates: ' . $e->getMessage());
        return false;
    }
}

function currency_rates(): array {
    static $rates = null;
    if ($rates !== null) return $rates;
    $rates = SLS_DEFAULT_RATES;
    global $db;
    if (!isset($db) || !($db instanceof PDO)) return $rates;
    try {
        $rows = $db->query("SELECT code, rate_per_ngn, updated_at, checked_at FROM currency_rates")->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return $rates;
        $stale = false;
        foreach ($rows as $r) {
            if ((float) $r['rate_per_ngn'] > 0) $rates[$r['code']] = (float) $r['rate_per_ngn'];
            if ($r['code'] !== 'NGN' && strtotime($r['updated_at']) < time() - 86400 && strtotime($r['checked_at']) < time() - 3600) $stale = true;
        }
        if ($stale && currency_refresh_rates($db)) {
            foreach ($db->query("SELECT code, rate_per_ngn FROM currency_rates")->fetchAll(PDO::FETCH_ASSOC) as $r) {
                if ((float) $r['rate_per_ngn'] > 0) $rates[$r['code']] = (float) $r['rate_per_ngn'];
            }
        }
    } catch (Throwable $e) {
        // table missing (migration 108 not run) or DB error: fallback rates
    }
    return $rates;
}

// ── Visitor currency ────────────────────────────────────────────────────────

function currency_client_ip(): ?string {
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {
        if (empty($_SERVER[$k])) continue;
        $ip = trim(explode(',', $_SERVER[$k])[0]);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return $ip;
    }
    return null; // local / private address: nothing to look up
}

function currency_country(): ?string {
    if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['sls_country'])) return $_SESSION['sls_country'] ?: null;
    $cc = null;
    $cf = strtoupper((string) ($_SERVER['HTTP_CF_IPCOUNTRY'] ?? ''));
    if (preg_match('/^[A-Z]{2}$/', $cf) && !in_array($cf, ['XX', 'T1'], true)) {
        $cc = $cf;
    } elseif (($ip = currency_client_ip()) !== null) {
        $ctx  = stream_context_create(['http' => ['timeout' => 2, 'ignore_errors' => true]]);
        $json = @file_get_contents(SLS_GEO_FEED . urlencode($ip), false, $ctx);
        $d    = $json ? json_decode($json, true) : null;
        if (is_array($d) && !empty($d[0]['country']) && preg_match('/^[A-Z]{2}$/', $d[0]['country'])) $cc = $d[0]['country'];
    }
    if (session_status() === PHP_SESSION_ACTIVE) $_SESSION['sls_country'] = $cc ?: '';
    return $cc;
}

function currency_current(): string {
    static $code = null;
    if ($code !== null) return $code;
    $pick = strtoupper((string) ($_GET['currency'] ?? ''));
    if (isset(SLS_CURRENCIES[$pick])) {
        if (!headers_sent()) setcookie('sls_currency', $pick, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
        return $code = $pick;
    }
    $cookie = strtoupper((string) ($_COOKIE['sls_currency'] ?? ''));
    if (isset(SLS_CURRENCIES[$cookie])) return $code = $cookie;
    return $code = currency_for_country(currency_country());
}

// ── Output ──────────────────────────────────────────────────────────────────

/**
 * Course price for display. Selar-sold courses (selar_months set) are converted
 * from Naira into the visitor's currency, with the original price struck through
 * when discounted. Every other course keeps its existing $ format unchanged.
 */
function course_price_html(array $c, bool $withOriginal = true): string {
    if (empty($c['selar_months'])) {
        return '$' . number_format((float) ($c['price'] ?? 0), 2);
    }
    $code  = currency_current();
    $rates = currency_rates();
    $ngn   = (float) ($c['price'] ?? 0);
    $out   = currency_format($ngn, $code, $rates);
    if ($withOriginal && !empty($c['compare_price']) && (float) $c['compare_price'] > $ngn) {
        $out .= ' <s style="opacity:.6;font-weight:400;font-size:.8em;">' . currency_format((float) $c['compare_price'], $code, $rates) . '</s>';
    }
    if ($code !== 'NGN') {
        $out = '<span title="Approximate conversion. Listed price: ' . currency_format($ngn, 'NGN', $rates) . '. Checkout on Selar shows the exact amount.">' . $out . '</span>';
    }
    return $out;
}

/** Small ₦ / £ / $ chooser that keeps the rest of the query string. */
function currency_switcher_html(): string {
    $cur = currency_current();
    $q = $_GET; unset($q['currency']);
    $out = '<span class="sls-currency-switch" style="display:inline-flex;gap:4px;align-items:center;font-size:.85rem;">';
    foreach (SLS_CURRENCIES as $code => $meta) {
        $href = '?' . http_build_query($q + ['currency' => $code]);
        $on   = $code === $cur;
        $out .= '<a href="' . htmlspecialchars($href) . '" title="' . $meta['name'] . '" style="padding:2px 9px;border:1px solid ' . ($on ? '#0b77ff' : '#cbd5e1')
              . ';border-radius:4px;text-decoration:none;font-weight:' . ($on ? '700' : '500') . ';background:' . ($on ? '#e0efff' : '#fff') . ';color:#1b2430;">' . $meta['symbol'] . '</a>';
    }
    return $out . '</span>';
}
