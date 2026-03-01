<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" theme-mode="{{ auth('admin')->user()->theme_preference ?? 'voodoo' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'SparkAdmin' }} | Sparktopus Tools</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/spark-admin.css') }}?v={{ time() }}">
    @stack('styles')
</head>
<body>
    <div class="spark-sidebar">
        <!-- Logo Section (Fixed) -->
        <div class="spark-logo-section">
            <div class="spark-logo">
                <i class="bi bi-rocket-takeoff-fill"></i>
                <span>SparkAdmin</span>
            </div>
        </div>

        <!-- Scrollable Navigation -->
        <div class="spark-sidebar-scroll">
            @php $me = auth('admin')->user(); @endphp
            <nav class="spark-nav">
                <!-- Main Section — visible to everyone -->
                <div class="spark-nav-group">
                    <div class="spark-nav-group-title">Main</div>
                    <a href="{{ route('spark-admin.dashboard') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.*dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['tool.manage', 'tool.create', 'tool.edit']))
                    <a href="{{ route('spark-admin.tools.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.tools.*') ? 'active' : '' }}">
                        <i class="bi bi-tools"></i>
                        <span>Tools</span>
                    </a>
                    @endif
                    @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['user.read', 'user.create', 'user.edit']))
                    <a href="{{ route('spark-admin.users.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Users</span>
                    </a>
                    @endif
                </div>

                <!-- Management Section -->
                @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['billing.view-revenue', 'billing.manage-plans', 'feedback.view', 'feedback.respond', 'ad.manage']))
                <div class="spark-nav-group">
                    <div class="spark-nav-group-title">Management</div>
                    @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['billing.view-revenue', 'billing.manage-plans']))
                    <a href="{{ route('spark-admin.subscriptions.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.subscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card-fill"></i>
                        <span>Subscriptions</span>
                    </a>
                    @endif
                    @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['feedback.view', 'feedback.respond']))
                    <a href="{{ route('spark-admin.feedback.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.feedback.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Feedback</span>
                    </a>
                    @endif
                    @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['ad.manage', 'ad.create', 'ad.edit']))
                    <a href="{{ route('spark-admin.advertisement.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.advertisement.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone-fill"></i>
                        <span>Advertisements</span>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Content Section -->
                @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['content.create', 'content.edit', 'content.moderate']))
                <div class="spark-nav-group">
                    <div class="spark-nav-group-title">Content</div>
                    <a href="{{ route('spark-admin.updates.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.updates.*') ? 'active' : '' }}">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span>Updates</span>
                    </a>
                    <a href="#" class="spark-nav-link">
                        <i class="bi bi-bell-fill"></i>
                        <span>Notifications</span>
                    </a>
                </div>
                @endif

                <!-- Access Control Section — Super Admin & admins with role.manage only -->
                @if($me->hasRole('Super Admin') || $me->hasAnyPermission(['role.manage', 'permission.manage']))
                <div class="spark-nav-group">
                    <div class="spark-nav-group-title">Access Control</div>
                    <a href="{{ route('spark-admin.roles.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Roles</span>
                    </a>
                    <a href="{{ route('spark-admin.permissions.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-key-fill"></i>
                        <span>Permissions</span>
                    </a>
                    <a href="{{ route('spark-admin.admins.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.admins.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i>
                        <span>Admins</span>
                    </a>
                </div>
                @endif

                <!-- Settings Section — Super Admin & system.settings only -->
                <div class="spark-nav-group">
                    <div class="spark-nav-group-title">Settings</div>
                    <a href="{{ route('spark-admin.profile.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                    @if($me->hasRole('Super Admin') || $me->hasPermissionTo('system.settings'))
                    <a href="{{ route('spark-admin.settings.index') }}" class="spark-nav-link {{ request()->routeIs('spark-admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill"></i>
                        <span>Site Settings</span>
                    </a>
                    @endif
                </div>

                <!-- Logout -->
                <div class="spark-divider"></div>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="spark-nav-link" style="color: #ef4444;">
                    <i class="bi bi-power"></i>
                    <span>Sign Out</span>
                </a>
                <form id="logout-form-sidebar" action="{{ route('spark-admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
        </div>

        <!-- Footer (Fixed) -->
        <div class="spark-sidebar-footer">
            <div class="spark-version-tag">SparkTerminal v2.1.0</div>
        </div>
    </div>

    <main class="spark-wrapper">
        <header class="spark-header">
            <div>
                <h1>{{ $title ?? 'Dashboard' }}</h1>
                <p class="spark-header-subtitle">Welcome back, Master Admin.</p>
            </div>
            <div class="header-actions">
                <div class="spark-nav-badge">
                    <i class="bi bi-bell-fill"></i>
                    <div class="spark-badge-dot"></div>
                </div>

                <button class="theme-toggle-btn" id="theme-toggle" title="Toggle Theme">
                    <i class="bi bi-sun-fill" id="theme-icon-light" style="display: none;"></i>
                    <i class="bi bi-moon-stars-fill" id="theme-icon-dark"></i>
                </button>
                
                <div style="position: relative;">
                    <div id="profile-trigger" class="profile-trigger">
                        <div class="profile-avatar">
                            {{ substr(auth('admin')->user()->name, 0, 1) }}
                        </div>
                        <span class="profile-name">{{ auth('admin')->user()->name }}</span>
                        <i class="bi bi-chevron-down profile-chevron"></i>
                    </div>

                    <div id="profile-dropdown" class="spark-dropdown">
                        <div class="spark-dropdown-header">
                            <div class="spark-dropdown-header-title">Master Panel</div>
                            <div class="spark-dropdown-header-email">{{ auth('admin')->user()->email }}</div>
                        </div>
                        <a href="{{ route('spark-admin.profile.index') }}" class="spark-dropdown-item">
                            <i class="bi bi-person-badge"></i> Master Profile
                        </a>
                        <a href="{{ route('spark-admin.settings.index') }}" class="spark-dropdown-item">
                            <i class="bi bi-shield-lock"></i> Security Settings
                        </a>
                        <div class="spark-dropdown-divider"></div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="spark-dropdown-item" style="color: #ef4444;">
                            <i class="bi bi-box-arrow-right"></i> System Logout
                        </a>
                        <form id="logout-form" action="{{ route('spark-admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{ $slot }}
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            const toggleBtn = document.getElementById('theme-toggle');
            const lightIcon = document.getElementById('theme-icon-light');
            const darkIcon = document.getElementById('theme-icon-dark');

            function updateIcons(mode) {
                if (mode === 'alpine') {
                    lightIcon.style.display = 'block';
                    darkIcon.style.display = 'none';
                } else {
                    lightIcon.style.display = 'none';
                    darkIcon.style.display = 'block';
                }
            }

            // Initial icon state
            updateIcons(html.getAttribute('theme-mode'));

            // Profile Dropdown Logic
            const profileTrigger = document.getElementById('profile-trigger');
            const profileDropdown = document.getElementById('profile-dropdown');

            profileTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = profileDropdown.style.display === 'block';
                profileDropdown.style.display = isOpen ? 'none' : 'block';
                profileTrigger.style.borderColor = isOpen ? 'var(--spark-border)' : 'var(--spark-accent)';
            });

            document.addEventListener('click', function() {
                profileDropdown.style.display = 'none';
                profileTrigger.style.borderColor = 'var(--spark-border)';
            });

            profileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            toggleBtn.addEventListener('click', function() {
                const currentMode = html.getAttribute('theme-mode');
                const newMode = currentMode === 'voodoo' ? 'alpine' : 'voodoo';
                
                html.setAttribute('theme-mode', newMode);
                updateIcons(newMode);

                // Save preference via AJAX
                fetch('{{ route("spark-admin.profile.update-theme") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ theme: newMode })
                }).catch(err => console.log('Theme save failed:', err));
            });
        });
    </script>

    @stack('scripts')
</body>
</html>