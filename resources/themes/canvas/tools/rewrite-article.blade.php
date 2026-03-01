<x-canvas-layout>
    @push('page_header')
        <link rel="stylesheet" href="{{ asset('css/ai-humanizer.css') }}">
        <style>
            /* ═══════════════════════════════════════════════════════════════
               PREMIUM ARTICLE REWRITER STYLES
               ═══════════════════════════════════════════════════════════════ */
            
            .ar-container { max-width: 1000px; margin: 0 auto; padding-bottom: 60px; }
            
            /* Usage Showcase Styles */
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
            [theme-mode="dark"] .usage-showcase-premium-v2 { background: rgba(96, 0, 194, 0.08) !important; border-color: rgba(96, 0, 194, 0.2) !important; }
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
            
            /* MODE PILLS */
            .ar-mode-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; margin-top: 15px; }
            .ar-pill { padding: 10px 14px; background: #fff; border: 1px solid #eee; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 600; text-align: center; transition: all 0.2s; color: #555; display: flex; align-items: center; justify-content: center; gap: 8px; }
            .ar-pill:hover { border-color: #6000C2; background: rgba(96, 0, 194, 0.04); color: #6000C2; }
            .ar-pill.active { background: #6000C2; color: white; border-color: #6000C2; box-shadow: 0 4px 12px rgba(96, 0, 194, 0.2); }
            [theme-mode="dark"] .ar-pill { background: #1a1a2e; border-color: #333; color: #aaa; }
            [theme-mode="dark"] .ar-pill.active { background: #6000C2; color: #fff; }

            /* UNIQUE CREATIVITY SLIDER/PILLS */
            .ar-creativity-box { background: rgba(96, 0, 194, 0.03); border-radius: 15px; padding: 20px; border: 1px dashed rgba(96,0,194,0.2); }
            [theme-mode="dark"] .ar-creativity-box { background: rgba(255, 255, 255, 0.02); }

            /* EDITOR BOX */
            .ar-editor-card { background: #fff; border-radius: 20px; border: 1px solid #eee; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
            [theme-mode="dark"] .ar-editor-card { background: #1a1a2e; border-color: #333; }
            .ar-editor-header { padding: 12px 20px; border-bottom: 1px solid #f5f5f5; background: #fafafa; }
            [theme-mode="dark"] .ar-editor-header { background: #151525; border-color: #333; }
            .ar-textarea { width: 100%; min-height: 250px; border: none; padding: 20px; font-size: 16px; line-height: 1.7; outline: none; background: transparent; color: inherit; resize: none; }
            
            .ar-btn-premium { background: #6000C2; color: white; padding: 15px 45px; border: none; border-radius: 50px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; box-shadow: 0 6px 20px rgba(96, 0, 194, 0.3); }
            .ar-btn-premium:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(96, 0, 194, 0.4); background: #4B0096; }
            .ar-btn-premium:disabled { background: #ccc; box-shadow: none; cursor: not-allowed; opacity: 0.7; }

            .ar-result-card { background: #fff; border-radius: 20px; border: 1px solid #eee; padding: 25px; margin-top: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
            [theme-mode="dark"] .ar-result-card { background: #1a1a2e; border-color: #333; }

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
        </style>
    @endpush

    @php
        $usagePercent = $limit > 0 ? (min($used, $limit) / $limit) * 100 : 0;
        $circumference = 2 * 3.14159 * 45;
        $dashOffset = $circumference - ($circumference * $usagePercent / 100);
        
        $modes = [
            ['id' => 'Smart', 'icon' => 'bi-cpu', 'desc' => 'Balanced & Human-like'],
            ['id' => 'Creative', 'icon' => 'bi-palette', 'desc' => 'Vivid & Original'],
            ['id' => 'Professional', 'icon' => 'bi-briefcase', 'desc' => 'Business Formal'],
            ['id' => 'Casual', 'icon' => 'bi-chat-heart', 'desc' => 'Friendly & Relaxed'],
            ['id' => 'Academic', 'icon' => 'bi-book', 'desc' => 'Research Quality'],
            ['id' => 'Shorten', 'icon' => 'bi-scissors', 'desc' => 'Concise & Direct'],
            ['id' => 'Expand', 'icon' => 'bi-textarea-t', 'desc' => 'Detailed & Elaborate']
        ];
    @endphp

    {{-- Guest Modal --}}
    <div id="guest-login-overlay" class="upgrade-popup-overlay" style="display: none;">
        <div class="upgrade-popup-modal">
            <button id="guest-login-close" class="upgrade-popup-close" aria-label="Close">&times;</button>
            <div class="upgrade-popup-content">
                <div class="upgrade-popup-icon" style="background: #6000C2;">
                    <i class="bi bi-person-lock" style="font-size: 2.5rem; color: white;"></i>
                </div>
                <h3 class="upgrade-popup-title">@lang('Sign In to Rewrite')</h3>
                <p class="upgrade-popup-description text-center">
                    @lang('Experience premium AI article rewriting. Please log in to your account to access all features.')
                </p>
                <div class="d-grid mt-4">
                    <a href="{{ route('login') }}" class="upgrade-popup-button" style="background: #6000C2;">@lang('Login Now')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4 px-lg-5 ar-container">
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
                    <div class="tool-description-mini d-none d-lg-block">@lang('Transform articles into high-quality, plagiarism-free content instantly.')</div>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-history" type="button" onclick="window.location.href='{{ route('tool.show', $tool->slug) }}'">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span class="d-none d-md-inline">@lang('Reset Tool')</span>
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
                            <div class="usage-info-label">@lang('Credits Left')</div>
                            <div class="usage-info-value">{{ max(0, $limit - $used) }}</div>
                        </div>
                    </div>
                    <div class="usage-info-card">
                        <div class="usage-info-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="usage-info-content">
                            <div class="usage-info-label">@lang('Next Reset')</div>
                            <div class="usage-info-value" id="countdown-timer">--:--:--</div>
                        </div>
                    </div>
                    <div class="usage-info-card highlight">
                        <div class="usage-info-icon"><i class="bi {{ $plan === 'free' ? 'bi-star-fill' : 'bi-gem' }}"></i></div>
                        <div class="usage-info-content">
                            <div class="usage-info-label">@lang('Active Plan')</div>
                            <div class="usage-info-value">{{ ucfirst($plan) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('tool.handle', $tool->slug) }}" method="POST" id="arForm">
            @csrf
            <input type="hidden" name="mode" id="modeInput" value="{{ $results['mode'] ?? 'Smart' }}">
            <input type="hidden" name="creativity" id="creativityInput" value="{{ $results['creativity'] ?? 'Medium' }}">

            <div class="row g-4 mt-2">
                <div class="col-lg-12">
                    <div class="ar-editor-card shadow-sm">
                        <div class="ar-editor-header d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-muted small uppercase tracking-wider">
                                <i class="bi bi-file-earmark-text me-2"></i>@lang('Original Article')
                            </div>
                            <div class="text-muted small">
                                <span id="wordCount">0</span> / {{ $tool->wc_tool ?? 5000 }} @lang('words')
                            </div>
                        </div>
                        <textarea name="string" id="textarea" class="ar-textarea" 
                                  placeholder="Paste your article here to rewrite..." 
                                  required>{{ $results['original_text'] ?? old('string') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-12 mt-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3"><i class="bi bi-sliders me-2 text-primary"></i>@lang('Rewriting Mode')</h5>
                            <div class="ar-mode-grid">
                                @foreach($modes as $m)
                                    <div class="ar-pill {{ ($results['mode'] ?? 'Smart') === $m['id'] ? 'active' : '' }} mode-pill" 
                                         data-value="{{ $m['id'] }}" title="{{ $m['desc'] }}">
                                        <i class="bi {{ $m['icon'] }}"></i> {{ $m['id'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h5 class="fw-bold mb-3"><i class="bi bi-magic me-2 text-primary"></i>@lang('Creativity Level')</h5>
                            <div class="ar-creativity-box">
                                <div class="d-flex gap-2">
                                    @foreach(['Low', 'Medium', 'High'] as $lvl)
                                        <div class="ar-pill flex-grow-1 {{ ($results['creativity'] ?? 'Medium') === $lvl ? 'active' : '' }} creativity-pill" 
                                             data-value="{{ $lvl }}">
                                            {{ $lvl }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center mt-2 small text-muted">
                                    <span id="creativityDesc">@lang('Balanced transformation for natural results.')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center mt-5">
                    <button type="submit" class="ar-btn-premium" id="submitBtn" {{ $isLimitReached ? 'disabled' : '' }}>
                        <i class="bi bi-stars me-2"></i>@lang('REWRITE ARTICLE NOW')
                    </button>
                    @if($isLimitReached)
                        <div class="text-danger small mt-2 fw-bold">@lang('Daily limit reached. Upgrade for more!')</div>
                    @endif
                </div>
            </div>
        </form>

        @if(isset($results))
            <div class="ar-result-card shadow-sm" id="result-scroll">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1 text-primary">
                            <i class="bi bi-check-circle-fill me-2"></i>@lang('Rewritten Content')
                        </h4>
                        <div class="badge bg-soft-primary text-primary rounded-pill px-3">
                            {{ $results['mode'] }} Mode • {{ $results['creativity'] }} Creativity
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light rounded-pill px-3 btn-sm border" id="copyBtn">
                            <i class="bi bi-clipboard me-1"></i> @lang('Copy')
                        </button>
                        <button class="btn btn-light rounded-pill px-3 btn-sm border" id="downloadBtn">
                            <i class="bi bi-download me-1"></i> @lang('Save .txt')
                        </button>
                    </div>
                </div>
                <div class="bg-light p-4 rounded-4 border" id="resultText">
                    <p style="white-space: pre-wrap; line-height: 1.8; margin-bottom: 0;">{{ $results['article_rewrite'] }}</p>
                </div>
            </div>
            <script>document.getElementById('result-scroll').scrollIntoView({ behavior: 'smooth' });</script>
        @endif

        {{-- RELATED TOOLS & HOW IT WORKS --}}
        <div class="mt-5 pt-5">
            <x-related-tools :tool="$tool" />
        </div>

        <div class="mt-5 pt-5 border-top">
            <div class="text-center mb-5">
                <h2 class="fw-bold">@lang('How the AI Article Rewriter Works')</h2>
                <p class="text-muted">@lang('Get high-quality, unique content in seconds.')</p>
            </div>
            <div class="how-it-works-steps">
                <div class="step-card">
                    <span class="step-number">1</span>
                    <i class="bi bi-file-text step-icon"></i>
                    <h4 class="step-title">@lang('Paste Content')</h4>
                    <p class="step-desc">@lang('Enter the article you want to refresh or modernize.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">2</span>
                    <i class="bi bi-gear-wide-connected step-icon"></i>
                    <h4 class="step-title">@lang('Adjust Settings')</h4>
                    <p class="step-desc">@lang('Pick a rewriting mode and creativity level to match your vibe.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">3</span>
                    <i class="bi bi-magic step-icon"></i>
                    <h4 class="step-title">@lang('AI Processing')</h4>
                    <p class="step-desc">@lang('Our advanced AI reconstructs your text for maximum impact.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">4</span>
                    <i class="bi bi-check2-circle step-icon"></i>
                    <h4 class="step-title">@lang('Perfect Result')</h4>
                    <p class="step-desc">@lang('Download your fresh, unique, and engaging new article.')</p>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 bg-light rounded-4 border" id="howToUseContent">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>@lang('Mastering the AI Article Rewriter')</h5>
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
            const modeInput = document.getElementById('modeInput');
            const creativityInput = document.getElementById('creativityInput');
            const creativityDesc = document.getElementById('creativityDesc');

            const descs = {
                'Low': "@lang('Minimal changes. Preserves original vocabulary.')",
                'Medium': "@lang('Balanced transformation for natural results.')",
                'High': "@lang('Bold changes. Completely fresh perspective.')"
            };

            // Word counter
            function updateWordCount() {
                const words = textarea.value.trim() ? textarea.value.trim().split(/\s+/).length : 0;
                wordCountSpan.textContent = words;
            }
            textarea.addEventListener('input', updateWordCount);
            updateWordCount();

            // Mode Selection
            document.querySelectorAll('.mode-pill').forEach(pill => {
                pill.addEventListener('click', function() {
                    document.querySelectorAll('.mode-pill').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    modeInput.value = this.dataset.value;
                });
            });

            // Creativity Selection
            document.querySelectorAll('.creativity-pill').forEach(pill => {
                pill.addEventListener('click', function() {
                    document.querySelectorAll('.creativity-pill').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    const val = this.dataset.value;
                    creativityInput.value = val;
                    creativityDesc.textContent = descs[val];
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
                    a.download = 'rewritten-article.txt';
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
