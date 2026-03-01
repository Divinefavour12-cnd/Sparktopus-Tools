<x-spark-admin-layout title="User Master Suite">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h3 style="margin: 0; font-size: 1.25rem; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-people-fill" style="color: var(--spark-accent);"></i> Registered Users
            </h3>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <form action="{{ route('spark-admin.users.index') }}" method="GET" style="display: flex; gap: 10px;">
                    <select name="status" onchange="this.form.submit()" style="height: 42px; background: rgba(var(--spark-accent), 0.05); border: 1px solid var(--spark-border); border-radius: 12px; color: var(--spark-text); padding: 0 12px; font-size: 0.85rem; outline: none;">
                        <option value="">All Statuses</option>
                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>Suspended</option>
                        <option value="2" {{ $status == '2' ? 'selected' : '' }}>Banned</option>
                    </select>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search identity..." style="height: 42px; background: rgba(var(--spark-accent), 0.03); border: 1px solid var(--spark-border); border-radius: 12px; color: var(--spark-text); padding: 0 15px; font-size: 0.875rem; min-width: 250px; outline: none;">
                    <button type="submit" class="spark-btn spark-btn-primary" style="height: 42px; padding: 0 20px;">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div style="overflow-x: auto; margin: 0 -10px;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 15px;">
                <thead>
                    <tr style="text-align: left; color: var(--spark-text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.12em; font-weight: 800; opacity: 0.8;">
                        <th style="padding: 10px 25px;">User Identity</th>
                        <th style="padding: 10px 25px;">Account Status</th>
                        <th style="padding: 10px 25px;">Subscription</th>
                        <th style="padding: 10px 25px;">Usage/Credits</th>
                        <th style="padding: 10px 25px;">Last Activity</th>
                        <th style="padding: 10px 25px; text-align: right;">Master Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="user-row" style="background: rgba(var(--spark-accent-rgb), 0.03); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 20px;">
                        <td style="padding: 25px; border-radius: 20px 0 0 20px; border-left: 1px solid var(--spark-border); border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <div style="display: flex; align-items: center; gap: 18px;">
                                <div style="width: 50px; height: 50px; border-radius: 16px; background: linear-gradient(135deg, var(--spark-accent) 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; color: #fff; box-shadow: 0 8px 16px var(--spark-accent-glow); transition: transform 0.3s ease;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 1.05rem; color: var(--spark-text); letter-spacing: -0.01em;">{{ $user->name }}</div>
                                    <div style="color: var(--spark-text-muted); font-size: 0.85rem; margin-top: 2px;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 25px; border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            @if($user->status == 1)
                                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: #10b981; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; border: 1px solid rgba(16, 185, 129, 0.2);">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span> ACTIVE
                                </span>
                            @elseif($user->status == 0)
                                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; border: 1px solid rgba(245, 158, 11, 0.2);">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; box-shadow: 0 0 10px #f59e0b;"></span> SUSPENDED
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; border: 1px solid rgba(239, 68, 68, 0.2);">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 10px #ef4444;"></span> BANNED
                                </span>
                            @endif
                        </td>
                        <td style="padding: 25px; border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <span style="padding: 8px 14px; border-radius: 12px; background: var(--spark-surface); border: 1px solid var(--spark-border); color: var(--spark-accent); font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                {{ $user->planLevel() }}
                            </span>
                        </td>
                        <td style="padding: 25px; border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <div style="font-weight: 800; font-size: 1rem; color: var(--spark-text);">{{ number_format($user->credits) }} <span style="font-size: 0.7rem; color: var(--spark-text-muted); font-weight: 600;">CREDITS</span></div>
                        </td>
                        <td style="padding: 25px; border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <div style="color: var(--spark-text-muted); font-size: 0.85rem; line-height: 1.4;">
                                <div style="color: var(--spark-text); font-weight: 700;">{{ $user->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--spark-text-muted); display: flex; align-items: center; gap: 5px;">
                                    <i class="bi bi-clock-history" style="color: var(--spark-accent);"></i> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </div>
                            </div>
                        </td>
                        <td style="padding: 25px; text-align: right; border-radius: 0 20px 20px 0; border-right: 1px solid var(--spark-border); border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                <a href="{{ route('spark-admin.users.show', $user) }}" class="master-action-btn" style="width: 42px; height: 42px; border-radius: 12px; background: rgba(var(--spark-accent-rgb), 0.1); color: var(--spark-accent); display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                
                                <div style="position: relative; display: inline-block;" class="action-dropdown">
                                    <button class="master-action-btn" style="width: 42px; height: 42px; border-radius: 12px; background: rgba(255,255,255,0.05); color: var(--spark-text); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;" onclick="toggleDropdown(this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; width: 240px; background: var(--spark-surface); border: 1px solid var(--spark-border); border-radius: 20px; box-shadow: 0 15px 50px rgba(0,0,0,0.4); z-index: 100; padding: 12px; margin-top: 12px; text-align: left; backdrop-filter: blur(20px);">
                                        <p style="margin: 8px 12px; font-size: 0.7rem; font-weight: 900; color: var(--spark-text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Master Control</p>
                                        
                                        @if($me->hasRole('Super Admin') || $me->hasPermissionTo('user.reset-password'))
                                        <form action="{{ route('spark-admin.users.reset-usage', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="spark-dropdown-item">
                                                <i class="bi bi-arrow-clockwise"></i> Reset Daily Usage
                                            </button>
                                        </form>
                                        @endif

                                        @if($me->hasRole('Super Admin') || $me->hasPermissionTo('user.suspend'))
                                            @if($user->status == 1)
                                                <form action="{{ route('spark-admin.users.suspend', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="spark-dropdown-item" style="color: #f59e0b;">
                                                        <i class="bi bi-pause-fill"></i> Suspend Account
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('spark-admin.users.unsuspend', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="spark-dropdown-item" style="color: #10b981;">
                                                        <i class="bi bi-play-fill"></i> Unsuspend Account
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        @if($me->hasRole('Super Admin') || $me->hasPermissionTo('billing.manage-plans'))
                                            <div style="height: 1px; background: var(--spark-border); margin: 12px 0;"></div>
                                            <p style="margin: 8px 12px; font-size: 0.7rem; font-weight: 900; color: var(--spark-text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Plan Authorization</p>
                                            
                                            <form action="{{ route('spark-admin.users.upgrade', [$user, 2]) }}" method="POST"> @csrf <button type="submit" class="spark-dropdown-item"><i class="bi bi-star"></i> Classic Plan</button></form>
                                            <form action="{{ route('spark-admin.users.upgrade', [$user, 3]) }}" method="POST"> @csrf <button type="submit" class="spark-dropdown-item"><i class="bi bi-star-fill"></i> Plus Plan</button></form>
                                            <form action="{{ route('spark-admin.users.upgrade', [$user, 4]) }}" method="POST"> @csrf <button type="submit" class="spark-dropdown-item"><i class="bi bi-shield-check"></i> Pro Plan</button></form>
                                            <form action="{{ route('spark-admin.users.upgrade', [$user, 1]) }}" method="POST"> @csrf <button type="submit" class="spark-dropdown-item" style="color: #ef4444;"><i class="bi bi-arrow-down-circle"></i> Strip to Free</button></form>
                                        @endif

                                        @if($me->hasRole('Super Admin') || $me->hasPermissionTo('user.delete'))
                                            <div style="height: 1px; background: var(--spark-border); margin: 12px 0;"></div>
                                            
                                            <form action="{{ route('spark-admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('DANGER: Permanent system removal?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="spark-dropdown-item" style="color: #ef4444; background: rgba(239, 68, 68, 0.05);">
                                                    <i class="bi bi-trash3-fill"></i> System Purge
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 60px; text-align: center; color: var(--spark-text-muted);">
                            <i class="bi bi-person-x" style="font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 20px;"></i>
                            No users match your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $users->appends(request()->all())->links() }}
        </div>
    </div>

    <style>
        .user-row:hover {
            background: rgba(var(--spark-accent-rgb), 0.06) !important;
            transform: scale(1.005);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .user-row:hover .master-action-btn {
            background: rgba(var(--spark-accent-rgb), 0.2);
        }
        .spark-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 15px;
            border: none;
            background: transparent;
            color: var(--spark-text);
            font-size: 0.85rem;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.2s;
        }
        .spark-dropdown-item:hover {
            background: rgba(var(--spark-accent-rgb), 0.1);
            transform: translateX(3px);
        }
        .spark-dropdown-item i {
            font-size: 1.1rem;
            opacity: 0.7;
        }
    </style>

    <script>
        function toggleDropdown(btn) {
            // Close all others
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== btn.nextElementSibling) menu.style.display = 'none';
            });
            
            const menu = btn.nextElementSibling;
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        // Close on outside click
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.action-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');
            }
        });
    </script>
</x-spark-admin-layout>
