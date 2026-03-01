<x-application-tools-wrapper>

@php
    $used  = $used ?? 0;
    $limit = $limit ?? 2;
    $plan  = $plan ?? 'free';
    $reset_at = $reset_at ?? now()->addHours(24)->toIso8601String();
    $reset_hours = $reset_hours ?? 24;
    $isLimitReached = $used >= $limit;

    $planIcons = [
        'free'    => 'bi-star-fill',
        'classic' => 'bi-shield-fill',
        'plus'    => 'bi-rocket-fill',
        'pro'     => 'bi-gem',
    ];
    $planIcon = $planIcons[$plan] ?? 'bi-star-fill';
@endphp

<style>
    /* ═══════════════════════════════════════════════════════════════
       PREMIUM TEXT SUMMARIZER STYLES
       No gradients. Solid darker primary (#4A00A0).
       ═══════════════════════════════════════════════════════════════ */

    .ts-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 16px 60px;
    }

    /* HEADER - MATCH AI HUMANIZER */
    .ts-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: nowrap;
        gap: 12px;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        white-space: nowrap;
        padding: 20px 0;
    }

    .ts-header .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ts-header .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #888;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 10px;
        transition: all 0.2s;
        border: 1px solid #eee;
    }

    .ts-header .back-link:hover {
        color: #6000C2;
        background: rgba(96, 0, 194, 0.04);
        border-color: #6000C2;
    }

    .ts-header .header-divider {
        width: 1px;
        height: 24px;
        background: #eee;
        margin: 0 4px;
    }

    .ts-header .tool-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a1a2e;
        margin: 0;
        line-height: 1.2;
    }

    .ts-header .title-group {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .ts-header .tool-description-mini {
        color: #777;
        font-size: 13px;
        font-weight: 500;
        margin-top: 2px;
    }

    .ts-header .history-btn {
        background: rgba(0, 0, 0, 0.03);
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .ts-header .history-btn:hover {
        background: rgba(96, 0, 194, 0.08);
        color: #6000C2;
    }

    [theme-mode="dark"] .ts-header .back-link { border-color: #333; color: #aaa; }
    [theme-mode="dark"] .ts-header .tool-title { color: #f0f0f0; }
    [theme-mode="dark"] .ts-header .header-divider { background: #333; }
    [theme-mode="dark"] .ts-header .history-btn { background: rgba(255,255,255, 0.05); color: #aaa; }
    [theme-mode="dark"] .ts-header .history-btn:hover { background: rgba(167, 139, 250, 0.1); color: #a78bfa; }

    .usage-showcase-premium-v2 {
        background: rgba(96, 0, 194, 0.04) !important;
        border: 1px solid rgba(96, 0, 194, 0.12) !important;
        border-radius: 20px !important;
        padding: 24px 32px !important;
        margin-bottom: 32px;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        transition: all 0.3s ease;
    }

    [theme-mode="dark"] .usage-showcase-premium-v2 {
        background: rgba(96, 0, 194, 0.08) !important;
        border-color: rgba(96, 0, 194, 0.2) !important;
    }

    .usage-stats-grid {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 32px;
        align-items: center;
    }

    .usage-circle-container {
        position: relative;
        width: 120px;
        height: 120px;
        flex-shrink: 0;
    }

    .usage-circle {
        transform: rotate(-90deg);
        width: 100%;
        height: 100%;
    }

    .usage-circle-bg {
        fill: none;
        stroke: rgba(96, 0, 194, 0.1);
        stroke-width: 8;
    }

    .usage-circle-progress {
        fill: none;
        stroke: #6000C2;
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dashoffset 1s ease;
    }

    .usage-circle-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 100%;
    }

    .usage-number {
        font-size: 32px;
        font-weight: 800;
        color: #6000C2;
        line-height: 1;
    }

    .usage-total {
        font-size: 16px;
        font-weight: 600;
        color: #999;
    }

    .usage-info-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .usage-info-card {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s ease;
    }

    .usage-info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .usage-info-card.highlight {
        background: #6000C2 !important;
        border-color: #6000C2 !important;
    }

    .usage-info-card.highlight .usage-info-label,
    .usage-info-card.highlight .usage-info-value {
        color: white !important;
    }

    .usage-info-card.highlight .usage-info-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    [theme-mode="dark"] .usage-info-card {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .usage-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(96, 0, 194, 0.1);
        color: #6000C2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .usage-info-content {
        flex: 1;
        min-width: 0;
    }

    .usage-info-label {
        font-size: 11px;
        font-weight: 700;
        color: #888;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .usage-info-value {
        font-size: 20px;
        font-weight: 800;
        color: #26282D;
        line-height: 1.2;
    }

    [theme-mode="dark"] .usage-info-label { color: #aaa; }
    [theme-mode="dark"] .usage-info-value { color: #f8f9fa; }

    @media (max-width: 768px) {
        .usage-showcase-premium-v2 {
            padding: 16px;
            border-radius: 20px;
            margin-bottom: 24px;
        }
        .usage-stats-grid {
            grid-template-columns: auto 1fr;
            gap: 16px;
        }
        .usage-circle-container {
            width: 70px;
            height: 70px;
        }
        .usage-number { font-size: 18px; }
        .usage-total { font-size: 11px; }
        .usage-circle-bg, .usage-circle-progress { stroke-width: 10; }
        
        .usage-info-cards {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .usage-info-card {
            padding: 10px 14px;
            border-radius: 12px;
            gap: 12px;
        }
        .usage-info-icon {
            width: 32px;
            height: 32px;
            font-size: 16px;
            border-radius: 8px;
        }
        .usage-info-value { font-size: 16px; }
        .usage-info-label { font-size: 9px; }
    }

    /* Ash Button */
    .btn-ash-ts {
        background-color: #ccc !important;
        border-color: #bbb !important;
        color: #888 !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        pointer-events: auto !important;
    }
    [theme-mode="dark"] .btn-ash-ts {
        background-color: #333 !important;
        border-color: #444 !important;
        color: #666 !important;
    }

    /* CONTROLS BAR */
    .ts-controls {
        background: #f8f6fc;
        border: 1px solid #e8e0f0;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    [theme-mode="dark"] .ts-controls {
        background: #1a1a2e;
        border-color: #2a2a4a;
    }

    .ts-control-group { flex: 1; min-width: 200px; }
    .ts-control-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #666;
        margin-bottom: 8px;
    }
    [theme-mode="dark"] .ts-control-label { color: #aaa; }

    /* Length Slider */
    .ts-slider-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ts-slider {
        flex: 1;
        -webkit-appearance: none;
        appearance: none;
        height: 6px;
        background: #ddd;
        border-radius: 3px;
        outline: none;
    }
    .ts-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        background: #4A00A0;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(74, 0, 160, 0.3);
    }
    .ts-slider-label {
        font-size: 14px;
        font-weight: 700;
        color: #4A00A0;
        min-width: 90px;
        text-align: right;
    }
    [theme-mode="dark"] .ts-slider { background: #333; }
    [theme-mode="dark"] .ts-slider-label { color: #b388ff; }

    /* Format Pills */
    .ts-format-pills {
        display: flex;
        gap: 6px;
    }
    .ts-format-pill {
        border: 2px solid #ddd;
        background: white;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: #555;
    }
    .ts-format-pill:hover { border-color: #4A00A0; color: #4A00A0; }
    .ts-format-pill.active {
        background: #4A00A0;
        border-color: #4A00A0;
        color: white;
    }
    [theme-mode="dark"] .ts-format-pill {
        background: #1a1a2e;
        border-color: #333;
        color: #aaa;
    }
    [theme-mode="dark"] .ts-format-pill.active {
        background: #4A00A0;
        border-color: #4A00A0;
        color: white;
    }

    /* TWO COLUMN LAYOUT */
    .ts-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) {
        .ts-columns { grid-template-columns: 1fr; }
    }

    .ts-column {
        background: white;
        border: 2px solid #e8e0f0;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    [theme-mode="dark"] .ts-column {
        background: #111;
        border-color: #2a2a4a;
    }

    .ts-column-label {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4A00A0;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ts-textarea {
        width: 100%;
        border: none;
        outline: none;
        resize: none;
        font-size: 15px;
        line-height: 1.7;
        color: #333;
        background: transparent;
        flex: 1;
        min-height: 320px;
    }
    [theme-mode="dark"] .ts-textarea { color: #ddd; }

    .ts-textarea::placeholder { color: #bbb; }
    [theme-mode="dark"] .ts-textarea::placeholder { color: #555; }

    .ts-column-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid #eee;
        margin-top: 12px;
        gap: 8px;
        flex-wrap: wrap;
    }
    [theme-mode="dark"] .ts-column-footer { border-color: #2a2a4a; }

    .ts-stat {
        font-size: 12px;
        color: #999;
        font-weight: 600;
    }

    .ts-btn-summarize {
        background: #4A00A0;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 32px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .ts-btn-summarize:hover {
        background: #3a0080;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(74, 0, 160, 0.3);
    }

    .ts-upload-btn {
        background: none;
        border: 1px dashed #ccc;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #777;
        cursor: pointer;
        transition: all 0.2s;
    }
    .ts-upload-btn:hover { border-color: #4A00A0; color: #4A00A0; }
    [theme-mode="dark"] .ts-upload-btn { border-color: #444; color: #888; }

    /* Action Buttons */
    .ts-action-btn {
        background: none;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 8px 12px;
        color: #666;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }
    .ts-action-btn:hover { border-color: #4A00A0; color: #4A00A0; }
    [theme-mode="dark"] .ts-action-btn { border-color: #444; color: #888; }

    /* COMPRESSION METER */
    .ts-compression-meter {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 16px;
    }
    .ts-meter-svg { width: 140px; height: 80px; }
    .ts-meter-pct {
        font-size: 28px;
        font-weight: 800;
        color: #4A00A0;
        margin-top: -8px;
    }
    .ts-meter-label {
        font-size: 12px;
        color: #999;
        font-weight: 600;
    }
    [theme-mode="dark"] .ts-meter-pct { color: #b388ff; }

    /* READING TIME SAVER */
    .ts-time-saver {
        display: flex;
        align-items: center;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
        border: 2px solid #e8e0f0;
    }
    [theme-mode="dark"] .ts-time-saver { border-color: #2a2a4a; }

    .ts-time-half {
        flex: 1;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 14px;
    }
    .ts-time-original {
        background: rgba(220, 53, 69, 0.08);
        color: #dc3545;
    }
    .ts-time-summary {
        background: rgba(25, 135, 84, 0.08);
        color: #198754;
    }
    .ts-time-badge {
        background: #4A00A0;
        color: white;
        padding: 8px 20px;
        font-weight: 800;
        font-size: 14px;
        white-space: nowrap;
    }

    /* KEY INSIGHTS */
    .ts-insights {
        border: 2px solid #e8e0f0;
        border-radius: 16px;
        margin-bottom: 24px;
        overflow: hidden;
    }
    [theme-mode="dark"] .ts-insights { border-color: #2a2a4a; }

    .ts-insights-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        cursor: pointer;
        font-weight: 700;
        font-size: 15px;
        color: #333;
        background: #f8f6fc;
    }
    [theme-mode="dark"] .ts-insights-header { background: #1a1a2e; color: #ddd; }
    .ts-insights-header:hover { background: #f0ecf6; }
    [theme-mode="dark"] .ts-insights-header:hover { background: #222244; }

    .ts-insights-body {
        padding: 20px;
        display: none;
    }
    .ts-insights-body.open { display: block; }

    .ts-insights-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    @media (max-width: 768px) {
        .ts-insights-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .ts-insight-card {
        background: #f8f6fc;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
    }
    [theme-mode="dark"] .ts-insight-card { background: #1a1a2e; }

    .ts-insight-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }
    .ts-insight-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #4A00A0;
        margin-bottom: 6px;
    }
    .ts-insight-value {
        font-size: 13px;
        color: #555;
        line-height: 1.5;
    }
    [theme-mode="dark"] .ts-insight-value { color: #aaa; }

    /* EMPTY STATE / PLACEHOLDER */
    .ts-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px 20px;
        flex: 1;
    }
    .ts-empty-icon {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: #f3eef8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    [theme-mode="dark"] .ts-empty-icon { background: #1e1e3a; }
    .ts-empty-icon i {
        font-size: 28px;
        color: #4A00A0;
        animation: ts-empty-pulse 2s ease-in-out infinite;
    }
    [theme-mode="dark"] .ts-empty-icon i { color: #b388ff; }
    @keyframes ts-empty-pulse {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.08); }
    }
    .ts-empty-title {
        font-size: 15px; font-weight: 700; color: #444; margin-bottom: 6px;
    }
    [theme-mode="dark"] .ts-empty-title { color: #ccc; }
    .ts-empty-sub {
        font-size: 13px; color: #999; line-height: 1.5; max-width: 240px;
    }

    /* HOW TO USE SECTION */
    .ts-howto {
        margin-top: 20px;
        margin-bottom: 24px;
    }
    .ts-howto-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    [theme-mode="dark"] .ts-howto-title { color: #f0f0f0; }
    .ts-howto-title i { color: #4A00A0; font-size: 20px; }
    [theme-mode="dark"] .ts-howto-title i { color: #b388ff; }

    .ts-howto-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 768px) {
        .ts-howto-steps { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .ts-howto-steps { grid-template-columns: 1fr; }
    }

    .ts-howto-step {
        background: white;
        border: 2px solid #e8e0f0;
        border-radius: 16px;
        padding: 24px 16px;
        text-align: center;
        position: relative;
        transition: all 0.25s;
    }
    .ts-howto-step:hover {
        border-color: #4A00A0;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(74, 0, 160, 0.1);
    }
    [theme-mode="dark"] .ts-howto-step {
        background: #111;
        border-color: #2a2a4a;
    }
    [theme-mode="dark"] .ts-howto-step:hover {
        border-color: #7c3aed;
        box-shadow: 0 8px 24px rgba(124, 58, 237, 0.15);
    }

    .ts-howto-num {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #4A00A0;
        color: white;
        font-size: 14px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }
    .ts-howto-step-icon {
        font-size: 28px;
        color: #4A00A0;
        margin-bottom: 10px;
    }
    [theme-mode="dark"] .ts-howto-step-icon { color: #b388ff; }
    .ts-howto-step-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 6px;
    }
    [theme-mode="dark"] .ts-howto-step-title { color: #eee; }
    .ts-howto-step-desc {
        font-size: 12px;
        color: #888;
        line-height: 1.5;
    }


</style>

<div class="ts-page">

    {{-- ═══════════════════════════════════════════════
         SECTION 1: HEADER
         ═══════════════════════════════════════════════ --}}
    <div class="humanizer-page-header">
        <div class="header-left">
            <a href="{{ route('front.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-md-inline">@lang('Back')</span>
            </a>
            <div class="header-divider d-none d-md-block"></div>
            <div class="title-group">
                <h1 class="tool-title">{{ $tool->name }}</h1>
                <div class="tool-description-mini d-none d-lg-block">
                    @lang('Transform long texts into concise summaries with AI.')
                </div>
            </div>
        </div>
        <div class="header-right">
            <button class="btn-history" type="button" id="tsHistoryBtn">
                <i class="bi bi-clock-history"></i>
                <span class="d-none d-md-inline">@lang('Recent Result')</span>
            </button>
        </div>
    </div>

    {{-- Horizontal Ad After Header --}}
    <div class="mb-4 text-center">
        <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    </div>

    {{-- ═══════════════════════════════════════════════
         SECTION 2: USAGE SHOWCASE
         ═══════════════════════════════════════════════ --}}
    <div class="usage-showcase-premium-v2">
        <div class="usage-stats-grid">
            {{-- Circular Progress --}}
            <div class="usage-circle-container">
                @php
                    $usagePercent = $limit > 0 ? (min($used, $limit) / $limit) * 100 : 0;
                    $circumference = 2 * 3.14159 * 45;
                    $dashOffset = $circumference - ($circumference * $usagePercent / 100);
                @endphp
                <svg class="usage-circle" width="120" height="120" viewBox="0 0 100 100">
                    <circle class="usage-circle-bg" cx="50" cy="50" r="45" />
                    <circle class="usage-circle-progress" cx="50" cy="50" r="45" 
                            style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $dashOffset }};" />
                </svg>
                <div class="usage-circle-text">
                    <div class="usage-number">{{ min($used, $limit) }}</div>
                    <div class="usage-total">/{{ $limit }}</div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="usage-info-cards">
                <div class="usage-info-card">
                    <div class="usage-info-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div class="usage-info-content">
                        <div class="usage-info-label">@lang('Uses Remaining')</div>
                        <div class="usage-info-value">{{ max(0, $limit - $used) }}</div>
                    </div>
                </div>

                <div class="usage-info-card">
                    <div class="usage-info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="usage-info-content">
                        <div class="usage-info-label">@lang('Resets In')</div>
                        <div class="usage-info-value" id="ts-countdown">--:--:--</div>
                    </div>
                </div>

                <div class="usage-info-card highlight">
                    <div class="usage-info-icon">
                        <i class="bi {{ $planIcon }}"></i>
                    </div>
                    <div class="usage-info-content">
                        <div class="usage-info-label">@lang('Current Plan')</div>
                        <div class="usage-info-value">{{ ucfirst($plan) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ad Slot for Free/Classic --}}
    @if(in_array($plan, ['free', 'classic']))
        <div class="mb-4" style="min-height: 100px;">
            <x-ad-slot :advertisement="get_advert_model('above-tool')" />
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════
         SECTION 3: CONTROLS BAR
         ═══════════════════════════════════════════════ --}}
    <form action="{{ route('tool.handle', ['tool' => 'text-summarizer']) }}" method="POST" id="tsSummarizerForm" enctype="multipart/form-data">
        @csrf

        <div class="ts-controls">
            <div class="ts-control-group">
                <div class="ts-control-label">@lang('Summary Length')</div>
                <div class="ts-slider-wrap">
                    <input type="range" name="length" class="ts-slider" id="tsLengthSlider"
                           min="25" max="90" step="1"
                           value="{{ $results['length_option'] ?? 50 }}">
                    <span class="ts-slider-label" id="tsSliderLabel">Medium (50%)</span>
                </div>
            </div>
            <div class="ts-control-group" style="min-width: auto;">
                <div class="ts-control-label">@lang('Format')</div>
                <div class="ts-format-pills">
                    <button type="button" class="ts-format-pill {{ ($results['format_option'] ?? 'paragraph') === 'paragraph' ? 'active' : '' }}" data-format="paragraph" title="Paragraph">
                        <i class="bi bi-text-paragraph"></i>
                    </button>
                    <button type="button" class="ts-format-pill {{ ($results['format_option'] ?? '') === 'bullets' ? 'active' : '' }}" data-format="bullets" title="Bullet Points">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="ts-format-pill {{ ($results['format_option'] ?? '') === 'brief' ? 'active' : '' }}" data-format="brief" title="Executive Brief">
                        <i class="bi bi-file-text"></i>
                    </button>
                </div>
                <input type="hidden" name="format" id="tsFormatInput" value="{{ $results['format_option'] ?? 'paragraph' }}">
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             SECTION 4: TWO-COLUMN LAYOUT
             ═══════════════════════════════════════════════ --}}
        <div class="ts-columns">
            {{-- INPUT COLUMN --}}
            <div class="ts-column">
                <div class="ts-column-label">
                    <i class="bi bi-pencil-square"></i> @lang('Input Text')
                </div>
                <textarea class="ts-textarea" name="string" id="tsInputText" rows="15"
                          placeholder="@lang('Paste the text you want to summarize here...')" required>{{ $results['original_text'] ?? old('string') }}</textarea>
                <div class="ts-column-footer">
                    <div>
                        <span class="ts-stat" id="tsCharCount">0 @lang('chars')</span>
                        <span class="ts-stat ms-2" id="tsWordCount">0 @lang('words')</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <label for="tsFileUpload" class="ts-upload-btn mb-0">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i> @lang('Upload')
                        </label>
                        <input type="file" name="file" id="tsFileUpload" accept=".pdf,.txt" class="d-none">
                        <button type="submit" class="ts-btn-summarize {{ $isLimitReached ? 'btn-ash-ts' : '' }}"
                                id="tsSubmitBtn" {{ $isLimitReached ? 'disabled' : '' }}>
                            <i class="bi bi-stars"></i>
                            @lang('Summarize')
                        </button>
                    </div>
                </div>
            </div>

            {{-- OUTPUT COLUMN --}}
            <div class="ts-column">
                <div class="ts-column-label">
                    <i class="bi bi-journal-text"></i> @lang('Summary Result')
                </div>

                @if(isset($results))
                    {{-- Compression Meter --}}
                    <div class="ts-compression-meter">
                        <svg class="ts-meter-svg" viewBox="0 0 140 80">
                            <path d="M 10 75 A 60 60 0 0 1 130 75" fill="none" stroke="#eee" stroke-width="10" stroke-linecap="round"/>
                            @php
                                $pct = $results['compression_pct'] ?? 0;
                                $angle = ($pct / 100) * 180;
                                $rad = deg2rad(180 - $angle);
                                $cx = 70; $cy = 75; $r = 60;
                                $endX = $cx + $r * cos($rad);
                                $endY = $cy - $r * sin($rad);
                                $largeArc = $angle > 180 ? 1 : 0;
                                $strokeColor = $pct < 30 ? '#dc3545' : ($pct < 60 ? '#ffc107' : ($pct < 86 ? '#198754' : '#4A00A0'));
                            @endphp
                            <path d="M 10 75 A 60 60 0 {{ $largeArc }} 1 {{ $endX }} {{ $endY }}"
                                  fill="none" stroke="{{ $strokeColor }}" stroke-width="10" stroke-linecap="round"
                                  class="ts-meter-arc"/>
                        </svg>
                        <div class="ts-meter-pct">{{ $pct }}%</div>
                        <div class="ts-meter-label">{{ $results['original_words'] }} → {{ $results['summary_words'] }} @lang('words')</div>
                    </div>

                    <textarea class="ts-textarea" id="tsSummaryResult" rows="12" readonly style="min-height: 200px;">{{ $results['summary_text'] }}</textarea>

                    <div class="ts-column-footer">
                        <span class="ts-stat">{{ $results['summary_words'] }} @lang('words')</span>
                        <div class="d-flex gap-2">
                            <button type="button" class="ts-action-btn" title="@lang('Copy')" onclick="navigator.clipboard.writeText(document.getElementById('tsSummaryResult').value); this.innerHTML='<i class=\'bi bi-check2\'></i>';">
                                <i class="bi bi-clipboard"></i>
                            </button>
                            <button type="button" class="ts-action-btn" title="@lang('Download TXT')" onclick="tsDownloadTxt()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button type="button" class="ts-action-btn" title="@lang('Print')" onclick="window.print()">
                                <i class="bi bi-printer"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="ts-empty-state">
                        <div class="ts-empty-icon">
                            <i class="bi bi-body-text"></i>
                        </div>
                        <div class="ts-empty-title">@lang('Summary appears here')</div>
                        <div class="ts-empty-sub">@lang('Paste your text, choose settings, and hit Summarize to get started.')</div>
                    </div>
                @endif
            </div>
        </div>

    </form>

    {{-- ═══════════════════════════════════════════════
         SECTION 5: READING TIME SAVER (shown after results)
         ═══════════════════════════════════════════════ --}}
    @if(isset($results))
        <div class="ts-time-saver">
            <div class="ts-time-half ts-time-original">
                <i class="bi bi-book" style="font-size: 20px;"></i>
                @lang('Original'): {{ $results['original_read_min'] }} min
            </div>
            <div class="ts-time-badge">
                {{ $results['time_saved_pct'] }}% @lang('FASTER')
            </div>
            <div class="ts-time-half ts-time-summary">
                <i class="bi bi-lightning-charge" style="font-size: 20px;"></i>
                @lang('Summary'): {{ $results['summary_read_min'] }} min
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             SECTION 6: KEY INSIGHTS
             ═══════════════════════════════════════════════ --}}
        <div class="ts-insights">
            <div class="ts-insights-header" onclick="document.getElementById('tsInsightsBody').classList.toggle('open'); this.querySelector('.bi').classList.toggle('bi-chevron-down'); this.querySelector('.bi').classList.toggle('bi-chevron-up');">
                <span><i class="bi bi-search me-2"></i> @lang('Key Insights')</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="ts-insights-body" id="tsInsightsBody">
                <div class="ts-insights-grid">
                    <div class="ts-insight-card">
                        <div class="ts-insight-icon"><i class="bi bi-book"></i></div>
                        <div class="ts-insight-title">@lang('Source')</div>
                        <div class="ts-insight-value">{{ $results['original_words'] }} @lang('words')</div>
                    </div>
                    <div class="ts-insight-card">
                        <div class="ts-insight-icon"><i class="bi bi-bar-chart"></i></div>
                        <div class="ts-insight-title">@lang('Compression')</div>
                        <div class="ts-insight-value">{{ $results['compression_pct'] }}% @lang('reduced')</div>
                    </div>
                    <div class="ts-insight-card">
                        <div class="ts-insight-icon"><i class="bi bi-clock"></i></div>
                        <div class="ts-insight-title">@lang('Time Saved')</div>
                        <div class="ts-insight-value">{{ max(0, $results['original_read_min'] - $results['summary_read_min']) }} @lang('minutes')</div>
                    </div>
                    <div class="ts-insight-card">
                        <div class="ts-insight-icon"><i class="bi bi-pencil-square"></i></div>
                        <div class="ts-insight-title">@lang('Format')</div>
                        <div class="ts-insight-value">{{ ucfirst($results['format_option']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-3" style="border-radius: 12px;">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════
         HOW TO USE SECTION
         ═══════════════════════════════════════════════ --}}
    <div class="mt-5 pt-4">
        <x-related-tools :tool="$tool" />
    </div>

    <div class="mt-5 pt-5 border-top tool-content-standalone">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-4">@lang('How this Text Summarizer works')</h2>
            <p class="text-muted lead">@lang('Condense your content in 4 simple steps')</p>
        </div>

        <div class="ts-howto-steps">
            <div class="ts-howto-step">
                <span class="ts-howto-num">1</span>
                <i class="bi bi-clipboard-plus ts-howto-step-icon"></i>
                <h4 class="ts-howto-step-title">@lang('Input Text')</h4>
                <p class="ts-howto-step-desc">@lang('Paste your long text or upload a document into the source editor.')</p>
            </div>
            <div class="ts-howto-step">
                <span class="ts-howto-num">2</span>
                <i class="bi bi-sliders ts-howto-step-icon"></i>
                <h4 class="ts-howto-step-title">@lang('Set Length')</h4>
                <p class="ts-howto-step-desc">@lang('Use the dynamic slider to choose how short or detailed your summary should be.')</p>
            </div>
            <div class="ts-howto-step">
                <span class="ts-howto-num">3</span>
                <i class="bi bi-stars ts-howto-step-icon"></i>
                <h4 class="ts-howto-step-title">@lang('Summarize')</h4>
                <p class="ts-howto-step-desc">@lang('Our AI analyzes the key points and creates a concise version while keeping the core meaning.')</p>
            </div>
            <div class="ts-howto-step">
                <span class="ts-howto-num">4</span>
                <i class="bi bi-download ts-howto-step-icon"></i>
                <h4 class="ts-howto-step-title">@lang('Export')</h4>
                <p class="ts-howto-step-desc">@lang('Copy your summary or download it as a text file for immediate use.')</p>
            </div>
        </div>

        <div class="mt-5 p-4 bg-light rounded-4 border" id="howToUseContent">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>@lang('How to Use Text Summarizer')</h5>
            <div class="how-to-use-list text-muted" style="font-size: 14px; line-height: 1.9;">
                {!! strip_tags($tool->content, '<p><a><strong><ul><ol><li>') !!}
            </div>
        </div>
    </div>

</div>



{{-- Guest Login Modal --}}
@if(auth()->guest())
<div id="ts-guest-overlay" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:10010;display:none;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;padding:32px;max-width:380px;text-align:center;border:2px solid #e8e0f0;">
        <i class="bi bi-lock-fill" style="font-size:48px;color:#4A00A0;"></i>
        <h3 class="fw-bold mt-3">@lang('Login Required')</h3>
        <p class="text-muted">@lang('Please log in to use the Text Summarizer.')</p>
        <a href="{{ route('login') }}" class="btn w-100 py-3 fw-bold" style="background:#4A00A0;color:white;border-radius:12px;">@lang('Log In')</a>
        <button class="btn btn-link mt-2" onclick="document.getElementById('ts-guest-overlay').style.display='none';">@lang('Cancel')</button>
    </div>
</div>
@endif

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form       = document.getElementById('tsSummarizerForm');
    const inputArea  = document.getElementById('tsInputText');
    const slider     = document.getElementById('tsLengthSlider');
    const sliderLabel= document.getElementById('tsSliderLabel');
    const formatPills= document.querySelectorAll('.ts-format-pill');
    const formatInput= document.getElementById('tsFormatInput');
    const submitBtn  = document.getElementById('tsSubmitBtn');
    const charCount  = document.getElementById('tsCharCount');
    const wordCount  = document.getElementById('tsWordCount');
    const fileUpload = document.getElementById('tsFileUpload');

    // ─── Length Slider ───
    function updateSliderLabel() {
        const val = parseInt(slider.value);
        let label = 'Medium (50%)';
        if (val <= 30) label = 'Very Short (' + val + '%)';
        else if (val <= 55) label = 'Medium (' + val + '%)';
        else if (val <= 80) label = 'Detailed (' + val + '%)';
        else label = 'Comprehensive (' + val + '%)';
        sliderLabel.textContent = label;
    }
    slider.addEventListener('input', updateSliderLabel);
    updateSliderLabel();

    // ─── Format Pills ───
    formatPills.forEach(pill => {
        pill.addEventListener('click', function() {
            formatPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            formatInput.value = this.dataset.format;
        });
    });

    // ─── Character/Word Count ───
    function updateCounts() {
        const text = inputArea.value;
        charCount.textContent = text.length.toLocaleString() + ' chars';
        const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
        wordCount.textContent = words.toLocaleString() + ' words';
    }
    inputArea.addEventListener('input', updateCounts);
    updateCounts();

    // ─── File Upload ───
    fileUpload.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (file.type === 'text/plain') {
            const reader = new FileReader();
            reader.onload = e => { inputArea.value = e.target.result; updateCounts(); };
            reader.readAsText(file, 'UTF-8');
        }
    });

    // ─── Form Submit ───
    form.addEventListener('submit', function(e) {
        if (submitBtn.classList.contains('btn-ash-ts')) {
            e.preventDefault();
            showUpgradePopup();
            return;
        }

        @if(auth()->guest())
            e.preventDefault();
            document.getElementById('ts-guest-overlay').style.display = 'flex';
            return;
        @endif

        @if($isLimitReached)
            e.preventDefault();
            showUpgradePopup();
            return;
        @endif
        
        // Standard app loader will trigger automatically since data-no-loader is removed
    });

    // ─── Ash Button Click ───
    submitBtn.addEventListener('click', function(e) {
        if (this.classList.contains('btn-ash-ts')) {
            e.preventDefault();
            showUpgradePopup();
        }
    });

    function showUpgradePopup() {
        const overlay = document.getElementById('upgrade-popup-overlay');
        const pageContent = document.getElementById('page-content-area');
        if (overlay) {
            overlay.style.display = 'flex';
            if (pageContent) pageContent.classList.add('page-content-blur');
        }
    }

    // ─── Countdown Timer ───
    const resetAt = new Date('{{ $reset_at }}');
    const timerEl = document.getElementById('ts-countdown');
    function updateCountdown() {
        const now = new Date();
        const diff = resetAt - now;
        if (diff <= 0) {
            timerEl.textContent = '00:00:00';
            setTimeout(() => location.reload(), 1000);
            return;
        }
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        timerEl.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);
});

// Download as TXT
function tsDownloadTxt() {
    const text = document.getElementById('tsSummaryResult').value;
    const blob = new Blob([text], {type: 'text/plain'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'summary.txt';
    a.click();
}
</script>
@endpush

</x-application-tools-wrapper>