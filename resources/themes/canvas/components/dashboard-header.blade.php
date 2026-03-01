<header class="dashboard-header">
    <div class="header-left">
        {{-- Mobile Sidebar Toggle --}}
        <button class="btn p-0 border-0 bg-transparent" id="sidebar-toggle" aria-label="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>
        
        {{-- Desktop Collapse Toggle --}}
        <button class="btn p-0 border-0 bg-transparent" id="sidebar-collapse-toggle" aria-label="Collapse Sidebar" title="Toggle sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        
        {{-- Page Title --}}
        <h1 class="page-title">Home</h1>
    </div>
    
    <div class="right-actions">
        {{-- Feedback Link --}}
        <a href="{{ route('contact') }}" class="header-link">Feedback</a>
        
        {{-- Docs Link --}}
        <a href="#" class="header-link">Docs</a>
        
        {{-- Favourites --}}
        <a href="#" class="btn btn-favourites">
            <i class="bi bi-bookmark"></i>
            <span>Favourites</span>
        </a>
        
        {{-- Notifications --}}
        @auth
            @php
                $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                $unreadCount = auth()->user()->unreadNotifications()->count();
            @endphp
            <div class="dropdown">
                <button class="btn-icon position-relative" type="button" data-bs-toggle="dropdown" aria-label="Notifications" id="notificationDropdown">
                    <i class="bi bi-bell"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 0.6rem; padding: 0.3em 0.5em;" id="notification-count">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0 mt-2" aria-labelledby="notificationDropdown" style="width: 320px;">
                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">@lang('Notifications')</h6>
                        <a href="{{ route('user.notifications') }}" class="text-primary small text-decoration-none">@lang('View All')</a>
                    </div>
                    <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                        @forelse($unreadNotifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}" class="dropdown-item p-3 border-bottom notification-item" data-id="{{ $notification->id }}">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        @php
                                            $icon = 'bi-info-circle';
                                            $color = 'text-info';
                                            if(isset($notification->data['type'])) {
                                                switch($notification->data['type']) {
                                                    case 'success': $icon = 'bi-check-circle-fill'; $color = 'text-success'; break;
                                                    case 'warning': $icon = 'bi-exclamation-triangle-fill'; $color = 'text-warning'; break;
                                                    case 'danger': $icon = 'bi-x-circle-fill'; $color = 'text-danger'; break;
                                                }
                                            }
                                        @endphp
                                        <div class="fs-5 {{ $color }}">
                                            <i class="bi {{ $icon }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <p class="mb-0 fw-bold text-truncate small" style="max-width: 180px;">{{ $notification->data['title'] ?? 'Notice' }}</p>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 text-muted small text-truncate">{{ $notification->data['message'] ?? '' }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-center">
                                <i class="bi bi-bell-slash text-muted mb-2 fs-3 d-block opacity-50"></i>
                                <span class="text-muted small">@lang('No new notifications')</span>
                            </div>
                        @endforelse
                    </div>
                    @if($unreadCount > 0)
                        <div class="p-2 text-center bg-light rounded-bottom-4">
                            <a href="{{ route('user.notifications.readAll') }}" class="btn btn-link btn-sm text-decoration-none text-muted small">
                                <i class="bi bi-check-all"></i> @lang('Mark all as read')
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <button class="btn-icon" aria-label="Notifications">
                <i class="bi bi-bell"></i>
            </button>
        @endauth
        
        {{-- User Profile - Direct link to profile --}}
        @auth
            <div class="dropdown">
                <button class="btn btn-icon user-avatar" type="button" data-bs-toggle="dropdown" aria-label="User menu">
                    @if(auth()->user()->picture)
                        <img src="{{ auth()->user()->picture }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.plan') }}"><i class="bi bi-credit-card me-2"></i>My Plan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-icon user-avatar">
                <div class="avatar-placeholder">
                    <i class="bi bi-person"></i>
                </div>
            </a>
        @endauth
    </div>
</header>