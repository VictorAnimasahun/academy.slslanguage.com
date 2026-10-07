/*
 * FAQ chatbox. Loaded by the SLS website and the EduHub start page:
 *   <script src="https://academy.slslanguage.com/assets/js/faq_chat.js" defer></script>
 * The chat talks to api/faq_chat.php next to this folder's parent, so it works from any origin in the
 * endpoint's allowlist. Builds its own button, panel and styles; nothing else is needed on the page.
 */
(function () {
    if (window.__slsFaqChat) return;
    window.__slsFaqChat = true;

    var endpoint = new URL('../../api/faq_chat.php', document.currentScript ? document.currentScript.src : window.location.href).href;
    var history = [];
    var busy = false;

    var css = [
        '#sls-faq-btn{position:fixed;right:18px;bottom:18px;z-index:9999;background:#1F1A5C;color:#fff;border:0;border-radius:28px;padding:12px 18px;font:600 15px/1 system-ui,-apple-system,Segoe UI,Roboto,sans-serif;cursor:pointer;box-shadow:0 4px 14px rgba(31,26,92,.35)}',
        '#sls-faq-panel{position:fixed;right:18px;bottom:76px;z-index:9999;width:340px;max-width:calc(100vw - 36px);height:460px;max-height:calc(100vh - 110px);background:#fff;border-radius:16px;border:1px solid #e5e7eb;display:none;flex-direction:column;overflow:hidden;font:14px/1.45 system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#111827;box-shadow:0 10px 30px rgba(0,0,0,.18)}',
        '#sls-faq-panel.open{display:flex}',
        '#sls-faq-head{background:#1F1A5C;color:#fff;padding:12px 14px;display:flex;justify-content:space-between;align-items:center;font-weight:600}',
        '#sls-faq-head button{background:none;border:0;color:#fff;font-size:20px;line-height:1;cursor:pointer}',
        '#sls-faq-log{flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px;background:#f8fafc}',
        '.sls-faq-msg{max-width:85%;padding:8px 11px;border-radius:12px;white-space:pre-wrap;word-wrap:break-word}',
        '.sls-faq-msg.user{align-self:flex-end;background:#0b5fff;color:#fff;border-bottom-right-radius:4px}',
        '.sls-faq-msg.bot{align-self:flex-start;background:#fff;border:1px solid #e5e7eb;border-bottom-left-radius:4px}',
        '.sls-faq-msg.err{align-self:flex-start;color:#b91c1c;background:#fef2f2}',
        '#sls-faq-form{display:flex;gap:8px;padding:10px;border-top:1px solid #e5e7eb}',
        '#sls-faq-input{flex:1;border:1px solid #d1d5db;border-radius:10px;padding:9px 10px;font:inherit;resize:none;height:40px}',
        '#sls-faq-send{background:#1F1A5C;color:#fff;border:0;border-radius:10px;padding:0 14px;font:600 14px system-ui,sans-serif;cursor:pointer}',
        '#sls-faq-send:disabled{opacity:.5;cursor:default}'
    ].join('');

    var style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    var btn = document.createElement('button');
    btn.id = 'sls-faq-btn';
    btn.type = 'button';
    btn.textContent = 'Ask a question';
    btn.setAttribute('aria-expanded', 'false');

    var panel = document.createElement('div');
    panel.id = 'sls-faq-panel';
    panel.setAttribute('role', 'dialog');
    panel.setAttribute('aria-label', 'SLS assistant');
    panel.innerHTML =
        '<div id="sls-faq-head"><span>SLS assistant</span><button type="button" aria-label="Close">&times;</button></div>' +
        '<div id="sls-faq-log"></div>' +
        '<form id="sls-faq-form"><textarea id="sls-faq-input" maxlength="600" placeholder="Type your question" aria-label="Your question"></textarea>' +
        '<button id="sls-faq-send" type="submit">Send</button></form>';

    document.body.appendChild(btn);
    document.body.appendChild(panel);

    var log = panel.querySelector('#sls-faq-log');
    var form = panel.querySelector('#sls-faq-form');
    var input = panel.querySelector('#sls-faq-input');
    var send = panel.querySelector('#sls-faq-send');

    function setOpen(open) {
        panel.classList.toggle('open', open);
        btn.setAttribute('aria-expanded', String(open));
        btn.style.display = open ? 'none' : '';
        if (open) input.focus();
    }

    function addMsg(text, kind) {
        var el = document.createElement('div');
        el.className = 'sls-faq-msg ' + kind;
        el.textContent = text; // textContent, never innerHTML: replies are plain text
        log.appendChild(el);
        log.scrollTop = log.scrollHeight;
        return el;
    }

    if (!history.length) {
        addMsg('Hi! Ask me about SLS, EduHub, courses, or anything about IELTS, CELPIP or English.', 'bot');
    }

    btn.addEventListener('click', function () { setOpen(true); });
    panel.querySelector('#sls-faq-head button').addEventListener('click', function () { setOpen(false); });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var question = input.value.trim();
        if (!question || busy) return;

        busy = true;
        send.disabled = true;
        input.value = '';
        addMsg(question, 'user');
        history.push({ role: 'user', content: question });
        var thinking = addMsg('Thinking...', 'bot');

        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ messages: history.slice(-10) })
        })
            .then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (data) {
                    if (!res.ok) throw new Error(data.error || 'The assistant could not answer just now.');
                    return data;
                });
            })
            .then(function (data) {
                thinking.textContent = data.reply;
                history.push({ role: 'assistant', content: data.reply });
            })
            .catch(function (err) {
                thinking.className = 'sls-faq-msg err';
                thinking.textContent = err.message || 'Something went wrong. Please try again.';
                history.pop(); // drop the question so the user can resend it
            })
            .finally(function () {
                busy = false;
                send.disabled = false;
                log.scrollTop = log.scrollHeight;
            });
    });
})();
