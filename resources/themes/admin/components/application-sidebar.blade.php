<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <a href="{{ route('admin.dashboard') }}">
            <x-application-logo class="sidebar-brand-full" width="202" height="36"
                alt="{{ config('app.name', 'Sparktopus Tools') }}" />
            <x-application-logo-small class="sidebar-brand-narrow" width="36" height="36"
                alt="{{ config('app.name', 'Sparktopus Tools') }}" />
        </a>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        @foreach ($menu as $key => $item)
        <li class="{{ $item['submenus']->count() > 0 ? 'nav-group' : 'nav-item' }}">
            <a href="{{ $item['url'] }}" id="{{ $key }}"
                class="nav-link{{ $item['submenus']->count() > 0 ? ' nav-group-toggle' : '' }}"> <i
                    class="{{ $item['icon'] }}"></i> {{ $item['name'] }}
                @if (!empty($item['badge']))
                <span
                    class="badge bg-{{ $item['badgeClass'] ?? 'primary' }} ms-auto">{{ $item['badge'] }}</span>
                @endif
            </a>
            @if ($item['submenus']->count())
            <ul class="nav-group-items">
                @foreach ($item['submenus'] as $subkey => $submenu)
                <li class="nav-item">
                    <a href="{{ $submenu['url'] }}" id="{{ $subkey }}" class="nav-link"> <i
                            class="{{ $submenu['icon'] ?? 'nav-icon lni lni-chevron-right' }}"></i>
                        {{ $submenu['name'] }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
        <li class="nav-item">
            <a href="{{ route('admin.subscriptions') }}" class="nav-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                <i class="nav-icon lni lni-credit-cards"></i> Subscriptions
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.updates') }}" class="nav-link {{ request()->routeIs('admin.updates*') ? 'active' : '' }}">
                <i class="nav-icon lni lni-bolt"></i> Updates
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.feedback.index') }}" class="nav-link {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">
                <i class="nav-icon lni lni-comments-alt"></i> Feedback
                @php $newFeedbackCount = \App\Models\Feedback::where('status','new')->count(); @endphp
                @if($newFeedbackCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $newFeedbackCount }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="nav-icon lni lni-exit"></i>@lang('auth.signout')</a></li>
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>