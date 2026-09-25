/*
 * Mock-test band report PDF -- the ONE builder used by both the student's
 * My Results page (academy/resources/practice_tests/my_results.php) and the
 * tutor's "Preview what the student receives" panel in sls-admin, so what the
 * tutor previews is byte-for-byte what the student downloads.
 *
 * Needs jsPDF 2.x loaded first (window.jspdf.jsPDF).
 *
 * jsPDF does not wrap or paginate on its own: text drawn past the bottom edge
 * is simply clipped. Every piece of free text here therefore goes through
 * para(), which wraps to the page width and starts a new page before any line
 * would cross the bottom margin -- nothing is ever cut to a fixed number of
 * lines. Long instructor comments are the whole point of this report.
 *
 * jsPDF's built-in Helvetica only has Latin-1/WinAnsi glyphs. Anything outside
 * that (arrows, ticks, curly quotes in other scripts...) prints as garbage, so
 * pdfSafe() maps or strips such characters instead of letting them corrupt the
 * line.
 *
 * raw (what PHP hands over, straight from the saved columns) -> composeMockReport(raw) -> payload
 * -> buildMockReportPDF(payload). The tutor's preview in sls-admin calls the very same
 * functions, so it cannot show something different from what the student gets.
 *
 * raw = {
 *   title, name, date, label ('Band'|'CLB'),
 *   l, r, w, s, overall,                 // numbers or numeric strings (null = not scored)
 *   l_score, r_score,                    // "27/40"
 *   writing_by ('ai'|'instructor'), writing_notes, writing_ai, speaking_notes,
 *   tasks: [{n, title, score, analysis}] // speaking tasks
 * }
 */
