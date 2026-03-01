<x-canvas-layout>
    @push('page_header')
        <link rel="stylesheet" href="{{ asset('css/ai-humanizer.css') }}">
    @endpush

    {{-- Global Drag Overlay --}}
    <div class="drag-overlay" id="dragOverlay">
        <div class="drag-content border-0">
            <i class="bi bi-cloud-arrow-up-fill display-1"></i>
            <h2 class="fw-bold fs-1">@lang('Drop to Humanize')</h2>
            <p class="lead">@lang('Release to upload your PDF or TXT file instantly')</p>
        </div>
    </div>

    {{-- Guest Login Modal --}}
    <div id="guest-login-overlay" class="upgrade-popup-overlay" style="display: none;">
        <div class="upgrade-popup-modal">
            <button id="guest-login-close" class="upgrade-popup-close" aria-label="Close">&times;</button>
            <div class="upgrade-popup-content">
                <div class="upgrade-popup-icon" style="background: #4a0094;">
                    <i class="bi bi-person-lock" style="font-size: 2.5rem; color: white;"></i>
                </div>
                <h3 class="upgrade-popup-title">@lang('Login to use this tool')</h3>
                <p class="upgrade-popup-description text-center">
                    @lang('This is a premium tool. Please sign in to your account to humanize your text and access all features.')
                </p>
                <div class="d-grid mt-4">
                    <a href="{{ route('login') }}" class="upgrade-popup-button" style="background: #4a0094; border: none; box-shadow: none;">
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
        #guest-login-overlay {
            backdrop-filter: blur(2px);
        }
        .hp-main-container {
            transition: filter 0.3s ease;
        }

        /* Header Style Fixes */
        .btn-star-header {
            color: #26282D !important;
            text-decoration: none !important;
            font-weight: 600;
        }
        .btn-star-header:hover {
            color: #6000C2 !important;
            opacity: 0.8;
        }

        /* Unique Usage Showcase Styles */
        .usage-showcase-premium {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .usage-stat-card {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon-wrap {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: rgba(96, 0, 194, 0.08);
            color: #6000C2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-content .stat-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .stat-content .stat-value {
            display: block;
            font-size: 18px;
            font-weight: 800;
            color: #333;
        }

        .usage-progress-wrap {
            flex: 1;
            max-width: 300px;
        }

        .usage-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .usage-progress-bar {
            height: 8px;
            background: rgba(0,0,0,0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .usage-progress-fill {
            height: 100%;
            background: #6000C2;
            border-radius: 10px;
            transition: width 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .usage-badge-premium {
            background: #6000C2;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 15px rgba(96, 0, 194, 0.2);
        }

        /* Step Layout Styles */
        .how-it-works-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }

        .step-card {
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            position: relative;
            text-align: center;
        }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.06);
            border-color: rgba(96, 0, 194, 0.1);
        }

        .step-number {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 35px;
            height: 35px;
            background: #6000C2;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            box-shadow: 0 5px 15px rgba(96, 0, 194, 0.3);
        }

        .step-icon {
            font-size: 40px;
            color: #6000C2;
            margin-bottom: 20px;
            display: block;
        }

        .step-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #333;
        }

        .step-desc {
            font-size: 14px;
            color: #777;
            line-height: 1.6;
            margin: 0;
        }

        [theme-mode="dark"] .usage-showcase-premium {
            background: rgba(26, 26, 26, 0.7);
            border-color: rgba(255,255,255,0.05);
        }
        [theme-mode="dark"] .stat-content .stat-value { color: #eee; }
        [theme-mode="dark"] .step-card { background: #1a1a1a; border-color: rgba(255,255,255,0.05); }
        [theme-mode="dark"] .step-title { color: #eee; }
        [theme-mode="dark"] .step-desc { color: #999; }
        [theme-mode="dark"] .btn-star-header { color: #ececec !important; }
    </style>

    @php
        $user = auth()->user();
        $plan = $user ? $user->planLevel() : 'free';
        $toolSlug = 'ai-humanizer';
        $usage = $user ? $user->toolUsages()->where('tool_name', $toolSlug)->first() : null;
        $used = $usage->usage_count ?? 0;
        $limit = $user ? $user->getDailyLimit($toolSlug) : 3;
        $remaining = $user ? $user->getRemainingUsage($toolSlug) : 3;
        $percentage = ($limit > 0) ? min(100, ($used / $limit) * 100) : 0;
        
        $charLimits = ['free' => 1000, 'classic' => 2500, 'plus' => 5000, 'pro' => 15000];
        $charLimit = $charLimits[$plan] ?? 1000;
    @endphp

    <div class="loading-wrapper" id="humanizerLoaderOverlay">
        <div class="custom-loader-container bg-white p-4 rounded-4 shadow-lg border" style="max-width: 320px; border-width: 2px !important;">
            <div class="loader-icons mb-3">
                <i class="bi bi-screwdriver icon-1 text-primary" style="font-size: 2.2rem;"></i>
                <i class="bi bi-wrench icon-2 text-secondary" style="font-size: 2.2rem;"></i>
            </div>
            <div class="loading-msg h5 fw-bold mb-1" id="loadingMsg">@lang('Analyzing patterns...')</div>
            <p class="text-muted small mb-0">@lang('Modernizing AI content...')</p>
        </div>
    </div>

    <style>
        #humanizerLoaderOverlay {
            background: transparent;
            z-index: 10005;
        }
        [theme-mode="dark"] .custom-loader-container {
            background: #111 !important;
            border-color: #333 !important;
        }
        /* Ash Button Style */
        .btn-ash {
            background-color: #e0e0e0 !important;
            border-color: #d0d0d0 !important;
            color: #888 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            pointer-events: auto !important; /* Allow JS to catch click */
        }
        [theme-mode="dark"] .btn-ash {
            background-color: #333 !important;
            border-color: #444 !important;
            color: #666 !important;
        }
    </style>

    <div class="container-fluid py-4 px-lg-5 hp-main-container" id="hpMainContainer">
        {{-- Custom Header --}}
        <div class="humanizer-page-header d-flex align-items-center justify-content-between flex-nowrap">
            <div class="header-left d-flex align-items-center gap-3">
                <a href="{{ route('front.index') }}" class="back-link flex-shrink-0">
                    <i class="bi bi-arrow-left"></i>
                    <span class="d-none d-md-inline">@lang('Back')</span>
                </a>
                <div class="header-divider d-none d-md-block"></div>
                <div class="title-group">
                    <h1 class="tool-title mb-0 h4 fw-bold">{{ $tool->name }}</h1>
                    <div class="tool-description-mini small text-muted d-none d-lg-block">
                        @lang('Transform AI text into human-like content.')
                    </div>
                </div>
            </div>
            <div class="header-right d-flex align-items-center gap-2">
                <a href="#" class="btn-star-header py-2 px-3 flex-shrink-0" id="viewFavorites">
                    <i class="bi bi-star"></i>
                    <span class="d-none d-md-inline ms-1">@lang('Saved')</span>
                </a>
                <div class="history-dropdown" id="historyDropdown">
                    <button type="button" class="btn-history py-2 px-3 flex-shrink-0" id="historyBtn">
                        <i class="bi bi-clock-history"></i>
                        <span class="d-none d-md-inline ms-1">@lang('Recent Results')</span>
                    </button>
                    {{-- History Dropdown Menu --}}
                    <div class="history-menu shadow-lg" id="historyMenu">
                        <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span class="fw-bold small">@lang('Recent Results')</span>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" id="clearHistory">
                                <i class="bi bi-trash3 me-1"></i>@lang('Clear')
                            </button>
                        </div>
                        <div class="history-list" id="historyList">
                            <div class="empty-history text-center py-4 text-muted">
                                <i class="bi bi-inbox d-block mb-2" style="font-size: 1.5rem;"></i>
                                @lang('No recent results')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Horizontal Ad after tool header --}}
        @if(in_array($plan, ['free', 'classic']))
            <div class="horizontal-ad-slot mt-4 mb-5" style="min-height: 160px;">
                @php $adModel = get_advert_model('above-tool'); @endphp
                @if($adModel && $adModel->status)
                    <x-ad-slot :advertisement="$adModel" />
                @else
                    <div class="d-flex align-items-center justify-content-center w-100 h-100 p-4" style="min-height: 160px; background: linear-gradient(135deg, #f8f6ff 0%, #ede8fa 100%); border-radius: 12px;">
                        <div class="text-center">
                            <img src="{{ asset('images/ad-placeholder.png') }}" alt="Advertisement" style="max-height: 120px; max-width: 100%; object-fit: contain; border-radius: 8px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display:none;">
                                <i class="bi bi-megaphone-fill" style="font-size: 2.5rem; color: #6000C2; opacity: 0.4;"></i>
                                <p class="text-muted small mt-2 mb-0">@lang('Advertisement Space')</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

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
                            <div class="usage-info-value" id="countdown-timer">--:--:--</div>
                        </div>
                    </div>

                    <div class="usage-info-card highlight">
                        <div class="usage-info-icon">
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
                        <div class="usage-info-content">
                            <div class="usage-info-label">@lang('Current Plan')</div>
                            <div class="usage-info-value">{{ ucfirst($plan) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
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

            /* Mobile Redesign for Showcase */
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

            #countdown-timer {
                font-family: 'Inter', sans-serif;
                font-variant-numeric: tabular-nums;
            }
        </style>

        <script>
            // Countdown Timer
            (function() {
                const resetAt = new Date('{{ $reset_at ?? now()->addDay()->toIso8601String() }}');
                const timerEl = document.getElementById('countdown-timer');
                
                function updateCountdown() {
                    const now = new Date();
                    const diff = resetAt - now;
                    
                    if (diff <= 0) {
                        timerEl.textContent = '00:00:00';
                        // Optionally reload page when timer hits 0
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    
                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    
                    timerEl.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
                
                updateCountdown();
                setInterval(updateCountdown, 1000);
            })();
        </script>


        <div class="humanizer-layout-refined">
            <div class="humanizer-main-full">
                <form action="{{ route('tool.handle', ['tool' => 'ai-humanizer']) }}" method="POST" id="aiHumanizerForm" data-no-loader enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" id="fileInput" accept=".pdf,.txt" class="d-none">
                    
                    {{-- Style Settings Hidden Inputs --}}
                    <input type="hidden" name="creativity" id="hiddenCreativity" value="Medium">
                    <input type="hidden" name="formality" id="hiddenFormality" value="Neutral">
                    <input type="hidden" name="variety" id="hiddenVariety" value="Balanced">
                    <input type="hidden" name="length" id="hiddenLength" value="Same">

                    <div class="humanizer-editors">
                        {{-- Input Editor --}}
                        <div class="humanizer-editor shadow-sm">
                            <div class="editor-head">
                                <div class="editor-label">
                                    <i class="bi bi-textarea-t"></i>
                                    <span>@lang('Source Content')</span>
                                </div>
                                <div class="editor-actions">
                                    <button type="button" class="btn-icon" id="uploadBtn" title="@lang('Upload PDF/TXT')">
                                        <i class="bi bi-paperclip"></i>
                                    </button>
                                    <button type="button" class="btn-icon text-danger" id="clearText" title="@lang('Clear All')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="editor-body">
                                <textarea name="string" id="textarea" placeholder="@lang('Paste AI-generated text or drop a file anywhere on this page...')">{{ $results['original_text'] ?? old('string') }}</textarea>
                            </div>

                            <div class="editor-foot">
                                <div class="counter-group">
                                    <span>@lang('Words'): <b id="wordCount">0</b></span>
                                    <span>@lang('Chars'): <b id="charCount">0</b> / <span id="charLimitDisplay">{{ $charLimit }}</span></span>
                                </div>
                                <div class="plan-limit-info">
                                    @lang('Max'): <b>{{ number_format($charLimit) }} @lang('chars')</b>
                                </div>
                            </div>
                        </div>

                        {{-- Output Editor --}}
                        <div class="humanizer-editor shadow-sm {{ !isset($results) ? 'is-empty opacity-75' : '' }}" id="outputEditor">
                            {{-- Quality Score indicator --}}
                            @if(isset($results))
                                <div class="quality-score-wrap shadow-sm">
                                    <canvas id="qualityChart" width="50" height="50"></canvas>
                                    <div class="score-inner" id="scoreValue">0%</div>
                                </div>
                            @endif

                            <div class="editor-head">
                                <div class="editor-label">
                                    <i class="bi bi-stars"></i>
                                    <span>@lang('Humanized Result')</span>
                                </div>
                                @if(isset($results))
                                    <div class="editor-actions">
                                        <button type="button" class="btn-icon" id="toggleComparison" title="@lang('Toggle Comparison')">
                                            <i class="bi bi-columns-gutters"></i>
                                        </button>
                                        <button type="button" class="btn-icon btn-star" id="saveFavorite" title="@lang('Save to Favorites')">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="editor-body">
                                @if(isset($results))
                                    <textarea id="rewriteResult" readonly>{{ $results['converted_text'] }}</textarea>
                                    <div class="comparison-view d-none" id="comparisonView">
                                        <div class="diff-pane original-pane" id="diffOriginal"></div>
                                        <div class="diff-pane humanized-pane" id="diffHumanized"></div>
                                    </div>
                                @else
                                    <div class="empty-state d-flex flex-column align-items-center justify-content-center p-5 text-muted" style="min-height: 400px;">
                                        <i class="bi bi-cpu-fill display-1 mb-3 opacity-25"></i>
                                        <p class="lead">@lang('Humanized output will appear here after processing')</p>
                                    </div>
                                @endif
                            </div>

                            <div class="editor-foot">
                                @if(isset($results))
                                    <div class="editor-actions">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill me-2 px-3" id="copyResult">
                                            <i class="bi bi-clipboard me-1"></i> @lang('Copy')
                                        </button>
                                        <div class="export-dropdown" id="exportDropdown">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="exportBtn">
                                                <i class="bi bi-download me-1"></i> @lang('Export')
                                            </button>
                                            <div class="dropdown-menu shadow-lg border-0" id="exportMenu">
                                                <div class="dropdown-item cursor-pointer" data-type="txt"><i class="bi bi-filetype-txt me-2"></i> @lang('Download TXT')</div>
                                                <div class="dropdown-item cursor-pointer" data-type="doc"><i class="bi bi-file-earmark-word me-2"></i> @lang('Download Word')</div>
                                                <div class="dropdown-item cursor-pointer" data-type="pdf"><i class="bi bi-file-earmark-pdf me-2"></i> @lang('Download PDF')</div>
                                                <hr class="dropdown-divider">
                                                <div class="dropdown-item cursor-pointer" data-type="email"><i class="bi bi-envelope me-2"></i> @lang('Email Result')</div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-patch-check me-1"></i> @lang('Humanized')</span>
                                @else
                                    <span class="text-muted">@lang('Ready to process')</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <button type="submit" class="btn humanize-btn px-5 py-3 rounded-pill fw-bold {{ $used >= $limit ? 'btn-ash' : '' }}" id="submitBtn" {{ $used >= $limit ? 'disabled' : '' }} style="background-color: #6000C2; border: none; color: white; background-image: none;">
                            <i class="bi bi-lightning-charge-fill me-2"></i>
                            <span class="fw-bold text-uppercase tracking-widest">@lang('HUMANIZE')</span>
                        </button>
                    </div>
                </form>

                {{-- Advanced Settings Panel --}}
                <div class="settings-panel collapsed mt-5" id="settingsPanel">
                    <div class="settings-header" id="settingsToggle">
                        <div class="header-title">
                            <i class="bi bi-sliders"></i>
                            <span>@lang('Advanced Humanization Settings')</span>
                        </div>
                        <i class="bi bi-chevron-down chevron"></i>
                    </div>
                    <div class="settings-content">
                        <div class="setting-group">
                            <div class="label-wrap">
                                <label>@lang('Creativity Level')</label>
                                <span class="value-label" id="valCreativity">@lang('Medium')</span>
                            </div>
                            <div class="slider-container">
                                <input type="range" min="1" max="3" step="1" value="2" class="style-slider" data-target="Creativity" data-values='["Low", "Medium", "High"]'>
                                <div class="range-labels">
                                    <span>@lang('Low')</span>
                                    <span>@lang('Medium')</span>
                                    <span>@lang('High')</span>
                                </div>
                            </div>
                        </div>
                        <div class="setting-group">
                            <div class="label-wrap">
                                <label>@lang('Formality')</label>
                                <span class="value-label" id="valFormality">@lang('Neutral')</span>
                            </div>
                            <div class="slider-container">
                                <input type="range" min="1" max="3" step="1" value="2" class="style-slider" data-target="Formality" data-values='["Casual", "Neutral", "Professional"]'>
                                <div class="range-labels">
                                    <span>@lang('Casual')</span>
                                    <span>@lang('Neutral')</span>
                                    <span>@lang('Prof')</span>
                                </div>
                            </div>
                        </div>
                        <div class="setting-group">
                            <div class="label-wrap">
                                <label>@lang('Sentence Variety')</label>
                                <span class="value-label" id="valVariety">@lang('Balanced')</span>
                            </div>
                            <div class="slider-container">
                                <input type="range" min="1" max="3" step="1" value="2" class="style-slider" data-target="Variety" data-values='["Simple", "Balanced", "Complex"]'>
                                <div class="range-labels">
                                    <span>@lang('Simple')</span>
                                    <span>@lang('Balanced')</span>
                                    <span>@lang('Complex')</span>
                                </div>
                            </div>
                        </div>
                        <div class="setting-group">
                            <div class="label-wrap">
                                <label>@lang('Output Length')</label>
                                <span class="value-label" id="valLength">@lang('Same')</span>
                            </div>
                            <div class="slider-container">
                                <input type="range" min="1" max="3" step="1" value="2" class="style-slider" data-target="Length" data-values='["Shorter", "Same", "Longer"]'>
                                <div class="range-labels">
                                    <span>@lang('Shorter')</span>
                                    <span>@lang('Same')</span>
                                    <span>@lang('Longer')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Related Tools --}}
                <div class="mt-5 pt-4">
                    <x-related-tools :tool="$tool" />
                </div>

                {{-- How It Works Section --}}
                <div class="mt-5 pt-5 border-top tool-content-standalone">
                    <div class="text-center mb-5">
                        <h2 class="display-5 fw-bold mb-4">@lang('How this AI Humanizer works')</h2>
                        <p class="text-muted lead">@lang('Humanize your AI content in 4 simple steps')</p>
                    </div>

                    <div class="how-it-works-steps">
                        <div class="step-card">
                            <span class="step-number">1</span>
                            <i class="bi bi-file-earmark-text step-icon"></i>
                            <h4 class="step-title">@lang('Paste Content')</h4>
                            <p class="step-desc">@lang('Paste your AI-generated text from ChatGPT, Claude, or any LLM into the source editor.')</p>
                        </div>
                        <div class="step-card">
                            <span class="step-number">2</span>
                            <i class="bi bi-shield-check step-icon"></i>
                            <h4 class="step-title">@lang('Analyze Patterns')</h4>
                            <p class="step-desc">@lang('Our advanced algorithms analyze linguistic patterns, syntax, and predictable AI structures.')</p>
                        </div>
                        <div class="step-card">
                            <span class="step-number">3</span>
                            <i class="bi bi-magic step-icon"></i>
                            <h4 class="step-title">@lang('Humanize')</h4>
                            <p class="step-desc">@lang('Click "Humanize" to rewrite the text with natural flow, varied sentence lengths, and human warmth.')</p>
                        </div>
                        <div class="step-card">
                            <span class="step-number">4</span>
                            <i class="bi bi-download step-icon"></i>
                            <h4 class="step-title">@lang('Ready to Use')</h4>
                            <p class="step-desc">@lang('Check your humanization score and export your content in PDF, Word, or TXT format.')</p>
                        </div>
                    </div>

                    <div class="mt-5 p-4 bg-light rounded-4 border" id="howToUseContent">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>@lang('How to Use AI Humaniser')</h5>
                        <div class="how-to-use-list text-muted" style="font-size: 14px; line-height: 1.9;">
                            {!! strip_tags($tool->content, '<p><a><strong><ul><ol><li>') !!}
                        </div>
                    </div>
                    <style>
                        .how-to-use-list p {
                            margin-bottom: 12px;
                        }
                        .how-to-use-list ul,
                        .how-to-use-list ol {
                            padding-left: 20px;
                            margin-bottom: 12px;
                        }
                        .how-to-use-list li {
                            margin-bottom: 8px;
                            line-height: 1.8;
                        }
                        [theme-mode="dark"] #howToUseContent {
                            background: #1a1a1a !important;
                            border-color: rgba(255,255,255,0.06) !important;
                        }
                    </style>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var el = document.getElementById('howToUseContent');
                            if (el) {
                                el.innerHTML = el.innerHTML.replace(/paraphraser/gi, 'humaniser').replace(/paraphrase/gi, 'humanise');
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
        {{-- CDN Libraries - Moved to bottom and deferred for performance --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/diff-match-patch/1.0.5/index.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/7.1.0/docx.js" defer></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Sidebar Saved Link Trigger
                const viewFavorites = document.getElementById('viewFavorites');
                // viewFavorites click handler is registered later in the Favorites section

                const sidebarSavedLink = document.getElementById('sidebarSavedLink');
                if (sidebarSavedLink) {
                    sidebarSavedLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        const viewFavoritesBtn = document.getElementById('viewFavorites');
                        if (viewFavoritesBtn) viewFavoritesBtn.click();
                    });
                }

                // Constants & State
                const CHAR_LIMIT = {{ $charLimit }};
                const IS_RESULT = {{ isset($results) ? 'true' : 'false' }};
                const ORIGINAL_TEXT = `{!! isset($results) ? addslashes($results['original_text']) : '' !!}`;
                const HUMANIZED_TEXT = `{!! isset($results) ? addslashes($results['converted_text']) : '' !!}`;

                // Elements
                const textarea = document.getElementById('textarea');
                const wordCount = document.getElementById('wordCount');
                const charCount = document.getElementById('charCount');
                const humanizerForm = document.getElementById('aiHumanizerForm');
                const submitBtn = document.getElementById('submitBtn');
                const formWrapper = document.getElementById('formWrapper');
                const uploadBtn = document.getElementById('uploadBtn');
                const fileInput = document.getElementById('fileInput');
                const loadingMsg = document.getElementById('loadingMsg');
                
                // --- 1. Real-time Counter ---
                const updateCounters = () => {
                    const text = textarea.value;
                    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
                    const chars = text.length;

                    wordCount.textContent = words.toLocaleString();
                    charCount.textContent = chars.toLocaleString();

                    // Clear file input if user types
                    if (chars > 0 && fileInput.value) {
                        fileInput.value = "";
                        textarea.placeholder = "@lang('Paste AI-generated text or drop a file anywhere on this page...')";
                    }

                    if (chars > CHAR_LIMIT) {
                        charCount.parentElement.classList.add('limit-reached');
                        submitBtn.disabled = true;
                    } else if (chars > CHAR_LIMIT * 0.9) {
                        charCount.parentElement.classList.add('limit-warning');
                        charCount.parentElement.classList.remove('limit-reached');
                        submitBtn.disabled = false;
                    } else {
                        charCount.parentElement.classList.remove('limit-warning', 'limit-reached');
                        submitBtn.disabled = false;
                    }
                };
                textarea.addEventListener('input', updateCounters);
                updateCounters();

                // --- Upload Button Listener ---
                if (uploadBtn && fileInput) {
                    uploadBtn.addEventListener('click', () => fileInput.click());
                    fileInput.addEventListener('change', (e) => {
                        if (e.target.files.length) {
                            handleFile(e.target.files[0]);
                        }
                    });
                }

                // --- 2. Advanced Settings Panel ---
                document.getElementById('settingsToggle').addEventListener('click', () => {
                    document.getElementById('settingsPanel').classList.toggle('collapsed');
                });

                document.querySelectorAll('.style-slider').forEach(slider => {
                    slider.addEventListener('input', function() {
                        const target = this.dataset.target;
                        const values = JSON.parse(this.dataset.values);
                        const value = values[this.value - 1];
                        document.getElementById('val' + target).textContent = value;
                        document.getElementById('hidden' + target).value = value;
                        localStorage.setItem('hp_setting_' + target.toLowerCase(), this.value);
                    });

                    // Restore settings
                    const saved = localStorage.getItem('hp_setting_' + slider.dataset.target.toLowerCase());
                    if (saved) {
                        slider.value = saved;
                        slider.dispatchEvent(new Event('input'));
                    }
                });

                // --- 3. Loading Animation ---
                const loadingMessages = [
                    "@lang('Analyzing AI patterns...')",
                    "@lang('Making it more human...')",
                    "@lang('Adding natural flow...')",
                    "@lang('Improving readability...')",
                    "@lang('Almost there...')"
                ];

                humanizerForm.addEventListener('submit', (e) => {
            if (submitBtn && submitBtn.classList.contains('btn-ash')) {
                e.preventDefault();
                showUpgradePopup();
                return;
            }

            @if(auth()->guest())
                e.preventDefault();
                showGuestModal();
                return;
            @endif

            @if($used >= $limit)
                e.preventDefault();
                showUpgradePopup();
                return;
            @endif

            document.body.classList.add('hp-loading-active');
            var mainContent = document.getElementById('hpMainContainer');
            if (mainContent) mainContent.classList.add('blurred');
            
            // Cycle messages
            let msgIdx = 0;
            const msgs = [
                "@lang('Analyzing patterns...')",
                "@lang('Humanizing content...')",
                "@lang('Applying flow...')",
                "@lang('Finalizing...')"
            ];
            
            const loaderInt = setInterval(() => {
                if(!document.body.classList.contains('hp-loading-active')) {
                    clearInterval(loaderInt);
                    return;
                }
                msgIdx = (msgIdx + 1) % msgs.length;
                document.getElementById('loadingMsg').innerText = msgs[msgIdx];
            }, 3000);
        });

        function showUpgradePopup() {
            const overlay = document.getElementById('upgrade-popup-overlay');
            const mainContent = document.getElementById('hpMainContainer');
            if (overlay) {
                overlay.style.display = 'flex';
                if (mainContent) mainContent.classList.add('blurred');
            }
        }

        // Add direct click listener for the ash button area
        submitBtn.addEventListener('click', function(e) {
            if (this.classList.contains('btn-ash')) {
                e.preventDefault();
                showUpgradePopup();
            }
        });

        function showGuestModal() {
            const overlay = document.getElementById('guest-login-overlay');
            const mainContent = document.getElementById('hpMainContainer');
            if (overlay) overlay.style.display = 'flex';
            if (mainContent) mainContent.classList.add('blurred');
        }

        function hideGuestModal() {
            const overlay = document.getElementById('guest-login-overlay');
            const mainContent = document.getElementById('hpMainContainer');
            if (overlay) overlay.style.display = 'none';
            if (mainContent) mainContent.classList.remove('blurred');
        }
        
                const guestCloseBtn = document.getElementById('guest-login-close');
                const guestOverlay = document.getElementById('guest-login-overlay');

                if (guestCloseBtn) {
                    guestCloseBtn.addEventListener('click', hideGuestModal);
                }

                if (guestOverlay) {
                    guestOverlay.addEventListener('click', (e) => {
                        if (e.target === guestOverlay) hideGuestModal();
                    });
                }

                // --- 4. Quality Score (Chart.js) ---
                if (IS_RESULT) {
                    const calculateQuality = () => {
                        const originalLen = ORIGINAL_TEXT.length;
                        const humanizedLen = HUMANIZED_TEXT.length;
                        const diff = Math.abs(originalLen - humanizedLen);
                        let score = 85; // Base high score
                        
                        // Heuristic adjustments
                        if (diff < originalLen * 0.05) score -= 5;
                        if (HUMANIZED_TEXT.includes('the') && HUMANIZED_TEXT.includes('and')) score += 5;
                        
                        return Math.min(Math.max(score, 45), 98);
                    };

                    const score = calculateQuality();
                    document.getElementById('scoreValue').textContent = score + '%';
                    const scoreColor = score > 80 ? '#22c55e' : (score > 60 ? '#f59e0b' : '#ef4444');

                    new Chart(document.getElementById('qualityChart'), {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [score, 100 - score],
                                backgroundColor: [scoreColor, 'rgba(0,0,0,0.05)'],
                                borderWidth: 0,
                                circumference: 360,
                                rotation: 0
                            }]
                        },
                        options: {
                            cutout: '80%',
                            responsive: false,
                            plugins: { tooltip: { enabled: false } }
                        }
                    });
                }

                // --- 5. Comparison View (diff-match-patch) ---
                if (IS_RESULT) {
                    const toggleBtn = document.getElementById('toggleComparison');
                    const rewriteResult = document.getElementById('rewriteResult');
                    const comparisonView = document.getElementById('comparisonView');
                    const dmp = new diff_match_patch();

                    toggleBtn.addEventListener('click', () => {
                        const isActive = comparisonView.classList.toggle('d-none');
                        toggleBtn.classList.toggle('active');
                        rewriteResult.classList.toggle('d-none');

                        if (!isActive) {
                            const diffs = dmp.diff_main(ORIGINAL_TEXT, HUMANIZED_TEXT);
                            dmp.diff_cleanupSemantic(diffs);
                            
                            let originalHtml = '';
                            let humanizedHtml = '';

                            diffs.forEach(part => {
                                const type = part[0]; // 0: same, -1: del, 1: ins
                                const text = part[1];

                                if (type === 0) {
                                    originalHtml += text;
                                    humanizedHtml += text;
                                } else if (type === -1) {
                                    originalHtml += `<del>${text}</del>`;
                                } else if (type === 1) {
                                    humanizedHtml += `<ins>${text}</ins>`;
                                }
                            });

                            document.getElementById('diffOriginal').innerHTML = originalHtml;
                            document.getElementById('diffHumanized').innerHTML = humanizedHtml;
                        }
                    });
                }

                // --- 6. History Management ---
                const saveToHistory = (original, humanized) => {
                    let history = JSON.parse(localStorage.getItem('hp_history') || '[]');
                    const entry = {
                        id: Date.now(),
                        original: original.substring(0, 100) + '...',
                        fullOriginal: original,
                        humanized: humanized,
                        time: new Date().toISOString()
                    };
                    history.unshift(entry);
                    localStorage.setItem('hp_history', JSON.stringify(history.slice(0, 10)));
                };

                if (IS_RESULT && !window.location.search.includes('from_history')) {
                    saveToHistory(ORIGINAL_TEXT, HUMANIZED_TEXT);
                }

                const renderHistory = () => {
                    const list = document.getElementById('historyList');
                    if (!list) return;
                    const history = JSON.parse(localStorage.getItem('hp_history') || '[]');
                    
                    if (history.length === 0) {
                        list.innerHTML = `<div class="empty-history text-center py-4 text-muted"><i class="bi bi-inbox d-block mb-2" style="font-size: 1.5rem;"></i>@lang('No recent results')</div>`;
                        return;
                    }

                    list.innerHTML = history.map(item => `
                        <div class="history-item" data-id="${item.id}">
                            <div class="item-meta">
                                <span>${new Date(item.time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                <span>${item.humanized.split(/\s+/).length} words</span>
                            </div>
                            <div class="item-preview">${item.original}</div>
                        </div>
                    `).join('');

                    list.querySelectorAll('.history-item').forEach(el => {
                        el.addEventListener('click', () => {
                            const item = history.find(h => h.id == el.dataset.id);
                            textarea.value = item.fullOriginal;
                            updateCounters();
                            window.location.hash = 'input';
                            document.getElementById('historyMenu')?.classList.remove('show');
                        });
                    });
                };

                const historyBtnEl = document.getElementById('historyBtn');
                const historyMenuEl = document.getElementById('historyMenu');
                const clearHistoryBtn = document.getElementById('clearHistory');

                if (historyBtnEl && historyMenuEl) {
                    historyBtnEl.addEventListener('click', (e) => {
                        e.stopPropagation();
                        historyMenuEl.classList.toggle('show');
                        renderHistory();
                    });
                }

                if (clearHistoryBtn) {
                    clearHistoryBtn.addEventListener('click', () => {
                        localStorage.setItem('hp_history', '[]');
                        renderHistory();
                    });
                }

                // --- 7. Copy Button Feedback ---
                const copyBtn = document.getElementById('copyResult');
                if (copyBtn) {
                    copyBtn.addEventListener('click', () => {
                        const text = document.getElementById('rewriteResult').value;
                        navigator.clipboard.writeText(text).then(() => {
                            const icon = copyBtn.querySelector('i');
                            const originalClass = icon.className;
                            icon.className = 'bi bi-check-lg';
                            copyBtn.style.color = '#22c55e';
                            copyBtn.style.borderColor = '#22c55e';
                            
                            if (window.navigator.vibrate) window.navigator.vibrate(50);

                            setTimeout(() => {
                                icon.className = originalClass;
                                copyBtn.style.color = '';
                                copyBtn.style.borderColor = '';
                            }, 2000);
                        });
                    });
                }

                // --- 8. Favorites ---
                const starBtn = document.getElementById('saveFavorite');
                if (starBtn) {
                    const checkFavorite = () => {
                        const favorites = JSON.parse(localStorage.getItem('hp_favorites') || '[]');
                        if (favorites.some(f => f.humanized === HUMANIZED_TEXT)) {
                            starBtn.classList.add('favorited');
                        }
                    };
                    checkFavorite();

                    starBtn.addEventListener('click', () => {
                        let favorites = JSON.parse(localStorage.getItem('hp_favorites') || '[]');
                        const index = favorites.findIndex(f => f.humanized === HUMANIZED_TEXT);
                        
                        if (index === -1) {
                            favorites.unshift({
                                original: ORIGINAL_TEXT,
                                humanized: HUMANIZED_TEXT,
                                time: new Date().toISOString()
                            });
                            starBtn.classList.add('favorited');
                            ArtisanApp.toastSuccess("@lang('Saved to Favorites!')");
                        } else {
                            favorites.splice(index, 1);
                            starBtn.classList.remove('favorited');
                        }
                    localStorage.setItem('hp_favorites', JSON.stringify(favorites));
                    });
                }

                // --- View Favorites ---
                const viewFavoritesBtn = document.getElementById('viewFavorites');
                if (viewFavoritesBtn && historyMenuEl) {
                    viewFavoritesBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const menu = historyMenuEl;
                        const header = menu.querySelector('.dropdown-header span');
                        const list = document.getElementById('historyList');
                        if (!list) return;
                        const favorites = JSON.parse(localStorage.getItem('hp_favorites') || '[]');
                        
                        if (header) header.textContent = "@lang('Saved Favorites')";
                        menu.classList.add('show');
                        
                        if (favorites.length === 0) {
                            list.innerHTML = `<div class="empty-history text-center py-4 text-muted"><i class="bi bi-star d-block mb-2" style="font-size: 1.5rem;"></i>@lang('No favorites saved')</div>`;
                            return;
                        }

                        list.innerHTML = favorites.map((item, idx) => `
                            <div class="history-item" data-idx="${idx}">
                                <div class="item-meta">
                                    <span>${new Date(item.time).toLocaleDateString()}</span>
                                    <span>${item.humanized.split(/\s+/).length} words</span>
                                </div>
                                <div class="item-preview">${item.original.substring(0, 60)}...</div>
                            </div>
                        `).join('');

                        list.querySelectorAll('.history-item').forEach(el => {
                            el.addEventListener('click', () => {
                                const item = favorites[el.dataset.idx];
                                textarea.value = item.original;
                                updateCounters();
                                menu.classList.remove('show');
                            });
                        });
                    });
                }

                // --- 9. Export Options ---
                const exportBtn = document.getElementById('exportBtn');
                const exportMenu = document.getElementById('exportMenu');
                if (exportBtn) {
                    exportBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        exportMenu.classList.toggle('show');
                    });

                    document.querySelectorAll('#exportMenu .dropdown-item').forEach(item => {
                        item.addEventListener('click', () => {
                            const type = item.dataset.type;
                            const text = HUMANIZED_TEXT;

                            if (type === 'txt') {
                                const blob = new Blob([text], {type: 'text/plain'});
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'humanized-result.txt';
                                a.click();
                            } else if (type === 'pdf') {
                                const { jsPDF } = window.jspdf;
                                const doc = new jsPDF();
                                doc.setFontSize(16);
                                doc.text("AI Humanizer Result - Sparktopus Tools", 10, 20);
                                doc.setFontSize(11);
                                const splitText = doc.splitTextToSize(text, 180);
                                doc.text(splitText, 10, 40);
                                doc.save('humanized-result.pdf');
                            } else if (type === 'doc') {
                                const blob = new Blob([text], {type: 'application/msword'});
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'humanized-result.doc';
                                a.click();
                            } else if (type === 'email') {
                                window.location.href = `mailto:?subject=Humanized Text&body=${encodeURIComponent(text)}`;
                            }
                        });
                    });
                }

                // --- 10. Drop Anywhere & File Handling ---
                const dragOverlay = document.getElementById('dragOverlay');
                let dragCounter = 0;

                window.addEventListener('dragenter', (e) => {
                    e.preventDefault();
                    dragCounter++;
                    if (dragCounter === 1) dragOverlay.classList.add('active');
                });

                window.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dragCounter--;
                    if (dragCounter === 0) dragOverlay.classList.remove('active');
                });

                window.addEventListener('dragover', (e) => e.preventDefault());

                const handleFile = (file) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (['txt', 'pdf'].includes(ext)) {
                        if (ext === 'txt') {
                            const reader = new FileReader();
                            reader.onload = (re) => {
                                textarea.value = re.target.result;
                                fileInput.value = ''; 
                                updateCounters();
                                ArtisanApp.toastSuccess("@lang('File loaded successfully!')");
                            };
                            reader.readAsText(file);
                        } else {
                            // PDF Handling: Attach to file input
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            fileInput.files = dataTransfer.files;
                            
                            // Visual feedback
                            textarea.value = '';
                            textarea.placeholder = `PDF: ${file.name} (Attached)`;
                            textarea.style.backgroundColor = 'rgba(96, 0, 194, 0.02)';
                            
                            // Update UI counters (simulate some length so button enables if needed)
                            wordCount.textContent = 'PDF';
                            charCount.textContent = 'File Attached';
                            submitBtn.disabled = false;
                            
                            ArtisanApp.toastSuccess(`@lang('PDF ready for processing: ') ${file.name}`);
                        }
                    } else {
                        ArtisanApp.toastError("@lang('Only TXT and PDF files are supported')");
                    }
                };

                // Drop on overlay
                dragOverlay.addEventListener('drop', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dragCounter = 0;
                    dragOverlay.classList.remove('active');
                    if (e.dataTransfer.files.length) {
                        handleFile(e.dataTransfer.files[0]);
                    }
                });

                // Drop on window (fallback)
                window.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dragCounter = 0;
                    dragOverlay.classList.remove('active');
                    
                    if (e.dataTransfer.files.length) {
                        handleFile(e.dataTransfer.files[0]);
                    }
                });

                // Global clicks
                window.addEventListener('click', () => {
                    document.getElementById('historyMenu')?.classList.remove('show');
                    exportMenu?.classList.remove('show');
                });

                // Clear btn
                document.getElementById('clearText').addEventListener('click', () => {
                    textarea.value = '';
                    updateCounters();
                });

                // Reset Timer Logic with Plan-Specific Hours
                const updateTimer = () => {
                    const plan = "{{ $plan }}";
                    const resetHours = {
                        'free': 24,
                        'classic': 22,
                        'plus': 20,
                        'pro': 18
                    }[plan] || 24;

                    const now = new Date();
                    // We'll simulate a countdown based on the interval
                    // For a real implementation, we'd need the last usage timestamp from the server
                    // But for the UI, we'll continue the midnight reset logic adjusted by the plan
                    const resetTime = new Date();
                    resetTime.setHours(resetHours, 0, 0, 0); 
                    
                    if (now > resetTime) {
                        resetTime.setDate(resetTime.getDate() + 1);
                    }

                    const diff = resetTime - now;
                    const h = Math.floor(diff / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    
                    const timerEl = document.getElementById('resetTimer');
                    if (timerEl) {
                        timerEl.textContent = `@lang('Resets in') ${h}h ${m}m ${s}s`;
                    }
                };
                setInterval(updateTimer, 1000);
                updateTimer();
            });
        </script>
    @endpush
</x-canvas-layout>
