<x-canvas-layout>
    {{-- Welcome Section --}}
    <div class="welcome-section">
        {{-- Top Right: Plan-based CTA --}}
        <div class="welcome-top-right">
            @guest
                <a href="{{ route('login') }}" class="plan-cta-link">
                    Log in to unlock higher limits <i class="bi bi-box-arrow-in-right"></i>
                </a>
            @endguest
            @auth
                @php
                    $userPlan = auth()->user()->planLevel();
                @endphp
                @if($userPlan === 'free')
                    <div class="homepage-cta-formal d-flex align-items-center gap-2">
                        <span class="cta-label-bold">Unlock for higher limits</span>
                        <a href="{{ route('plans.list') }}" class="btn-upgrade-white-bordered-hp"><i class="bi bi-shield"></i> Upgrade to Classic</a>
                    </div>
                @elseif($userPlan === 'classic')
                    <div class="homepage-cta-formal d-flex align-items-center gap-2">
                        <span class="cta-label-bold">Unlock for higher limits</span>
                        <a href="{{ route('plans.list') }}" class="btn-upgrade-white-bordered-hp"><i class="bi bi-rocket"></i> Upgrade to Plus</a>
                    </div>
                @elseif($userPlan === 'plus')
                    <div class="homepage-cta-formal d-flex align-items-center gap-2">
                        <span class="cta-label-bold">Unlock for higher limits</span>
                        <a href="{{ route('plans.list') }}" class="btn-upgrade-white-bordered-hp"><i class="bi bi-gem"></i> Upgrade to Pro</a>
                    </div>
                @else
                    <div class="homepage-cta-formal pro-access d-flex align-items-center gap-2">
                        <span class="cta-label-bold">You're enjoying full access</span>
                        <div class="pro-badge-formal-hp">
                            <i class="bi bi-gem"></i> PRO MEMBER
                        </div>
                    </div>
                @endif
            @endauth
        </div>
        
        {{-- AI Humanizer Link with light border oval --}}
        <a href="{{ route('tool.show', ['tool' => 'ai-humanizer']) }}" class="ai-humanizer-pill">
            <span class="new-badge">New</span>
            <span class="ai-text">AI Humanizer</span>
            <i class="bi bi-chevron-right"></i>
        </a>
        
        {{-- Workspace Label --}}
        <p class="workspace-label">My Workspace</p>
        
        {{-- Greeting --}}
        <h2>Hello {{ auth()->check() ? auth()->user()->first_name : 'Guest' }}!</h2>
        <p>What do you want Sparktopus to accelerate today?</p>
    </div>

    {{-- Search Section --}}
    <div class="search-hero">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" name="q" class="form-control" placeholder="Search all tools" autocomplete="off">
            <i class="bi bi-search search-icon"></i>
        </form>
        
        {{-- Quick Tags - Top 3 tools from last 24 hours --}}
        <div class="quick-tags">
            @foreach($search_suggestions ?? $popular_tools->take(3) as $suggestedTool)
                <a href="{{ route('search', ['q' => $suggestedTool->name]) }}" class="badge">
                    <i class="bi bi-search"></i> {{ $suggestedTool->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Two Column Grid for Tools --}}
    <div class="row g-4">
        {{-- Trending Tools Section - Last 7 days most used --}}
        <div class="col-lg-6">
            <div class="grid-section">
                <h3>Trending Tools</h3>
                <div class="d-flex flex-column gap-3">
                    @foreach($trending_tools ?? $popular_tools->take(4) as $tool)
                        @php
                            $iconMap = config('tool-icons');
                            $toolIcon = $iconMap[$tool->slug] ?? 'bi-tools';
                            
                            // Format view count
                            $viewCount = $tool->views_count ?? views($tool)->count();
                            if ($viewCount >= 1000000) {
                                $formattedCount = number_format($viewCount / 1000000, 1) . 'M';
                            } elseif ($viewCount >= 1000) {
                                $formattedCount = number_format($viewCount / 1000, 1) . 'k';
                            } else {
                                $formattedCount = $viewCount;
                            }
                        @endphp
                        <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}" class="tool-card-dashboard">
                            <div class="tool-icon-circle">
                                <i class="{{ $toolIcon }}"></i>
                            </div>
                            <div class="tool-info">
                                <h4>{{ $tool->name }}</h4>
                                <div class="tool-desc">{{ Str::limit($tool->meta_description, 45) }}</div>
                            </div>
                            <div class="tool-meta">
                                <i class="bi bi-eye"></i> {{ $formattedCount }}
                            </div>
                            <button class="tool-save-btn" onclick="event.preventDefault();">
                                <i class="bi bi-bookmark"></i>
                            </button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Newly Added Section - With view count instead of date --}}
        <div class="col-lg-6">
            <div class="grid-section">
                <h3>Newly Added</h3>
                <div class="d-flex flex-column gap-3">
                    @foreach($newly_added_tools->take(4) as $tool)
                        @php
                            $iconMap = config('tool-icons');
                            $toolIcon = $iconMap[$tool->slug] ?? 'bi-tools';
                            
                            // View count instead of date
                            $viewCount = views($tool)->count();
                            if ($viewCount >= 1000000) {
                                $formattedCount = number_format($viewCount / 1000000, 1) . 'M';
                            } elseif ($viewCount >= 1000) {
                                $formattedCount = number_format($viewCount / 1000, 1) . 'k';
                            } else {
                                $formattedCount = $viewCount;
                            }
                        @endphp
                        <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}" class="tool-card-dashboard">
                            <div class="tool-icon-circle">
                                <i class="{{ $toolIcon }}"></i>
                            </div>
                            <div class="tool-info">
                                <h4>{{ $tool->name }}</h4>
                                <div class="tool-desc">{{ Str::limit($tool->meta_description, 45) }}</div>
                            </div>
                            <div class="tool-meta">
                                <i class="bi bi-eye"></i> {{ $formattedCount }}
                            </div>
                            <button class="tool-save-btn" onclick="event.preventDefault();">
                                <i class="bi bi-bookmark"></i>
                            </button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-canvas-layout>