(function (global) {
    'use strict';

    // Characters WinAnsi (jsPDF's default encoding) can draw beyond U+00FF.
    var WINANSI_EXTRA = '‘’‚“”„•–—…€™†‡‰‹›ŠšŽžŒœŸƒˆ˜';
    var REPLACEMENTS = {
        '→': '->', '←': '<-', '✓': 'v', '✔': 'v', '✗': 'x', '✘': 'x',
        '‑': '-', '‒': '-', '−': '-', '‐': '-',
        ' ': ' ', ' ': ' ', ' ': ' ', ' ': ' ', '​': '', '﻿': '',
        '′': "'", '″': '"', '≥': '>=', '≤': '<=', '≠': '!=', '≈': '~',
        '★': '*', '☆': '*', '●': '-', '○': '-', '‣': '-', '▪': '-', '▫': '-'
    };

    function pdfSafe(input) {
        var s = String(input == null ? '' : input).replace(/\r\n?/g, '\n');
        if (s.normalize) s = s.normalize('NFC');
        var out = '';
        for (var i = 0; i < s.length; i++) {
            var ch = s.charAt(i), code = s.charCodeAt(i);
            if (ch === '\n' || ch === '\t' || (code >= 0x20 && code <= 0xFF && code !== 0x7F && !(code >= 0x80 && code <= 0x9F))) {
                out += ch === '\t' ? '    ' : ch;
            } else if (WINANSI_EXTRA.indexOf(ch) !== -1) {
                out += ch;
            } else if (Object.prototype.hasOwnProperty.call(REPLACEMENTS, ch)) {
                out += REPLACEMENTS[ch];
            } else if (s.normalize) {
                // Accented letters outside Latin-1 (e.g. Ő, ș): drop the accent, keep the letter.
                var base = ch.normalize('NFD').replace(/[̀-ͯ]/g, '');
                out += (base && base.charCodeAt(0) <= 0xFF && base !== ch) ? base : '';
                // Surrogate halves / emoji / other scripts have no glyph: dropped.
            }
        }
        return out;
    }

    var AI_FAILED_RE = /\[AI GRADING FAILED\]|AI grading temporarily unavailable|Could not parse AI response/i;

    function has(v) { return v != null && String(v).trim() !== ''; }
    function fmt1(v) {
        var n = parseFloat(v);
        return (v == null || v === '' || isNaN(n)) ? '-' : n.toFixed(1);
    }
    // CELPIP levels are whole numbers. The overall is only an estimate (average of four levels,
    // kept to the nearest half), so a half-point is shown as the two levels either side: 8.5 -> "8-9".
    // (Same rule as mock_fmt_score() in academy/includes/mock_report.php.)
    function fmtCelpip(v, isOverall) {
        var n = parseFloat(v);
        if (v == null || v === '' || isNaN(n)) return '-';
        if (isOverall && Math.abs(n - Math.floor(n) - 0.5) < 0.001) return Math.floor(n) + '-' + Math.ceil(n);
        return String(Math.round(n));
    }

    // Decide what the student is shown. A failed AI grade is a tutor-facing
    // warning, never student-facing text.
    function composeMockReport(raw) {
        var label = raw.label || 'Band';
        var byInstructor = raw.writing_by === 'instructor';
        var aiOk = has(raw.writing_ai) && !AI_FAILED_RE.test(raw.writing_ai);

        var writing = [];
        if (has(raw.writing_notes)) writing.push({ key: 'writing_notes', heading: 'Instructor comments', text: raw.writing_notes });
        if (aiOk) writing.push({
            key: 'ai',
            heading: byInstructor ? 'Automated feedback on your submission (for reference)' : 'Examiner feedback',
            text: raw.writing_ai
        });

        var speaking = [];
        if (has(raw.speaking_notes)) speaking.push({ key: 'speaking_notes', heading: 'Instructor comments', text: raw.speaking_notes });
        (raw.tasks || []).forEach(function (t) {
            if (!has(t.score) && !has(t.analysis)) return;
            speaking.push({
                key: 'task',
                heading: (has(t.title) && /^task\s*\d/i.test(t.title) ? t.title : 'Task ' + t.n + (has(t.title) ? ' - ' + t.title : '')) + (has(t.score) ? '  (' + label + ' ' + t.score + ')' : ''),
                text: has(t.analysis) ? t.analysis : '(score only - no written comment)'
            });
        });

        var celpip = label === 'CLB Level';
        var F = celpip ? function (v) { return fmtCelpip(v, false); } : fmt1;
        var O = celpip ? function (v) { return fmtCelpip(v, true); } : fmt1;
        return {
            title: raw.title, name: raw.name, date: raw.date, label: label,
            overall: O(raw.overall), l: F(raw.l), r: F(raw.r), w: F(raw.w), s: F(raw.s),
            l_score: raw.l_score || '', r_score: raw.r_score || '',
            w_by: byInstructor ? 'Instructor' : 'AI-graded',
            aiFailed: has(raw.writing_ai) && AI_FAILED_RE.test(raw.writing_ai),
            sections: [
                { label: 'LISTENING', band: F(raw.l), score: raw.l_score || '',
                  note: 'Every question with your answer and the correct one is in your online results.', blocks: [] },
                { label: 'READING', band: F(raw.r), score: raw.r_score || '',
                  note: 'Every passage and question with your answer and the correct one is in your online results.', blocks: [] },
                { label: 'WRITING', band: F(raw.w), score: '',
                  note: writing.length ? '' : 'Your full responses are in your online results.', blocks: writing },
                { label: 'SPEAKING', band: F(raw.s), score: '',
                  note: speaking.length ? '' : 'Instructor-graded.', blocks: speaking }
            ]
        };
    }

    function buildMockReportPDF(data, opts) {
        opts = opts || {};
        var jsPDF = global.jspdf.jsPDF;
        var doc = new jsPDF({ unit: 'mm', format: 'a4' });

        var navy = [14, 44, 96], blue = [14, 165, 233], pink = [236, 72, 153],
            dark = [15, 23, 42], muted = [100, 116, 139], light = [241, 245, 249], white = [255, 255, 255];
        var L = 15, R = 195, W = 180;
        var TOP_CONT = 24;      // where content restarts on pages 2+
        var BOTTOM = 274;       // last usable baseline (footer bar starts at 282)
        var y = 0;

        var label = data.label || 'Band';
        var S = pdfSafe;

        function pageHeader(first) {
            doc.setFillColor.apply(doc, navy);
            if (first) {
                doc.rect(0, 0, 210, 46, 'F');
                doc.setTextColor(180, 210, 255); doc.setFontSize(7.5); doc.setFont('helvetica', 'italic');
                doc.text('Confidential Assessment Report', R, 8, { align: 'right' });
                doc.setTextColor.apply(doc, white); doc.setFontSize(22); doc.setFont('helvetica', 'bold');
                // Long mock titles must not run off the page.
                var tl = doc.splitTextToSize(S(data.title).toUpperCase(), W);
                doc.setFontSize(tl.length > 1 ? 15 : 22);
                doc.text(tl.slice(0, 2), L, tl.length > 1 ? 17 : 22);
                doc.setFontSize(10.5); doc.setFont('helvetica', 'italic');
                doc.text('Full Band Assessment Report', L, 31);
                doc.setDrawColor(100, 140, 200); doc.setLineWidth(0.25); doc.line(L, 35, R, 35);
                doc.setFontSize(8); doc.setFont('helvetica', 'normal'); doc.setTextColor(180, 210, 255);
                doc.text('Scholarly Language Services', R, 43, { align: 'right' });
            } else {
                doc.rect(0, 0, 210, 14, 'F');
                doc.setTextColor.apply(doc, white); doc.setFontSize(9); doc.setFont('helvetica', 'bold');
                doc.text(S(data.title).toUpperCase().slice(0, 60), L, 9);
                doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor(180, 210, 255);
                doc.text(S(data.name), R, 9, { align: 'right' });
            }
        }

        function newPage() {
            doc.addPage();
            pageHeader(false);
            y = TOP_CONT;
        }
        function ensure(h) { if (y + h > BOTTOM) newPage(); }

        // Wrapped text that flows across pages, line by line.
        function para(text, o) {
            o = o || {};
            var size = o.size || 9.5, indent = o.indent || 0;
            var lh = size * 0.3528 * 1.45;
            // Style is re-applied for every line: a page break draws the page header,
            // which changes font/size/colour, and the text after it must not inherit that.
            var style = function () {
                doc.setFont('helvetica', o.bold ? 'bold' : (o.italic ? 'italic' : 'normal'));
                doc.setFontSize(size);
                doc.setTextColor.apply(doc, o.color || dark);
            };
            style();
            var lines = doc.splitTextToSize(S(text), W - indent);
            for (var i = 0; i < lines.length; i++) {
                ensure(lh);
                style();
                doc.text(lines[i], L + indent, y);
                y += lh;
            }
            y += (o.gap == null ? 1.5 : o.gap);
        }

        function sectionBar(name, bandText) {
            ensure(10 + 14);   // never leave a bar stranded at the bottom of a page
            doc.setFillColor.apply(doc, navy); doc.rect(L, y, W, 10, 'F');
            doc.setTextColor.apply(doc, white); doc.setFontSize(9.5); doc.setFont('helvetica', 'bold');
            doc.text(S(name), L + 5, y + 7);
            doc.text(S(bandText), R, y + 7, { align: 'right' });
            y += 15;
        }

        // ── Page 1: header, candidate, score table, overall ──
        pageHeader(true);
        doc.setTextColor.apply(doc, dark); doc.setFontSize(12.5); doc.setFont('helvetica', 'bold');
        doc.text('Candidate: ' + S(data.name), L, 59);
        doc.setFontSize(9); doc.setFont('helvetica', 'normal'); doc.setTextColor.apply(doc, muted);
        doc.text('Date: ' + S(data.date), L, 66);

        var tY = 73, colW = 45;
        var cols = [
            { label: 'Listening', band: data.l, sub: data.l_score || '' },
            { label: 'Reading',   band: data.r, sub: data.r_score || '' },
            { label: 'Writing',   band: data.w, sub: data.w_by || '' },
            { label: 'Speaking',  band: data.s, sub: 'Instructor' }
        ];
        doc.setFillColor.apply(doc, navy); doc.rect(L, tY, W, 10, 'F');
        doc.setTextColor.apply(doc, white); doc.setFontSize(8.5); doc.setFont('helvetica', 'bold');
        cols.forEach(function (c, i) { doc.text(c.label, L + colW * i + colW / 2, tY + 7, { align: 'center' }); });
        doc.setFillColor.apply(doc, light); doc.rect(L, tY + 10, W, 22, 'F');
        doc.setDrawColor(226, 232, 240); doc.setLineWidth(0.2);
        for (var i = 1; i < 4; i++) doc.line(L + colW * i, tY + 10, L + colW * i, tY + 32);
        doc.setFontSize(22); doc.setFont('helvetica', 'bold'); doc.setTextColor.apply(doc, navy);
        cols.forEach(function (c, i) { doc.text(S(c.band), L + colW * i + colW / 2, tY + 26, { align: 'center' }); });
        doc.setFontSize(7.5); doc.setFont('helvetica', 'normal'); doc.setTextColor.apply(doc, muted);
        cols.forEach(function (c, i) { if (c.sub) doc.text(S(c.sub), L + colW * i + colW / 2, tY + 31, { align: 'center' }); });

        var oY = tY + 34;
        doc.setFillColor.apply(doc, navy); doc.rect(L, oY, 120, 14, 'F');
        doc.setTextColor.apply(doc, white); doc.setFontSize(10); doc.setFont('helvetica', 'bold');
        doc.text('OVERALL ' + S(label).toUpperCase() + ' SCORE', L + 5, oY + 9.5);
        doc.setFillColor.apply(doc, pink); doc.rect(L + 120, oY, 60, 14, 'F');
        doc.setFontSize(20); doc.text(S(data.overall), L + 150, oY + 10.5, { align: 'center' });
        var nums = [data.l, data.r, data.w, data.s].map(parseFloat);
        if (nums.every(function (n) { return isFinite(n); })) {
            var avg = ((nums[0] + nums[1] + nums[2] + nums[3]) / 4).toFixed(2);
            doc.setFontSize(7.5); doc.setFont('helvetica', 'italic'); doc.setTextColor.apply(doc, muted);
            var avgLine = '(' + data.l + ' + ' + data.r + ' + ' + data.w + ' + ' + data.s + ') / 4 = ' + avg
                + (label === 'CLB Level' ? '  ->  estimated ' + data.overall : '  ->  rounded to ' + data.overall);
            // CELPIP reports each skill's CLB level separately; the single figure is only an estimate.
            if (label === 'CLB Level') avgLine += '  (an estimate - CELPIP reports each skill\'s CLB level separately, not a single composite score)';
            doc.text(doc.splitTextToSize(S(avgLine), W), L, oY + 21);
        }

        y = oY + 34;

        // ── Sections: as much text as the tutor wrote, on as many pages as it takes ──
        (data.sections || []).forEach(function (sec) {
            sectionBar(sec.label, label + ' ' + sec.band);
            if (sec.score) para('Raw score: ' + sec.score, { bold: true, size: 9 });
            if (sec.note)  para(sec.note, { italic: true, color: muted, size: 8.5, gap: 2.5 });
            (sec.blocks || []).forEach(function (b) {
                if (!b || !String(b.text || '').trim()) return;
                if (b.heading) {
                    ensure(6 + 6);   // keep a heading with at least its first lines
                    para(b.heading, { bold: true, size: 9, color: navy, gap: 0.8 });
                }
                para(b.text, { size: 9.5, indent: 2, gap: 3.5 });
            });
            y += 3;
        });

        // ── Closing pointer ──
        ensure(24);
        doc.setFillColor(224, 242, 254); doc.rect(L, y, W, 20, 'F');
        doc.setDrawColor.apply(doc, blue); doc.setLineWidth(0.4); doc.line(L, y, L, y + 20);
        doc.setTextColor.apply(doc, dark); doc.setFontSize(9); doc.setFont('helvetica', 'bold');
        doc.text('Full detailed results: academy.slslanguage.com', L + 5, y + 8);
        doc.setFontSize(8); doc.setFont('helvetica', 'normal'); doc.setTextColor.apply(doc, muted);
        doc.text('Log in to see every question, the correct answers and your full responses.', L + 5, y + 15);

        // ── Footer + page numbers on every page (needs the final page count) ──
        var pages = doc.getNumberOfPages();
        var generated = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
        for (var p = 1; p <= pages; p++) {
            doc.setPage(p);
            doc.setFillColor.apply(doc, navy); doc.rect(0, 282, 210, 15, 'F');
            doc.setTextColor(180, 210, 255); doc.setFontSize(7.5); doc.setFont('helvetica', 'normal');
            doc.text('Scholarly Language Services  ·  slslanguage.com', L, 290);
            doc.text('Generated ' + generated + '  ·  Page ' + p + ' of ' + pages, R, 290, { align: 'right' });
        }

        return doc;
    }

    function downloadMockReportPDF(raw) {
        var data = composeMockReport(raw);
        var doc = buildMockReportPDF(data);
        var safe = function (s) { return String(s || '').replace(/[^A-Za-z0-9]+/g, '_').replace(/^_|_$/g, ''); };
        doc.save('Mock_Report_' + safe(data.name) + '_' + safe(data.date) + '.pdf');
        return doc;
    }

    global.pdfSafe = pdfSafe;
    global.composeMockReport = composeMockReport;
    global.buildMockReportPDF = buildMockReportPDF;
    global.downloadMockReportPDF = downloadMockReportPDF;
})(window);
