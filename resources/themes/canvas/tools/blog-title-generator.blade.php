<x-canvas-layout>
    @push('page_header')
        <link rel="stylesheet" href="{{ asset('css/blog-title-generator.css') }}">
    @endpush

    {{-- Guest Login Modal --}}
    <div id="btg-guest-overlay" class="upgrade-popup-overlay" style="display: none;">
        <div class="upgrade-popup-modal">
            <button id="btg-guest-close" class="upgrade-popup-close" aria-label="Close">&times;</button>
            <div class="upgrade-popup-content">
                <div class="upgrade-popup-icon" style="background: #4B0096;">
                    <i class="bi bi-person-lock" style="font-size: 2.5rem; color: white;"></i>
                </div>
                <h3 class="upgrade-popup-title">@lang('Login to use this tool')</h3>
                <p class="upgrade-popup-description text-center">
                    @lang('This is a premium AI tool. Please sign in to generate blog titles.')
                </p>
                <div class="d-grid mt-4">
                    <a href="{{ route('login') }}" class="upgrade-popup-button" style="background: #4B0096; border: none; box-shadow: none;">
                        @lang('Login Now')
                    </a>
                </div>
                <p class="upgrade-popup-footer mt-4">
                    @lang('Don\'t have an account?') <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">@lang('Join Sparktopus')</a>
                </p>
            </div>
        </div>
    </div>

    <style>
        .blurred {
            filter: blur(8px);
            pointer-events: none;
            user-select: none;
            transition: filter 0.3s ease;
        }
        #btg-guest-overlay {
            backdrop-filter: blur(2px);
        }

        /* ── Usage Showcase ── */
        .btg-usage-showcase {
            background: linear-gradient(135deg, rgba(75, 0, 150, 0.03) 0%, rgba(96, 0, 194, 0.08) 100%);
            border: 2px solid rgba(75, 0, 150, 0.15);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 8px 32px rgba(75, 0, 150, 0.06);
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        [theme-mode="dark"] .btg-usage-showcase {
            background: linear-gradient(135deg, rgba(75, 0, 150, 0.08) 0%, rgba(96, 0, 194, 0.12) 100%);
            border-color: rgba(75, 0, 150, 0.25);
        }

        .btg-usage-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 32px;
            align-items: center;
        }

        @media (max-width: 768px) {
            .btg-usage-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .btg-circle-wrap { margin: 0 auto; }
        }

        .btg-circle-wrap {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .btg-circle-svg { transform: rotate(-90deg); }
        .btg-circle-bg { fill: none; stroke: rgba(75, 0, 150, 0.1); stroke-width: 8; }
        .btg-circle-fill { fill: none; stroke: #4B0096; stroke-width: 8; stroke-linecap: round; transition: stroke-dashoffset 1s ease; }

        .btg-circle-text {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        .btg-circle-num { font-size: 32px; font-weight: 800; color: #4B0096; line-height: 1; }
        .btg-circle-total { font-size: 16px; font-weight: 600; color: #999; }

        .btg-info-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        @media (max-width: 768px) {
            .btg-info-cards { grid-template-columns: 1fr; }
        }

        .btg-info-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }
        .btg-info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }
        .btg-info-card.highlight {
            background: linear-gradient(135deg, #4B0096 0%, #6000C2 100%);
            border-color: #4B0096;
        }
        .btg-info-card.highlight .btg-info-label,
        .btg-info-card.highlight .btg-info-value { color: white !important; }
        .btg-info-card.highlight .btg-info-icon-wrap { background: rgba(255,255,255,0.2); color: white; }

        [theme-mode="dark"] .btg-info-card {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
        }

        .btg-info-icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: rgba(75, 0, 150, 0.1);
            color: #4B0096;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .btg-info-content { flex: 1; }
        .btg-info-label {
            font-size: 13px; font-weight: 600; color: #666;
            margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .btg-info-value { font-size: 24px; font-weight: 800; color: #26282D; }

        [theme-mode="dark"] .btg-info-label { color: #aaa; }
        [theme-mode="dark"] .btg-info-value { color: #f8f9fa; }

        #btg-countdown { font-family: 'Courier New', monospace; }

        /* Horizontal Ad Slot */
        .btg-ad-slot {
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 12px;
            overflow: hidden;
            background: var(--bg-gray);
            border: 1px dashed var(--border-color);
            min-height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="container-fluid py-4 px-lg-5" id="btgMainContainer">
        {{-- Header --}}
        <div class="btg-page-header d-flex align-items-center justify-content-between flex-nowrap">
            <div class="header-left d-flex align-items-center gap-3">
                <a href="{{ route('front.index') }}" class="back-link flex-shrink-0">
                    <i class="bi bi-arrow-left"></i>
                    <span class="d-none d-md-inline">@lang('Back')</span>
                </a>
                <div class="header-divider d-none d-md-block"></div>
                <div class="title-group">
                    <h1 class="tool-title mb-0 h4 fw-bold">{{ $tool->name }}</h1>
                    <div class="tool-description-mini small text-muted d-none d-lg-block">
                        @lang('Generate catchy, SEO-friendly blog titles instantly.')
                    </div>
                </div>
            </div>
        </div>

        {{-- Horizontal Ad After Header --}}
        <div class="mb-4 text-center">
            <x-ad-slot :advertisement="get_advert_model('above-tool')" />
        </div>

        {{-- Usage Showcase --}}
        @php
            $usagePercent = $limit > 0 ? (min($used, $limit) / $limit) * 100 : 0;
            $circumference = 2 * 3.14159 * 45;
            $dashOffset = $circumference - ($circumference * $usagePercent / 100);
        @endphp
        <div class="btg-usage-showcase">
            <div class="btg-usage-grid">
                <div class="btg-circle-wrap">
                    <svg class="btg-circle-svg" width="120" height="120" viewBox="0 0 100 100">
                        <circle class="btg-circle-bg" cx="50" cy="50" r="45" />
                        <circle class="btg-circle-fill" cx="50" cy="50" r="45"
                                style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $dashOffset }};" />
                    </svg>
                    <div class="btg-circle-text">
                        <div class="btg-circle-num">{{ min($used, $limit) }}</div>
                        <div class="btg-circle-total">/{{ $limit }}</div>
                    </div>
                </div>

                <div class="btg-info-cards">
                    <div class="btg-info-card">
                        <div class="btg-info-icon-wrap">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div class="btg-info-content">
                            <div class="btg-info-label">@lang('Uses Remaining')</div>
                            <div class="btg-info-value">{{ max(0, $limit - $used) }}</div>
                        </div>
                    </div>

                    <div class="btg-info-card">
                        <div class="btg-info-icon-wrap">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="btg-info-content">
                            <div class="btg-info-label">@lang('Resets In')</div>
                            <div class="btg-info-value" id="btg-countdown">--:--:--</div>
                        </div>
                    </div>

                    <div class="btg-info-card highlight">
                        <div class="btg-info-icon-wrap">
                            @if($plan === 'free')
                                <i class="bi bi-star-fill"></i>
                            @elseif($plan === 'classic')
                                <i class="bi bi-shield-fill"></i>
                            @elseif($plan === 'plus')
                                <i class="bi bi-rocket-fill"></i>
                            @else
                                <i class="bi bi-gem"></i>
                            @endif
                        </div>
                        <div class="btg-info-content">
                            <div class="btg-info-label">@lang('Current Plan')</div>
                            <div class="btg-info-value">{{ ucfirst($plan) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function() {
                const resetAt = new Date('{{ $reset_at }}');
                const timerEl = document.getElementById('btg-countdown');
                function tick() {
                    const diff = resetAt - new Date();
                    if (diff <= 0) { timerEl.textContent = '00:00:00'; setTimeout(() => location.reload(), 1000); return; }
                    const h = Math.floor(diff / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    timerEl.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
                }
                tick(); setInterval(tick, 1000);
            })();
        </script>

        {{-- Ad Slot --}}
        @if(in_array($plan, ['free', 'classic']))
            <div class="btg-ad-slot mt-4 mb-5">
                <x-ad-slot :advertisement="get_advert_model('above-tool')" />
            </div>
        @endif

        {{-- Main Content --}}
        <div class="btg-layout">
            <div class="btg-main">
                <form action="{{ route('tool.handle', ['tool' => 'blog-title-generator']) }}" method="POST" id="btgForm">
                    @csrf
                    <input type="hidden" name="tone" id="btgToneInput" value="{{ $results['tone'] ?? 'Catchy' }}">
                    <input type="hidden" name="audience" id="btgAudienceInput" value="{{ $results['audience'] ?? 'General' }}">

                    {{-- Input Card --}}
                    <div class="btg-input-card">
                        <div class="btg-input-head">
                            <div class="btg-input-label">
                                <i class="bi bi-pencil-square"></i>
                                <span>@lang('Describe Your Topic')</span>
                            </div>
                            <div class="btg-input-actions">
                                <button type="button" class="btn-icon" id="btgClearText" title="@lang('Clear')">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>
                            </div>
                        </div>
                        <div class="btg-input-body">
                            <textarea name="string" id="btgTextarea" placeholder="@lang('Describe your blog topic, main idea, or keywords...\n\nExample: How to start a profitable online store in 2025 with no experience')">{{ $results['original_text'] ?? old('string') }}</textarea>
                        </div>
                        <div class="btg-input-foot">
                            <div class="btg-counter-group">
                                <span>@lang('Words'): <b id="btgWordCount">0</b></span>
                                <span>@lang('Chars'): <b id="btgCharCount">0</b></span>
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('string')" class="mt-2 mb-3" />

                    {{-- Settings Panel --}}
                    <div class="btg-settings expanded" id="btgSettingsPanel">
                        <div class="btg-settings-header" id="btgSettingsToggle">
                            <div class="header-title">
                                <i class="bi bi-sliders"></i>
                                <span>@lang('Title Style')</span>
                            </div>
                            <i class="bi bi-chevron-down chevron"></i>
                        </div>
                        <div class="btg-settings-body">
                            {{-- Tone --}}
                            <div class="btg-setting-group">
                                <label>@lang('Tone')</label>
                                <div class="btg-pills" data-target="tone">
                                    <div class="btg-pill {{ ($results['tone'] ?? 'Catchy') === 'Catchy' ? 'active' : '' }}" data-value="Catchy">
                                        <i class="bi bi-fire"></i> @lang('Catchy')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'Clickbait' ? 'active' : '' }}" data-value="Clickbait">
                                        <i class="bi bi-megaphone"></i> @lang('Clickbait')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'Crazy Friendly' ? 'active' : '' }}" data-value="Crazy Friendly">
                                        <i class="bi bi-emoji-smile"></i> @lang('Crazy Friendly')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'SEO-Optimized' ? 'active' : '' }}" data-value="SEO-Optimized">
                                        <i class="bi bi-graph-up-arrow"></i> @lang('SEO-Optimized')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'Professional' ? 'active' : '' }}" data-value="Professional">
                                        <i class="bi bi-briefcase"></i> @lang('Professional')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'Question-Based' ? 'active' : '' }}" data-value="Question-Based">
                                        <i class="bi bi-question-circle"></i> @lang('Question-Based')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'How-To' ? 'active' : '' }}" data-value="How-To">
                                        <i class="bi bi-journal-check"></i> @lang('How-To')
                                    </div>
                                    <div class="btg-pill {{ ($results['tone'] ?? '') === 'Listicle' ? 'active' : '' }}" data-value="Listicle">
                                        <i class="bi bi-list-ol"></i> @lang('Listicle')
                                    </div>
                                </div>
                            </div>

                            {{-- Audience --}}
                            <div class="btg-setting-group">
                                <label>@lang('Target Audience')</label>
                                <div class="btg-pills" data-target="audience">
                                    <div class="btg-pill {{ ($results['audience'] ?? 'General') === 'General' ? 'active' : '' }}" data-value="General">
                                        <i class="bi bi-people"></i> @lang('General')
                                    </div>
                                    <div class="btg-pill {{ ($results['audience'] ?? '') === 'Marketers' ? 'active' : '' }}" data-value="Marketers">
                                        <i class="bi bi-megaphone"></i> @lang('Marketers')
                                    </div>
                                    <div class="btg-pill {{ ($results['audience'] ?? '') === 'Developers' ? 'active' : '' }}" data-value="Developers">
                                        <i class="bi bi-code-slash"></i> @lang('Developers')
                                    </div>
                                    <div class="btg-pill {{ ($results['audience'] ?? '') === 'Students' ? 'active' : '' }}" data-value="Students">
                                        <i class="bi bi-mortarboard"></i> @lang('Students')
                                    </div>
                                    <div class="btg-pill {{ ($results['audience'] ?? '') === 'Entrepreneurs' ? 'active' : '' }}" data-value="Entrepreneurs">
                                        <i class="bi bi-rocket-takeoff"></i> @lang('Entrepreneurs')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="btg-submit-wrap">
                        <button type="submit" class="btg-submit-btn {{ $isLimitReached ? 'btn-ash-btg' : '' }}" id="btgSubmitBtn">
                            <i class="bi bi-lightbulb-fill"></i>
                            <span>@lang('Generate Titles')</span>
                        </button>
                    </div>
                </form>

                {{-- Results --}}
                @if(isset($results) && !empty($results['titles']))
                    <div class="btg-results-section">
                        <div class="btg-results-header">
                            <div class="btg-results-title">
                                <i class="bi bi-stars"></i>
                                <span>@lang('Generated Titles')</span>
                            </div>
                            <div class="btg-results-count">{{ count($results['titles']) }} @lang('titles')</div>
                        </div>
                        <div class="btg-titles-grid">
                            @foreach($results['titles'] as $i => $title)
                                <div class="btg-title-card">
                                    <div class="btg-title-number">{{ $i + 1 }}</div>
                                    <div class="btg-title-text">{{ $title }}</div>
                                    <div class="btg-title-actions">
                                        <button type="button" class="btn-action btg-copy-btn" data-text="{{ $title }}" title="@lang('Copy')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif(!isset($results))
                    <div class="btg-empty-state">
                        <div class="btg-empty-icon">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <div class="btg-empty-title">@lang('Your titles will appear here')</div>
                        <p class="btg-empty-desc">@lang('Describe your blog topic above, pick a tone and audience, then hit Generate.')</p>
                    </div>
                @endif

                {{-- Related Tools --}}
                <div class="mt-5 pt-4">
                    <x-related-tools :tool="$tool" />
                </div>

                {{-- How It Works --}}
                <div class="btg-howto mt-5 pt-5 border-top">
                    <div class="btg-howto-heading">
                        <h2>@lang('How the Blog Title Generator Works')</h2>
                        <p>@lang('Create high-performing blog titles in 4 easy steps')</p>
                    </div>
                    <div class="btg-howto-steps">
                        <div class="btg-step-card">
                            <span class="btg-step-number">1</span>
                            <i class="bi bi-pencil-square btg-step-icon"></i>
                            <h4 class="btg-step-title">@lang('Describe Topic')</h4>
                            <p class="btg-step-desc">@lang('Enter your blog topic, main idea, or target keywords.')</p>
                        </div>
                        <div class="btg-step-card">
                            <span class="btg-step-number">2</span>
                            <i class="bi bi-sliders btg-step-icon"></i>
                            <h4 class="btg-step-title">@lang('Pick a Style')</h4>
                            <p class="btg-step-desc">@lang('Choose your preferred tone and target audience for tailored titles.')</p>
                        </div>
                        <div class="btg-step-card">
                            <span class="btg-step-number">3</span>
                            <i class="bi bi-lightning-charge btg-step-icon"></i>
                            <h4 class="btg-step-title">@lang('Generate')</h4>
                            <p class="btg-step-desc">@lang('Click "Generate Titles" and our AI creates 10 unique, optimized titles.')</p>
                        </div>
                        <div class="btg-step-card">
                            <span class="btg-step-number">4</span>
                            <i class="bi bi-clipboard-check btg-step-icon"></i>
                            <h4 class="btg-step-title">@lang('Copy & Use')</h4>
                            <p class="btg-step-desc">@lang('Hover any title to copy it, then use it on your blog or social media.')</p>
                        </div>
                    </div>

                    <div class="mt-5 text-muted small p-4 bg-light rounded-4 border">
                        <i class="bi bi-info-circle me-2"></i>
                        {!! strip_tags($tool->content, '<p><a><strong>') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const textarea = document.getElementById('btgTextarea');
                const wordCount = document.getElementById('btgWordCount');
                const charCount = document.getElementById('btgCharCount');
                const form = document.getElementById('btgForm');
                const submitBtn = document.getElementById('btgSubmitBtn');
                const toneInput = document.getElementById('btgToneInput');
                const audienceInput = document.getElementById('btgAudienceInput');
                const clearBtn = document.getElementById('btgClearText');
                const settingsPanel = document.getElementById('btgSettingsPanel');
                const settingsToggle = document.getElementById('btgSettingsToggle');

                // ── Real-time Counter ──
                function updateCounters() {
                    const text = textarea.value;
                    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
                    wordCount.textContent = words.toLocaleString();
                    charCount.textContent = text.length.toLocaleString();
                }
                textarea.addEventListener('input', updateCounters);
                updateCounters();

                // ── Clear Button ──
                clearBtn.addEventListener('click', () => {
                    textarea.value = '';
                    updateCounters();
                    textarea.focus();
                });

                // ── Settings Toggle ──
                settingsToggle.addEventListener('click', () => {
                    settingsPanel.classList.toggle('expanded');
                });

                // ── Pill Selection ──
                document.querySelectorAll('.btg-pills').forEach(group => {
                    const target = group.dataset.target;
                    group.querySelectorAll('.btg-pill').forEach(pill => {
                        pill.addEventListener('click', () => {
                            group.querySelectorAll('.btg-pill').forEach(p => p.classList.remove('active'));
                            pill.classList.add('active');
                            if (target === 'tone') toneInput.value = pill.dataset.value;
                            if (target === 'audience') audienceInput.value = pill.dataset.value;
                        });
                    });
                });

                // ── Form Submit Guards ──
                form.addEventListener('submit', function(e) {
                    if (submitBtn.classList.contains('btn-ash-btg')) {
                        e.preventDefault();
                        showUpgradePopup();
                        return;
                    }

                    @if(auth()->guest())
                        e.preventDefault();
                        showGuestModal();
                        return;
                    @endif

                    @if($isLimitReached)
                        e.preventDefault();
                        showUpgradePopup();
                        return;
                    @endif

                    // Let the default app loader handle it
                });

                // Ash button click
                submitBtn.addEventListener('click', function(e) {
                    if (this.classList.contains('btn-ash-btg')) {
                        e.preventDefault();
                        showUpgradePopup();
                    }
                });

                // ── Copy Individual Title ──
                document.querySelectorAll('.btg-copy-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const text = this.dataset.text;
                        navigator.clipboard.writeText(text).then(() => {
                            const icon = this.querySelector('i');
                            icon.className = 'bi bi-check-lg';
                            this.classList.add('copied');
                            setTimeout(() => {
                                icon.className = 'bi bi-clipboard';
                                this.classList.remove('copied');
                            }, 2000);
                        });
                    });
                });

                // ── Helpers ──
                function showUpgradePopup() {
                    const overlay = document.getElementById('upgrade-popup-overlay');
                    const pageContent = document.getElementById('page-content-area');
                    if (overlay) {
                        overlay.style.display = 'flex';
                        if (pageContent) pageContent.classList.add('page-content-blur');
                    }
                }

                function showGuestModal() {
                    const overlay = document.getElementById('btg-guest-overlay');
                    const pageContent = document.getElementById('page-content-area');
                    if (overlay) overlay.style.display = 'flex';
                    if (pageContent) pageContent.classList.add('page-content-blur');
                }

                function hideGuestModal() {
                    const overlay = document.getElementById('btg-guest-overlay');
                    const pageContent = document.getElementById('page-content-area');
                    if (overlay) overlay.style.display = 'none';
                    if (pageContent) pageContent.classList.remove('page-content-blur');
                }

                const guestCloseBtn = document.getElementById('btg-guest-close');
                const guestOverlay = document.getElementById('btg-guest-overlay');
                if (guestCloseBtn) guestCloseBtn.addEventListener('click', hideGuestModal);
                if (guestOverlay) guestOverlay.addEventListener('click', (e) => {
                    if (e.target === guestOverlay) hideGuestModal();
                });
            });
        </script>
    @endpush
</x-canvas-layout>
