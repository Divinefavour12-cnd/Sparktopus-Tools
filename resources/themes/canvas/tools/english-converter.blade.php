<x-canvas-layout>
    @push('page_header')
        <link rel="stylesheet" href="{{ asset('css/ai-humanizer.css') }}">
        <style>
            /* Unique Usage Showcase Styles */
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

            .usage-stats-grid { display: grid; grid-template-columns: auto 1fr; gap: 32px; align-items: center; }
            @media (max-width: 768px) { .usage-stats-grid { grid-template-columns: 1fr; text-align: center; } .usage-circle-container { margin: 0 auto; } }

            .usage-circle-container { position: relative; width: 120px; height: 120px; flex-shrink: 0; }
            .usage-circle { transform: rotate(-90deg); width: 100%; height: 100%; }
            .usage-circle-bg { fill: none; stroke: rgba(96, 0, 194, 0.1); stroke-width: 8; }
            .usage-circle-progress { fill: none; stroke: #6000C2; stroke-width: 8; stroke-linecap: round; transition: stroke-dashoffset 1s ease; }
            .usage-circle-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; }
            .usage-number { font-size: 32px; font-weight: 800; color: #1a1a2e; line-height: 1; }
            [theme-mode="dark"] .usage-number { color: #fff; }
            .usage-total { font-size: 16px; font-weight: 600; color: #888; margin-top: 2px; }

            .usage-info-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
            @media (max-width: 768px) { .usage-info-cards { grid-template-columns: 1fr; } }
            .usage-info-card { background: #fff; border: 1px solid rgba(0,0,0,0.05); border-radius: 16px; padding: 18px; display: flex; align-items: center; gap: 15px; transition: all 0.3s ease; }
            [theme-mode="dark"] .usage-info-card { background: rgba(255, 255, 255, 0.03); border-color: rgba(255,255,255,0.08); }
            .usage-info-card.highlight { background: #6000C2; border-color: #6000C2; }
            .usage-info-card.highlight .usage-info-label, .usage-info-card.highlight .usage-info-value { color: #fff !important; }
            .usage-info-card.highlight .usage-info-icon { background: rgba(255,255,255,0.2); color: #fff; }
            .usage-info-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(96, 0, 194, 0.08); color: #6000C2; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
            .usage-info-content { flex: 1; }
            .usage-info-label { font-size: 11px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
            .usage-info-value { font-size: 20px; font-weight: 800; color: #1a1a2e; }
            [theme-mode="dark"] .usage-info-value { color: #eee; }

            /* Header */
            .ts-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding: 20px 0; max-width: 1000px; margin-left: auto; margin-right: auto; }
            .ts-header .header-left { display: flex; align-items: center; gap: 12px; }
            .ts-header .back-link { display: inline-flex; align-items: center; gap: 6px; color: #888; text-decoration: none; font-size: 13px; font-weight: 500; padding: 8px 14px; border-radius: 10px; border: 1px solid #eee; transition: all 0.2s; }
            .ts-header .back-link:hover { color: #6000C2; background: rgba(96, 0, 194, 0.04); border-color: #6000C2; }
            .ts-header .header-divider { width: 1px; height: 24px; background: #eee; margin: 0 4px; }
            .ts-header .tool-title { font-size: 24px; font-weight: 800; color: #1a1a2e; margin: 0; line-height: 1.2; }
            [theme-mode="dark"] .ts-header .tool-title { color: #fff; }
            .ts-header .tool-description-mini { color: #777; font-size: 13px; font-weight: 500; margin-top: 2px; }
            .history-btn { display: inline-flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #eee; padding: 10px 18px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #555; cursor: pointer; transition: all 0.2s; }
            [theme-mode="dark"] .history-btn { background: #1a1a2e; border-color: #333; color: #aaa; }
            .history-btn:hover { border-color: #6000C2; color: #6000C2; background: rgba(96,0,194,0.04); }

            /* Pills */
            .btg-pill { padding: 10px 18px; border-radius: 50px; border: 1.5px solid #eee; background: #fff; color: #555; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
            [theme-mode="dark"] .btg-pill { background: #1a1a2e; border-color: #333; color: #aaa; }
            .btg-pill:hover { border-color: #6000C2; color: #6000C2; background: rgba(96, 0, 194, 0.04); }
            .btg-pill.active { background: #6000C2; border-color: #6000C2; color: #fff; box-shadow: 0 4px 12px rgba(96,0,194,0.2); }

            /* How it works */
            .how-it-works-steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-top: 40px; }
            .step-card { background: #fff; padding: 30px; border-radius: 18px; border: 1px solid rgba(0,0,0,0.04); transition: all 0.3s ease; position: relative; text-align: center; }
            [theme-mode="dark"] .step-card { background: #1a1a2e; border-color: rgba(255,255,255,0.05); }
            .step-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.06); border-color: rgba(96, 0, 194, 0.1); }
            .step-number { position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 35px; height: 35px; background: #6000C2; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; box-shadow: 0 5px 15px rgba(96, 0, 194, 0.3); }
            .step-icon { font-size: 40px; color: #6000C2; margin-bottom: 20px; display: block; }
            .step-title { font-size: 18px; font-weight: 700; margin-bottom: 12px; color: #333; }
            [theme-mode="dark"] .step-title { color: #eee; }
            .step-desc { font-size: 14px; color: #777; line-height: 1.6; margin: 0; }
            [theme-mode="dark"] .step-desc { color: #999; }

            .ec-lang-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-top: 20px; }
            .ec-lang-pill { padding: 10px 14px; background: #fff; border: 1px solid #eee; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 600; text-align: center; transition: all 0.2s; color: #555; }
            .ec-lang-pill:hover { border-color: #6000C2; background: rgba(96, 0, 194, 0.04); color: #6000C2; }
            .ec-lang-pill.active { background: #6000C2; color: white; border-color: #6000C2; box-shadow: 0 4px 12px rgba(96, 0, 194, 0.2); }
            [theme-mode="dark"] .ec-lang-pill { background: #1a1a2e; border-color: #333; color: #aaa; }

            .ec-input-group { background: #fff; border-radius: 20px; border: 1px solid #eee; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
            [theme-mode="dark"] .ec-input-group { background: #1a1a2e; border-color: #333; }
            .ec-header-area { padding: 12px 20px; border-bottom: 1px solid #f5f5f5; background: #fafafa; }
            [theme-mode="dark"] .ec-header-area { background: #151525; border-color: #333; }
            .ec-textarea { width: 100%; min-height: 200px; border: none; padding: 20px; font-size: 16px; line-height: 1.6; outline: none; background: transparent; color: inherit; }
            
            .ec-btn-premium { background: #6000C2; color: white; padding: 14px 40px; border: none; border-radius: 50px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(96, 0, 194, 0.3); }
            .ec-btn-premium:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(96, 0, 194, 0.4); background: #4B0096; }
            .ec-btn-ash { background: #e0e0e0; color: #888; cursor: not-allowed; box-shadow: none; }
            [theme-mode="dark"] .ec-btn-ash { background: #333; color: #666; }

            .ec-result-card { background: #fff; border-radius: 20px; border: 1px solid #eee; padding: 24px; margin-top: 30px; position: relative; }
            [theme-mode="dark"] .ec-result-card { background: #1a1a2e; border-color: #333; }
        </style>
    @endpush

    @php
        $user = auth()->user();
        $usagePercent = $limit > 0 ? (min($used, $limit) / $limit) * 100 : 0;
        $circumference = 2 * 3.14159 * 45;
        $dashOffset = $circumference - ($circumference * $usagePercent / 100);
        $languages = [
            'Nigerian Pidgin', 'US English', 'UK English', 'Australian English', 'Canadian English',
            'French', 'Spanish', 'German', 'Italian', 'Portuguese', 'Arabic', 'Hindi',
            'Chinese (Mandarin)', 'Japanese', 'Korean', 'Russian', 'Turkish', 'Dutch',
            'Swedish', 'Norwegian', 'Danish', 'Finnish', 'Polish', 'Portuguese (Brazil)',
            'Spanish (Latin America)', 'Swahili', 'Afrikaans', 'Yoruba', 'Igbo', 'Hausa',
            'Zulu', 'Tagalog', 'Vietnamese', 'Thai', 'Greek', 'Hebrew'
        ];
    @endphp

    {{-- Guest Login Modal --}}
    <div id="guest-login-overlay" class="upgrade-popup-overlay" style="display: none;">
        <div class="upgrade-popup-modal">
            <button id="guest-login-close" class="upgrade-popup-close" aria-label="Close">&times;</button>
            <div class="upgrade-popup-content">
                <div class="upgrade-popup-icon" style="background: #6000C2;">
                    <i class="bi bi-person-lock" style="font-size: 2.5rem; color: white;"></i>
                </div>
                <h3 class="upgrade-popup-title">@lang('Login to use English AI')</h3>
                <p class="upgrade-popup-description text-center">
                    @lang('This is a premium AI tool. Please sign in to convert English to global dialects and languages.')
                </p>
                <div class="d-grid mt-4">
                    <a href="{{ route('login') }}" class="upgrade-popup-button" style="background: #6000C2;">
                        @lang('Login Now')
                    </a>
                </div>
                <p class="upgrade-popup-footer mt-4">
                    @lang('Don\'t have an account?') <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">@lang('Join Sparktopus')</a>
                </p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4 px-lg-5 hp-main-container">
        {{-- Premium Header --}}
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
                        @lang('Convert English to any global language or local dialect with AI precision.')
                    </div>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-history" type="button" id="ecHistoryBtn">
                    <i class="bi bi-clock-history"></i>
                    <span class="d-none d-md-inline">@lang('Recent Translations')</span>
                </button>
            </div>
        </div>

        {{-- Horizontal Ad After Header --}}
        <div class="mb-4 text-center">
            <x-ad-slot :advertisement="get_advert_model('above-tool')" />
        </div>

        {{-- Usage Showcase --}}
        <div class="usage-showcase-premium-v2">
            <div class="usage-stats-grid">
                <div class="usage-circle-container">
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

                <div class="usage-info-cards">
                    <div class="usage-info-card">
                        <div class="usage-info-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div class="usage-info-content">
                            <div class="usage-info-label">@lang('Uses Remaining')</div>
                            <div class="usage-info-value">{{ max(0, $limit - $used) }}</div>
                        </div>
                    </div>
                    <div class="usage-info-card">
                        <div class="usage-info-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="usage-info-content">
                            <div class="usage-info-label">@lang('Resets In')</div>
                            <div class="usage-info-value" id="countdown-timer">--:--:--</div>
                        </div>
                    </div>
                    <div class="usage-info-card highlight">
                        <div class="usage-info-icon">
                            @if($plan === 'free') <i class="bi bi-star-fill"></i> @else <i class="bi bi-gem"></i> @endif
                        </div>
                        <div class="usage-info-content">
                            <div class="usage-info-label">@lang('Current Plan')</div>
                            <div class="usage-info-value">{{ ucfirst($plan) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-lg-12">
                <form action="{{ route('tool.handle', $tool->slug) }}" method="POST" id="ecForm">
                    @csrf
                    <input type="hidden" name="target_lang" id="targetLangInput" value="{{ $results['target_lang'] ?? 'US English' }}">
                    <input type="hidden" name="tone" id="toneInput" value="{{ $results['tone'] ?? 'Natural' }}">

                    <div class="ec-input-group shadow-sm">
                        <div class="ec-header-area d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-muted small uppercase tracking-wider">
                                <i class="bi bi-chat-left-text me-2"></i>@lang('Source English Text')
                            </div>
                            <div class="text-muted small">
                                <span id="wordCount">0</span> @lang('words')
                            </div>
                        </div>
                        <textarea name="string" id="textarea" class="ec-textarea" 
                                  placeholder="Paste your English text here to convert..." 
                                  required>{{ $results['original_text'] ?? old('string') }}</textarea>
                    </div>

                    <div class="mt-5">
                        <h5 class="fw-bold mb-3"><i class="bi bi-globe2 me-2 text-primary"></i>@lang('Select Target Language/Dialect')</h5>
                        <div class="ec-lang-grid">
                            @foreach($languages as $lang)
                                <div class="ec-lang-pill {{ ($results['target_lang'] ?? 'US English') === $lang ? 'active' : '' }}" data-value="{{ $lang }}">
                                    {{ $lang }}
                                </div>
                            @endforeach
                            <div class="ec-lang-pill {{ !in_array($results['target_lang'] ?? '', $languages) && isset($results['target_lang']) ? 'active' : '' }}" id="customLangBtn">
                                <i class="bi bi-plus-circle me-1"></i> @lang('Other Language')
                            </div>
                        </div>
                        <div class="mt-3 d-none" id="customLangWrapper">
                            <input type="text" class="form-control rounded-pill px-4" id="customLangInput" placeholder="Enter any language or dialect (e.g. Swahili, Jamaican Patois)...">
                        </div>
                    </div>

                    <div class="mt-5">
                        <h5 class="fw-bold mb-3"><i class="bi bi-magic me-2 text-primary"></i>@lang('Translation Tone')</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach(['Natural', 'Formal', 'Casual', 'Energetic', 'Poetic'] as $t)
                                <div class="btg-pill {{ ($results['tone'] ?? 'Natural') === $t ? 'active' : '' }} tone-pill" data-value="{{ $t }}">
                                    {{ $t }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-center mt-5 pb-5">
                        <button type="submit" class="ec-btn-premium {{ $isLimitReached ? 'ec-btn-ash' : '' }}" id="submitBtn" {{ $isLimitReached ? 'disabled' : '' }}>
                            <i class="bi bi-arrow-repeat me-2"></i>@lang('CONVERT NOW')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($results))
            <div class="ec-result-card shadow-sm" id="result-scroll">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-check2-circle me-2"></i>@lang('AI Converted Result')
                    </h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light rounded-pill px-3 btn-sm border" id="copyBtn">
                            <i class="bi bi-clipboard me-1"></i> @lang('Copy')
                        </button>
                        <button class="btn btn-light rounded-pill px-3 btn-sm border" id="downloadBtn">
                            <i class="bi bi-download me-1"></i> @lang('TXT')
                        </button>
                    </div>
                </div>
                <div class="bg-light p-4 rounded-4 position-relative border" id="resultText">
                    <p style="white-space: pre-wrap; line-height: 1.8; margin-bottom: 0;">{{ $results['converted_text'] }}</p>
                    <div class="badge bg-primary position-absolute top-0 end-0 m-3 rounded-pill px-3">
                        {{ $results['target_lang'] }}
                    </div>
                </div>
            </div>
            <script>document.getElementById('result-scroll').scrollIntoView({ behavior: 'smooth' });</script>
        @endif

        <div class="mt-5 pt-5">
            <x-related-tools :tool="$tool" />
        </div>

        <div class="mt-5 pt-5 border-top">
            <div class="text-center mb-5">
                <h2 class="fw-bold">@lang('How the English AI Converter Works')</h2>
                <p class="text-muted">@lang('Translate and adapt your English content in 4 simple steps.')</p>
            </div>
            <div class="how-it-works-steps">
                <div class="step-card">
                    <span class="step-number">1</span>
                    <i class="bi bi-pencil-square step-icon"></i>
                    <h4 class="step-title">@lang('Input English')</h4>
                    <p class="step-desc">@lang('Paste your source English text or local dialect into the editor.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">2</span>
                    <i class="bi bi-globe2 step-icon"></i>
                    <h4 class="step-title">@lang('Choose Language')</h4>
                    <p class="step-desc">@lang('Select from over 35 global languages including Nigerian Pidgin, French, or Spanish.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">3</span>
                    <i class="bi bi-magic step-icon"></i>
                    <h4 class="step-title">@lang('Set the Tone')</h4>
                    <p class="step-desc">@lang('Pick a tone like Formal or Casual to match your target audience perfectly.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">4</span>
                    <i class="bi bi-check2-all step-icon"></i>
                    <h4 class="step-title">@lang('Get Result')</h4>
                    <p class="step-desc">@lang('Hit convert and receive a high-quality, AI-optimized translation instantly.')</p>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 bg-light rounded-4 border" id="howToUseContent">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>@lang('How the AI English Converter Works')</h5>
            <div class="how-to-use-list text-muted" style="font-size: 14px; line-height: 1.9;">
                {!! strip_tags($tool->content, '<p><a><strong><ul><ol><li>') !!}
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('textarea');
            const wordCountSpan = document.getElementById('wordCount');
            const targetLangInput = document.getElementById('targetLangInput');
            const toneInput = document.getElementById('toneInput');
            const customLangWrapper = document.getElementById('customLangWrapper');
            const customLangInput = document.getElementById('customLangInput');
            const submitBtn = document.getElementById('submitBtn');

            // Word counter
            textarea.addEventListener('input', function() {
                const words = this.value.trim() ? this.value.trim().split(/\s+/).length : 0;
                wordCountSpan.textContent = words;
            });

            // Language pills
            document.querySelectorAll('.ec-lang-pill').forEach(pill => {
                pill.addEventListener('click', function() {
                    document.querySelectorAll('.ec-lang-pill').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    if (this.id === 'customLangBtn') {
                        customLangWrapper.classList.remove('d-none');
                        customLangInput.focus();
                    } else {
                        customLangWrapper.classList.add('d-none');
                        targetLangInput.value = this.dataset.value;
                    }
                });
            });

            customLangInput.addEventListener('input', function() {
                targetLangInput.value = this.value;
            });

            // Tone pills
            document.querySelectorAll('.tone-pill').forEach(pill => {
                pill.addEventListener('click', function() {
                    document.querySelectorAll('.tone-pill').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    toneInput.value = this.dataset.value;
                });
            });

            // Copy/Download
            const copyBtn = document.getElementById('copyBtn');
            if (copyBtn) {
                copyBtn.addEventListener('click', function() {
                    const text = document.getElementById('resultText').innerText.trim();
                    navigator.clipboard.writeText(text).then(() => {
                        this.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
                        setTimeout(() => this.innerHTML = '<i class="bi bi-clipboard me-1"></i> Copy', 2000);
                    });
                });
            }

            const downloadBtn = document.getElementById('downloadBtn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', function() {
                    const text = document.getElementById('resultText').innerText.trim();
                    const blob = new Blob([text], { type: 'text/plain' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'translation.txt';
                    a.click();
                });
            }

            // Countdown Timer
            (function() {
                const resetAtStr = '{{ $reset_at }}';
                if (!resetAtStr) return;
                const resetAt = new Date(resetAtStr);
                const timerEl = document.getElementById('countdown-timer');
                function updateCountdown() {
                    const diff = resetAt - new Date();
                    if (diff <= 0) { timerEl.textContent = '00:00:00'; return; }
                    const h = Math.floor(diff / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    timerEl.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
                }
                updateCountdown();
                setInterval(updateCountdown, 1000);
            })();

            // Modal logic
            const guestOverlay = document.getElementById('guest-login-overlay');
            if (guestOverlay) {
                document.getElementById('guest-login-close').onclick = () => guestOverlay.style.display = 'none';
                @if(session('show_guest_modal')) guestOverlay.style.display = 'flex'; @endif
            }
        });
    </script>
    @endpush
</x-canvas-layout>
