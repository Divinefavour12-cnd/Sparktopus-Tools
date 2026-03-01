{{-- Mobile Bottom Navigation --}}
<nav class="mobile-bottom-nav">
    <ul class="nav">
        <li class="nav-item">
            <a href="{{ route('front.index') }}" class="nav-link {{ request()->routeIs('front.index') ? 'active' : '' }}">
                <i class="bi bi-house-door{{ request()->routeIs('front.index') ? '-fill' : '' }}"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('front.tools') }}" class="nav-link {{ request()->routeIs('front.tools') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap{{ request()->routeIs('front.tools') ? '-fill' : '' }}"></i>
                <span>Tools</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('plans.list') }}" class="nav-link">
                <i class="bi bi-lightning-charge-fill"></i>
                <span>Upgrade</span>
            </a>
        </li>
        <li class="nav-item">
            @auth
                <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <i class="bi bi-person{{ request()->routeIs('user.profile') ? '-fill' : '' }}"></i>
                    <span>Profile</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="bi bi-person"></i>
                    <span>Login</span>
                </a>
            @endauth
        </li>
    </ul>
</nav>