<div class="dashboard-sidebar" id="dashboard-sidebar">
    <!-- Logo Area -->
    <div class="sidebar-logo-area">
    <a href="{{ route('front.index') }}" class="sidebar-brand" data-no-loader>
            {{-- Full logos for expanded state --}}
            <img src="{{ asset('themes/canvas/images/logo.svg') }}" alt="Sparktopus Tools" class="logo-img logo-light">
            <img src="{{ asset('themes/canvas/images/logo-dark.svg') }}" alt="Sparktopus Tools" class="logo-img logo-dark">
            {{-- Simplified icon for collapsed state --}}
            <div class="sidebar-icon-collapsed">
                <i class="bi bi-tools"></i>
            </div>
        </a>
        <!-- Close button for mobile -->
        <button class="btn-close-sidebar" id="sidebar-close" aria-label="Close Sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Workspace Dropdown -->
    <div class="workspace-selector">
        <div class="dropdown w-100">
            <button class="btn-workspace" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="workspace-left">
                    <span class="workspace-icon-box">
                        <i class="bi bi-layers-fill"></i>
                    </span>
                    <span class="workspace-text">{{ request('workspace') == 'agent' ? 'Agent Workspace' : 'Creative Workspace' }}</span>
                </div>
                <i class="bi bi-chevron-expand chevron-icon"></i>
            </button>
            <ul class="dropdown-menu w-100">
                <li>
                    <a class="dropdown-item {{ request('workspace') != 'agent' ? 'active' : '' }}" href="{{ route('front.index', ['workspace' => 'creative']) }}">
                        <i class="bi bi-palette-fill"></i> Creative Workspace
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request('workspace') == 'agent' ? 'active' : '' }}" href="{{ route('front.index', ['workspace' => 'agent']) }}">
                        <i class="bi bi-robot"></i> Agent Workspace
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Navigation - Scrollable -->
    <nav class="sidebar-nav">
        {{-- Mini Toggle for Uncollapsing --}}
        <div class="sidebar-mini-toggle-wrapper">
            <button class="btn-sidebar-mini-toggle" id="sidebar-collapse-toggle-mini" title="Expand Sidebar">
                <i class="bi bi-layout-sidebar"></i>
            </button>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('front.index') && request('workspace') != 'agent' ? 'active' : '' }}" href="{{ route('front.index') }}" title="Home">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('front.tools') ? 'active' : '' }}" href="{{ route('front.tools') }}" title="All Tools">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>All Tools</span>
                </a>
            </li>
        </ul>

        @if(request('workspace') == 'agent')
            {{-- Agent Workspace Content --}}
            <div class="nav-section-title">Agent Tools</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#" title="AI Assistant">
                        <i class="bi bi-robot"></i>
                        <span>AI Assistant</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="Task Automation">
                        <i class="bi bi-gear-wide-connected"></i>
                        <span>Task Automation</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="Workflow Builder">
                        <i class="bi bi-diagram-3"></i>
                        <span>Workflow Builder</span>
                    </a>
                </li>
            </ul>
        @else
            {{-- Creative Workspace Content --}}
            <!-- Playground Section - Only 3 items -->
            <div class="nav-section-title">Playground</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#" title="Storybook Writer">
                        <i class="bi bi-book"></i>
                        <span>Storybook Writer</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="Website Tracker">
                        <i class="bi bi-globe"></i>
                        <span>Website Tracker</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="YT Content Writer">
                        <i class="bi bi-youtube"></i>
                        <span>YT Content Writer</span>
                    </a>
                </li>
            </ul>
        @endif

        <!-- Recently Used Section - Dynamic from database -->
        <div class="nav-section-title">Recently Used</div>
        <ul class="nav flex-column">
            @auth
                @php
                    $recentlyUsedTools = auth()->user()->toolUsages()
                        ->orderBy('last_used_at', 'desc')
                        ->take(5)
                        ->get();
                    
                    $iconMap = config('tool-icons');
                @endphp
                @forelse($recentlyUsedTools as $usage)
                    @php
                        $tool = \App\Models\Tool::where('slug', $usage->tool_name)->with('translations')->first();
                    @endphp
                    @if($tool)
                        @php
                            $toolIcon = $iconMap[$tool->slug] ?? 'bi-tools';
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tool.show', ['tool' => $tool->slug]) }}" title="{{ $tool->name }}">
                                <i class="bi {{ $toolIcon }}"></i>
                                <span>{{ Str::limit($tool->name, 18) }}</span>
                            </a>
                        </li>
                        {{-- Add Saved Results link specifically for AI Humanizer --}}
                        @if($tool->slug == 'ai-humanizer' && request()->routeIs('tool.show') && request()->route('tool') == 'ai-humanizer')
                            <li class="nav-item">
                                <a class="nav-link" href="#" id="sidebarSavedLink" title="Saved Results">
                                    <i class="bi bi-bookmark-star"></i>
                                    <span>Saved Results</span>
                                </a>
                            </li>
                        @endif
                    @endif
                @empty
                    <li class="nav-item">
                        <span class="nav-link text-muted">
                            <i class="bi bi-clock-history"></i>
                            <span>No recent tools</span>
                        </span>
                    </li>
                @endforelse
                @if($recentlyUsedTools->count() > 0)
                    <li class="nav-item">
                        <a class="nav-link see-all-link" href="{{ route('user.history') }}" title="See All">
                            <i class="bi bi-arrow-right-circle"></i>
                            <span>See All</span>
                        </a>
                    </li>
                @endif
            @else
                <li class="nav-item">
                    <span class="nav-link text-muted">
                        <i class="bi bi-clock-history"></i>
                        <span>Log in to see history</span>
                    </span>
                </li>
            @endauth
        </ul>
    </nav>

    <!-- Bottom Actions - Plan-based CTA -->
    <div class="sidebar-footer">
        @guest
            <div class="sidebar-cta-container">
                <p class="plan-cta-text-light">Log in to unlock higher limits</p>
                <a href="{{ route('login') }}" class="btn-upgrade-white-bordered" title="Log In">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Log In</span>
                </a>
            </div>
        @endguest
        
        @auth
            @php
                $userPlan = auth()->user()->planLevel();
            @endphp
            
            <div class="sidebar-cta-container">
                @if($userPlan === 'free')
                    <p class="plan-cta-text-light">Unlock more features</p>
                    <a href="{{ route('plans.list') }}" class="btn-upgrade-white-bordered" title="Upgrade to Classic">
                        <i class="bi bi-shield"></i>
                        <span>Upgrade to Classic</span>
                    </a>
                @elseif($userPlan === 'classic')
                    <p class="plan-cta-text-light">Unlock more features</p>
                    <a href="{{ route('plans.list') }}" class="btn-upgrade-white-bordered" title="Upgrade to Plus">
                        <i class="bi bi-rocket"></i>
                        <span>Upgrade to Plus</span>
                    </a>
                @elseif($userPlan === 'plus')
                    <p class="plan-cta-text-light">Unlock more features</p>
                    <a href="{{ route('plans.list') }}" class="btn-upgrade-white-bordered" title="Upgrade to Pro">
                        <i class="bi bi-gem"></i>
                        <span>Upgrade to Pro</span>
                    </a>
                @else
                    {{-- Pro user: Full access badge --}}
                    <div class="plan-cta pro-access">
                        <p class="plan-cta-text-light">You're enjoying full access</p>
                        <div class="pro-badge-formal">
                            <i class="bi bi-gem"></i>
                            <span>Pro</span>
                        </div>
                    </div>
                @endif
            </div>
        @endauth
    </div>
</div>