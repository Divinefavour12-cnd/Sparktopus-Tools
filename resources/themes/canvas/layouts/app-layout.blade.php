<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="@lang('front.direction')"
    theme-mode="{{ (theme_option('dark_default_theme') == 'dark' && theme_option('enable_dark_mode') == 1 && request()->cookie('siteMode') != 'light') || request()->cookie('siteMode') === 'dark' ? 'dark' : 'light' }}">

<head>
    <meta name="app-search" content="{{ route('search') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Bootstrap Icons CDN --}}
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/themes/canvas/assets/sass/app.scss', 'resources/themes/canvas/assets/js/app.js'])
    @meta_tags()
    @meta_tags('header')
    @stack('page_header')
    @if (setting('enable_header_code', 0))
        {!! setting('header_code') !!}
    @endif
    <style>
        .page-content-blur {
            filter: blur(8px);
            pointer-events: none;
            transition: filter 0.3s ease;
        }
    </style>
</head>

<body>
    {{-- Dark Mode Toggle Component --}}
    <x-application-theme-switch />

    <div class="dashboard-wrapper" id="dashboard-wrapper">
        {{-- Sidebar Overlay for Mobile --}}
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        {{-- Fixed Sidebar (Desktop & Mobile Offcanvas) --}}
        @include('components.dashboard-sidebar')

        {{-- Main Content Area --}}
        <div class="main-content">
            {{-- Dashboard Header - Only on Home Page --}}
            @if(request()->routeIs('front.index'))
                @include('components.dashboard-header')
            @endif

            {{-- Workspace Content Logic --}}
            @if(request('workspace') == 'agent')
                <div class="container h-100 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                    <div class="text-center">
                        <i class="bi bi-robot fs-1 text-primary mb-3"></i>
                        <h2 class="fw-bold">Agent Workspace</h2>
                        <p class="text-muted">This workspace is coming soon. Stay tuned!</p>
                        <a href="{{ route('front.index', ['workspace' => 'creative']) }}" class="btn btn-outline-primary rounded-pill mt-3">
                            Back to Creative Workspace
                        </a>
                    </div>
                </div>
            @else
                {{-- Creative Workspace (Normal Content) --}}
                <div class="page-content" id="page-content-area">
                    {{ $slot }}
                </div>
            @endif
        </div>
        
        {{-- Mobile Bottom Nav --}}
        @include('components.mobile-bottom-nav')
    </div>

    <x-application-adblock />
    <x-application-signout />
    <x-application-messages />
    @include('components.page-loader')
    <x-application-cookies-consent />
    <x-application-back-to-top />
    
    @meta_tags('footer')

    {{-- Popup ad logic based on Plan --}}
    @php
        $showPopup = false;
        $user = auth()->user();
        $isHomePage = request()->routeIs('front.index');
        $isToolPage = request()->routeIs('tool.show') || request()->routeIs('tool.handle');
        
        if (!$user) {
            $showPopup = true; // Guests see ads like free users
        } else {
            $plan = $user->planLevel();
            if ($plan === 'free') {
                $showPopup = true;
            } elseif ($plan === 'classic') {
                if ($isHomePage || $isToolPage) {
                    $showPopup = true;
                }
            }
        }
    @endphp

    @if($showPopup)
        @include('partials.popup-ad')
    @endif

    {{-- Upgrade popup for tool usage limits --}}
    @auth
        @include('partials.upgrade-popup')
    @endauth

    {{-- Global Feedback Widget --}}
    @include('partials.feedback-widget')

    @stack('page_scripts')
    
    {{-- Sidebar Toggle & Collapse Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('dashboard-wrapper');
            const sidebar = document.getElementById('dashboard-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const mobileToggle = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');
            const collapseToggle = document.getElementById('sidebar-collapse-toggle');
            
            // Storage key for collapse state
            const COLLAPSE_KEY = 'sparktopus_sidebar_collapsed';
            
            // Restore collapse state on desktop
            function restoreCollapseState() {
                if (window.innerWidth >= 992) {
                    const isCollapsed = localStorage.getItem(COLLAPSE_KEY) === 'true';
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                        wrapper.classList.add('sidebar-collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                        wrapper.classList.remove('sidebar-collapsed');
                    }
                }
            }
            
            // Toggle mobile sidebar (offcanvas)
            function toggleMobileSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }
            
            // Close mobile sidebar
            function closeMobileSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            // Toggle desktop sidebar collapse
            function toggleDesktopCollapse() {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                wrapper.classList.toggle('sidebar-collapsed', isCollapsed);
                localStorage.setItem(COLLAPSE_KEY, isCollapsed);
            }
            
            // Event listeners
            if (mobileToggle) {
                mobileToggle.addEventListener('click', toggleMobileSidebar);
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', closeMobileSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }
            
            if (collapseToggle) {
                collapseToggle.addEventListener('click', toggleDesktopCollapse);
            }

            // Uncollapse when clicking the mini-toggle button in collapsed state
            const miniToggle = document.getElementById('sidebar-collapse-toggle-mini');
            if (miniToggle) {
                miniToggle.addEventListener('click', function(e) {
                    if (sidebar.classList.contains('collapsed')) {
                        e.stopPropagation();
                        toggleDesktopCollapse();
                    }
                });
            }
            
            // Restore state on load
            restoreCollapseState();

            @auth
            // Notification click handler
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') e.preventDefault();
                    
                    const id = this.dataset.id;
                    const url = this.getAttribute('href');
                    
                    fetch(`{{ url('user/notifications/mark-as-read') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        if (url !== '#') window.location.href = url;
                    });
                });
            });
            @endauth
            
            // Handle resize - reset mobile state on desktop
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (window.innerWidth >= 992) {
                        closeMobileSidebar();
                        restoreCollapseState();
                    } else {
                        // Remove collapsed state on mobile
                        sidebar.classList.remove('collapsed');
                        wrapper.classList.remove('sidebar-collapsed');
                    }
                }, 150);
            });
        });
    </script>

    @if (setting('enable_footer_code', 0))
        {!! setting('footer_code') !!}
    @endif
</body>
</html>
