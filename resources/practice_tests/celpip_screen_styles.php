<?php
// Shared look for CELPIP-style practice test screens (Reading, and reusable
// for future Listening pages). Modelled on real CELPIP-General screenshots
// from the official Prometric "CELPIP-General Overview" participant package
// (dark header bar + Next button, split info/work panels, blue accents).
?>
<style>
    .main-wrapper { padding: 1.25rem; min-height: 100vh; }
    .celpip-shell {
        max-width: 980px;
        margin: 0 auto;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 16px rgba(0,0,0,.1);
        border: 1px solid #d7dce0;
        background: #fff;
    }
    .celpip-header {
        background: #3d4750;
        color: #fff;
        padding: .7rem 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .celpip-header .title { font-weight: 700; font-size: .95rem; }
    .celpip-header .meta { display: flex; align-items: center; gap: .9rem; font-size: .82rem; color: #dfe3e6; }
    .celpip-header .meta strong { color: #fff; font-family: monospace; font-size: .95rem; }
    .celpip-next-btn {
        background: #2f6f9a;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: .35rem 1.1rem;
        font-weight: 700;
        font-size: .82rem;
        letter-spacing: .03em;
        cursor: pointer;
    }
    .celpip-next-btn:hover { background: #255a7d; }
    .celpip-next-btn:disabled { background: #6b7680; cursor: not-allowed; }
    .celpip-back-btn {
        background: transparent;
        color: #dfe3e6;
        border: 1px solid #5c6670;
        border-radius: 4px;
        padding: .35rem 1rem;
        font-weight: 600;
        font-size: .82rem;
        cursor: pointer;
    }
    .celpip-back-btn:hover { color: #fff; border-color: #8a949d; }
    .celpip-back-btn:disabled { opacity: .35; cursor: not-allowed; }

    .celpip-body { display: flex; min-height: 420px; }
    .celpip-panel {
        flex: 1 1 50%;
        max-height: 62vh;
        overflow-y: auto;
        padding: 1.1rem 1.25rem;
    }
    .celpip-panel.info { background: #eef1f3; border-right: 1px solid #d7dce0; }
    .celpip-panel.work { background: #fff; }
    .celpip-panel-label {
        display: flex;
        align-items: center;
        gap: .4rem;
        color: #1b6394;
        font-weight: 700;
        font-size: .85rem;
        margin-bottom: .6rem;
    }
    .celpip-panel-label i { font-size: 1rem; }
    .celpip-passage h4 { font-size: .92rem; font-weight: 700; margin: .9rem 0 .4rem; color: #1f2937; }
    .celpip-passage h4:first-child { margin-top: 0; }
    .celpip-passage p { font-size: .88rem; line-height: 1.75; color: #374151; }
    .celpip-passage .sub-divider { border-top: 1px dashed #c7ccd1; margin: 1.1rem 0; }

    .celpip-q-row { margin-bottom: 1rem; }
    .celpip-q-row .q-num { font-weight: 700; color: #1b6394; font-size: .85rem; }
    .celpip-q-row .q-text { font-size: .87rem; color: #1f2937; margin: .15rem 0 .4rem; }
    .celpip-select {
        width: 100%;
        border: 1px solid #c7ccd1;
        border-radius: 4px;
        padding: .4rem .6rem;
        font-size: .85rem;
        background: #fff;
        color: #1f2937;
    }
    .celpip-select:focus { outline: none; border-color: #2f6f9a; box-shadow: 0 0 0 2px rgba(47,111,154,.15); }
    .celpip-select.correct   { border-color: #198754; background: #eafaf1; }
    .celpip-select.incorrect { border-color: #dc3545; background: #fdecee; }
    .celpip-feedback-hint { font-size: .76rem; margin-top: .2rem; }
    .celpip-feedback-hint.ok  { color: #198754; }
    .celpip-feedback-hint.bad { color: #dc3545; }

    .celpip-diagram-image { width:100%; height:auto; border:1px solid #d7dce0; border-radius:6px; display:block; margin-top:.25rem; }
    .diagram-table { width:100%; border-collapse: collapse; font-size:.8rem; margin-top:.5rem; }
    .diagram-table th, .diagram-table td { border:1px solid #d7dce0; padding:.4rem .55rem; vertical-align:top; }
    .diagram-table th { background:#e2e8f0; }
    .schedule-table { width:100%; border-collapse: collapse; font-size:.8rem; margin-top: .5rem; }
    .schedule-table th, .schedule-table td { border:1px solid #d7dce0; padding:.4rem .55rem; vertical-align:top; }
    .schedule-table th { background:#e2e8f0; }
    .legend-pill { display:inline-block; background:#e2e8f0; border-radius:6px; padding:.2rem .55rem; margin:.15rem .15rem 0 0; font-size:.74rem; }
    .brochure-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:.6rem; margin-top:.5rem; }
    .brochure-card { background:#e2e8f0; border-radius:6px; padding:.7rem; font-size:.78rem; line-height:1.55; }
    .brochure-card strong { color:#1b6394; display:block; margin-bottom:.25rem; }

    .celpip-progress { display:flex; gap:.3rem; padding:.5rem 1.1rem; background:#f5f6f7; border-top:1px solid #d7dce0; }
    .celpip-progress .dot { flex:1; height:4px; border-radius:2px; background:#d7dce0; }
    .celpip-progress .dot.done { background:#2f6f9a; }
    .celpip-progress .dot.current { background:#1b6394; }

    .result-badge { display: inline-block; color: #fff; border-radius: 8px; padding: .4rem 1rem; font-size: .95rem; font-weight: 700; margin: .25rem; }

    @media (max-width: 767px) {
        .celpip-body { flex-direction: column; }
        .celpip-panel { max-height: none; }
        .celpip-panel.info { border-right: none; border-bottom: 1px solid #d7dce0; }
    }
</style>
