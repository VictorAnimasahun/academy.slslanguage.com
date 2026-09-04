<?php
// Shared CELPIP-style screen renderer for Reading practice tests.
// Expects $parts (the part=>sections data array) and $maxScore to already
// be defined by the including page. One "screen" per top-level Part,
// matching the real CELPIP interface: dark header bar with a timer and a
// Next button, and a split info/work panel below it (passage or diagram on
// the left, numbered questions with dropdown answers on the right).

function renderCelpipInfoPanel(array $sections): void {
    foreach ($sections as $i => $sec) {
        if ($i > 0) echo '<div class="sub-divider"></div>';
        switch ($sec['type']) {
            case 'mcq':
                if (!empty($sec['passage'])) {
                    if (!empty($sec['passage_title'])) echo '<h4>' . htmlspecialchars($sec['passage_title']) . '</h4>';
                    echo $sec['passage'];
                }
                break;
            case 'paragraph_match':
                echo '<h4>' . htmlspecialchars($sec['passage_title']) . '</h4>';
                foreach ($sec['paragraphs'] as $letter => $text) {
                    echo '<p><strong>' . $letter . '.</strong> ' . htmlspecialchars($text) . '</p>';
                }
                echo '<p class="text-muted fst-italic mb-0"><strong>E.</strong> Not given in any of the paragraphs.</p>';
                break;
            case 'diagram':
                echo '<h4>' . htmlspecialchars($sec['passage_title']) . '</h4>';
                echo $sec['passage'];
                echo '<div class="table-responsive"><table class="diagram-table"><thead><tr><th>Plant</th><th>Difficulty</th><th>Season</th><th>Notes</th></tr></thead><tbody>';
                foreach ($sec['diagram_rows'] as $row) {
                    echo '<tr><td><strong>' . htmlspecialchars($row['plant']) . '</strong></td><td>' . htmlspecialchars($row['difficulty']) . '</td><td>' . htmlspecialchars($row['season']) . '</td><td><ul class="mb-0 ps-3">';
                    foreach ($row['notes'] as $n) echo '<li>' . htmlspecialchars($n) . '</li>';
                    echo '</ul></td></tr>';
                }
                echo '</tbody></table></div>';
                break;
            case 'schedule':
                echo '<h4>' . htmlspecialchars($sec['passage_title']) . '</h4>';
                foreach ($sec['group_legend'] as $code => $desc) {
                    echo '<span class="legend-pill"><strong>' . htmlspecialchars($code) . '</strong> – ' . htmlspecialchars($desc) . '</span>';
                }
                echo '<div class="table-responsive"><table class="schedule-table"><thead><tr><th></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th></tr></thead><tbody>';
                foreach ($sec['schedule_rows'] as $row) {
                    echo '<tr><td><strong>' . htmlspecialchars($row['week']) . '</strong></td><td>' . $row['mon'] . '</td><td>' . $row['tue'] . '</td><td>' . $row['wed'] . '</td></tr>';
                }
                echo '</tbody></table></div>';
                break;
            case 'brochure':
                echo '<h4>' . htmlspecialchars($sec['passage_title']) . '</h4><div class="brochure-grid">';
                foreach ($sec['styles'] as $style) {
                    echo '<div class="brochure-card"><strong>' . htmlspecialchars($style['name']) . '</strong>' . htmlspecialchars($style['desc']) . '</div>';
                }
                echo '</div>';
                break;
        }
    }
}

function renderCelpipWorkPanel(array $sections): void {
    foreach ($sections as $sec) {
        if (empty($sec['questions'])) continue;
        echo '<p class="fw-semibold small mb-2" style="color:#1f2937;">' . $sec['instructions'] . '</p>';
        $options = $sec['options'] ?? null;
        $optionLabels = $sec['option_labels'] ?? [];
        foreach ($sec['questions'] as $q) {
            echo '<div class="celpip-q-row">';
            echo '<div class="q-num">' . $q['q'] . '.</div>';
            echo '<div class="q-text">' . htmlspecialchars($q['text']) . '</div>';
            echo '<select class="celpip-select" data-q="' . $q['q'] . '"><option value="">– Select –</option>';
            $rowOptions = $options ?? array_keys($q['options']);
            foreach ($rowOptions as $letter) {
                $text = $options ? ($optionLabels[$letter] ?? ('Paragraph ' . $letter)) : $q['options'][$letter];
                echo '<option value="' . strtolower($letter) . '">' . htmlspecialchars($letter) . '. ' . htmlspecialchars($text) . '</option>';
            }
            echo '</select></div>';
        }
    }
}
?>
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <span class="section-badge" style="background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; padding:.3rem 1.1rem; border-radius:50px; font-weight:700; font-size:.8rem;">Reading</span>
    <span class="text-muted small">38 Questions · 55 min</span>
</div>

<div class="celpip-shell" id="celpipShell">
    <?php foreach ($parts as $pNum => $part): ?>
    <div class="celpip-screen" data-part="<?= $pNum ?>" style="<?= $pNum === 1 ? '' : 'display:none;' ?>">
        <div class="celpip-header">
            <div class="title"><?= htmlspecialchars($part['title']) ?>: <?= htmlspecialchars($part['label']) ?></div>
            <div class="meta">
                <span>Time remaining: <strong id="timerDisplay-<?= $pNum ?>" class="timerDisplayShared">55:00</strong></span>
                <?php if ($pNum > 1): ?><button type="button" class="celpip-back-btn" onclick="celpipGoBack()">Back</button><?php endif; ?>
                <button type="button" class="celpip-next-btn" onclick="celpipGoNext()"><?= $pNum < count($parts) ? 'Next' : 'Finish Test' ?></button>
            </div>
        </div>
        <div class="celpip-body">
            <div class="celpip-panel info">
                <div class="celpip-panel-label"><i class="bi bi-info-circle-fill"></i> Read the following.</div>
                <div class="celpip-passage"><?php renderCelpipInfoPanel($part['sections']); ?></div>
            </div>
            <div class="celpip-panel work">
                <div class="celpip-panel-label"><i class="bi bi-info-circle-fill"></i> Answer the questions below.</div>
                <?php renderCelpipWorkPanel($part['sections']); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="celpip-progress">
        <?php for ($i = 1; $i <= count($parts); $i++): ?>
        <div class="dot <?= $i === 1 ? 'current' : '' ?>" id="progressDot-<?= $i ?>"></div>
        <?php endfor; ?>
    </div>
</div>
