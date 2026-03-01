@php
    $allPermissions = \App\Models\Permission::all()->groupBy('group');

    // Color-coded role styling config
    $roleConfig = [
        'Super Admin'       => ['icon' => 'bi-shield-shaded',    'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.08)',   'border' => 'rgba(239,68,68,0.2)',   'badge' => 'FOUNDER',          'type' => 'Internal', 'max' => '1 Max'],
        'Admin'             => ['icon' => 'bi-person-fill-gear',  'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.08)',  'border' => 'rgba(139,92,246,0.2)',  'badge' => 'OPERATIONS',       'type' => 'Internal', 'max' => null],
        'Moderator'         => ['icon' => 'bi-eye-fill',          'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.08)',  'border' => 'rgba(245,158,11,0.2)',  'badge' => 'CONTENT SAFETY',   'type' => 'Internal', 'max' => null],
        'Support'           => ['icon' => 'bi-headset',           'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.08)',  'border' => 'rgba(16,185,129,0.2)',  'badge' => 'CUSTOMER SUCCESS', 'type' => 'Internal', 'max' => null],
        'Finance Admin'     => ['icon' => 'bi-bank2',             'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,0.08)',  'border' => 'rgba(59,130,246,0.2)',  'badge' => 'FINANCE',          'type' => 'Internal', 'max' => null],
        'Developer'         => ['icon' => 'bi-code-slash',        'color' => '#6366f1', 'bg' => 'rgba(99,102,241,0.08)',  'border' => 'rgba(99,102,241,0.2)',  'badge' => 'ENGINEERING',      'type' => 'Internal', 'max' => null],
        'User (Free)'       => ['icon' => 'bi-person',            'color' => '#9ca3af', 'bg' => 'rgba(156,163,175,0.06)', 'border' => 'rgba(156,163,175,0.15)','badge' => 'FREE TIER',        'type' => 'External', 'max' => null],
        'User (Paid)'       => ['icon' => 'bi-star-fill',         'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.06)', 'border' => 'rgba(245,158,11,0.15)', 'badge' => 'SUBSCRIBER',       'type' => 'External', 'max' => null],
        'Enterprise Owner'  => ['icon' => 'bi-building',          'color' => '#0ea5e9', 'bg' => 'rgba(14,165,233,0.06)', 'border' => 'rgba(14,165,233,0.15)', 'badge' => 'ORG OWNER',        'type' => 'External', 'max' => null],
        'Enterprise Member' => ['icon' => 'bi-people-fill',       'color' => '#64748b', 'bg' => 'rgba(100,116,139,0.06)','border' => 'rgba(100,116,139,0.15)','badge' => 'TEAM MEMBER',      'type' => 'External', 'max' => null],
    ];

    // Fallback config
    $defaultConfig = ['icon' => 'bi-shield-check', 'color' => 'var(--spark-accent)', 'bg' => 'rgba(var(--spark-accent-rgb),0.05)', 'border' => 'rgba(var(--spark-accent-rgb),0.15)', 'badge' => 'CUSTOM', 'type' => 'Custom', 'max' => null];
@endphp

<x-spark-admin-layout title="Role Hierarchy">
    @php $me = auth('admin')->user(); @endphp
    {{-- ── Header ──────────────────────────────── --}}
    <div class="spark-card" style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800;"><i class="bi bi-shield-lock-fill"></i> Enterprise Role Hierarchy</h3>
                <p style="margin: 6px 0 0; color: var(--spark-text-muted); font-size: 0.85rem;">{{ $roles->count() }} roles configured across internal and external tiers.</p>
            </div>
            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
            <button onclick="document.getElementById('create-role-modal').style.display = 'flex'" class="spark-btn spark-btn-primary" style="padding: 10px 25px;">
                <i class="bi bi-plus-lg"></i> New Role
            </button>
            @endif
        </div>
    </div>

    {{-- ── Internal Roles Section ──────────────── --}}
    @php $internalRoles = $roles->filter(fn($r) => ($roleConfig[$r->name]['type'] ?? 'Internal') === 'Internal'); @endphp
    @if($internalRoles->count())
        <div style="margin-bottom: 15px;">
            <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--spark-text-muted); opacity: 0.6; padding-left: 5px;"><i class="bi bi-lock-fill"></i> Internal Team Roles</h4>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px; margin-bottom: 40px;">
            @foreach($internalRoles as $role)
                @php $cfg = $roleConfig[$role->name] ?? $defaultConfig; @endphp
                <div class="role-card" style="background: var(--spark-glass); border: 1px solid var(--spark-border); border-radius: 22px; padding: 24px; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); transition: all 0.35s cubic-bezier(0.4,0,0.2,1);">
                    {{-- Decorative accent line --}}
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: {{ $cfg['color'] }}; border-radius: 22px 22px 0 0;"></div>

                    {{-- Top row: icon + name + actions --}}
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: {{ $cfg['bg'] }}; border: 1px solid {{ $cfg['border'] }}; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: {{ $cfg['color'] }}; flex-shrink: 0;">
                                <i class="bi {{ $cfg['icon'] }}"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--spark-text);">{{ $role->name }}</h4>
                                <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                    <span style="font-size: 0.65rem; padding: 2px 8px; border-radius: 6px; background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }}; font-weight: 800; letter-spacing: 0.08em;">{{ $cfg['badge'] }}</span>
                                    @if($cfg['max'])
                                        <span style="font-size: 0.6rem; padding: 2px 6px; border-radius: 5px; background: rgba(239,68,68,0.1); color: #ef4444; font-weight: 700;">{{ $cfg['max'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
                            <button onclick="editRole({{ $role->id }}, '{{ $role->name }}', {{ $role->permissions->pluck('id') }})" class="role-action-btn" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            @endif

                            @if(($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage')) && $role->name !== 'Super Admin')
                                <form action="{{ route('spark-admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Permanently delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="role-action-btn role-action-delete" title="Delete">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($role->description)
                        <p style="margin: 0 0 16px; font-size: 0.8rem; color: var(--spark-text-muted); line-height: 1.5;">{{ $role->description }}</p>
                    @endif

                    {{-- Permission pills --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @forelse($role->permissions->take(6) as $perm)
                            <span class="perm-pill" style="background: {{ $cfg['bg'] }}; border: 1px solid {{ $cfg['border'] }}; color: {{ $cfg['color'] }};">
                                {{ $perm->name }}
                            </span>
                        @empty
                            <span style="color: var(--spark-text-muted); font-size: 0.75rem; font-style: italic;">No permissions assigned.</span>
                        @endforelse
                        @if($role->permissions_count > 6)
                            <span style="font-size: 0.65rem; padding: 4px 10px; color: var(--spark-text-muted); font-weight: 600;">+ {{ $role->permissions_count - 6 }} more</span>
                        @endif
                    </div>

                    {{-- Footer: capability count --}}
                    <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--spark-border); display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.7rem; color: var(--spark-text-muted); font-weight: 600;">
                            <i class="bi bi-key-fill" style="opacity: 0.5;"></i> {{ $role->permissions_count }} capabilities
                        </span>
                        <span style="font-size: 0.65rem; padding: 3px 10px; border-radius: 8px; background: rgba(var(--spark-accent-rgb), 0.06); color: var(--spark-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">
                            {{ $cfg['type'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── External Roles Section ──────────────── --}}
    @php $externalRoles = $roles->filter(fn($r) => ($roleConfig[$r->name]['type'] ?? 'Internal') === 'External'); @endphp
    @if($externalRoles->count())
        <div style="margin-bottom: 15px;">
            <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--spark-text-muted); opacity: 0.6; padding-left: 5px;"><i class="bi bi-globe"></i> External User Roles</h4>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px; margin-bottom: 40px;">
            @foreach($externalRoles as $role)
                @php $cfg = $roleConfig[$role->name] ?? $defaultConfig; @endphp
                <div class="role-card" style="background: var(--spark-glass); border: 1px solid var(--spark-border); border-radius: 22px; padding: 24px; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); transition: all 0.35s cubic-bezier(0.4,0,0.2,1);">
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: {{ $cfg['color'] }}; border-radius: 22px 22px 0 0;"></div>

                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: {{ $cfg['bg'] }}; border: 1px solid {{ $cfg['border'] }}; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: {{ $cfg['color'] }}; flex-shrink: 0;">
                                <i class="bi {{ $cfg['icon'] }}"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--spark-text);">{{ $role->name }}</h4>
                                <span style="font-size: 0.65rem; padding: 2px 8px; border-radius: 6px; background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }}; font-weight: 800; letter-spacing: 0.08em; margin-top: 4px; display: inline-block;">{{ $cfg['badge'] }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
                                @if(!in_array($role->name, ['User (Free)', 'User (Paid)']))
                                    <form action="{{ route('spark-admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Permanently delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="role-action-btn role-action-delete" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($role->description)
                        <p style="margin: 0; font-size: 0.8rem; color: var(--spark-text-muted); line-height: 1.5;">{{ $role->description }}</p>
                    @endif

                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--spark-border); display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.7rem; color: var(--spark-text-muted); font-weight: 600;">
                            <i class="bi bi-globe2" style="opacity: 0.5;"></i> Frontend role
                        </span>
                        <span style="font-size: 0.65rem; padding: 3px 10px; border-radius: 8px; background: rgba(16,185,129,0.06); color: #10b981; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">
                            {{ $cfg['type'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Custom Roles Section ──────────────── --}}
    @php $customRoles = $roles->filter(fn($r) => !isset($roleConfig[$r->name]) && ($roleConfig[$r->name]['type'] ?? 'Custom') === 'Custom'); @endphp
    @if($customRoles->count())
        <div style="margin-bottom: 15px;">
            <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--spark-text-muted); opacity: 0.6; padding-left: 5px;"><i class="bi bi-gear-wide-connected"></i> Custom Roles</h4>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px; margin-bottom: 40px;">
            @foreach($customRoles as $role)
                @php $cfg = $defaultConfig; @endphp
                <div class="role-card" style="background: var(--spark-glass); border: 1px solid var(--spark-border); border-radius: 22px; padding: 24px; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); transition: all 0.35s cubic-bezier(0.4,0,0.2,1);">
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--spark-accent); border-radius: 22px 22px 0 0;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(var(--spark-accent-rgb), 0.08); border: 1px solid rgba(var(--spark-accent-rgb), 0.2); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--spark-accent); flex-shrink: 0;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--spark-text);">{{ $role->name }}</h4>
                                <span style="font-size: 0.65rem; padding: 2px 8px; border-radius: 6px; background: rgba(var(--spark-accent-rgb), 0.08); color: var(--spark-accent); font-weight: 800; letter-spacing: 0.08em; margin-top: 4px; display: inline-block;">CUSTOM</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
                            <button onclick="editRole({{ $role->id }}, '{{ $role->name }}', {{ $role->permissions->pluck('id') }})" class="role-action-btn" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('spark-admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Permanently delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="role-action-btn role-action-delete" title="Delete">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @if($role->description)
                        <p style="margin: 0 0 16px; font-size: 0.8rem; color: var(--spark-text-muted); line-height: 1.5;">{{ $role->description }}</p>
                    @endif
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @forelse($role->permissions->take(6) as $perm)
                            <span class="perm-pill">{{ $perm->name }}</span>
                        @empty
                            <span style="color: var(--spark-text-muted); font-size: 0.75rem; font-style: italic;">No permissions assigned.</span>
                        @endforelse
                        @if($role->permissions_count > 6)
                            <span style="font-size: 0.65rem; padding: 4px 10px; color: var(--spark-text-muted); font-weight: 600;">+ {{ $role->permissions_count - 6 }} more</span>
                        @endif
                    </div>
                    <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--spark-border); display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.7rem; color: var(--spark-text-muted); font-weight: 600;">
                            <i class="bi bi-key-fill" style="opacity: 0.5;"></i> {{ $role->permissions_count }} capabilities
                        </span>
                        <span style="font-size: 0.65rem; padding: 3px 10px; border-radius: 8px; background: rgba(var(--spark-accent-rgb), 0.06); color: var(--spark-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">Custom</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ══════════════════════════════════════════ --}}
    {{-- MODALS --}}
    {{-- ══════════════════════════════════════════ --}}

    {{-- Create Role Modal --}}
    <div id="create-role-modal" class="spark-modal-overlay" style="display: none;">
        <div class="spark-modal-content" style="max-width: 500px;">
            <div class="spark-modal-header">
                <h3>Create New Role</h3>
                <button onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="close-modal">&times;</button>
            </div>
            <form action="{{ route('spark-admin.roles.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--spark-text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Role Name</label>
                    <input type="text" name="name" required placeholder="e.g. SEO Manager" class="spark-input" style="width: 100%;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="spark-btn" style="background: rgba(255,255,255,0.05);">Cancel</button>
                    <button type="submit" class="spark-btn spark-btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Permissions Modal --}}
    <div id="edit-permissions-modal" class="spark-modal-overlay" style="display: none;">
        <div class="spark-modal-content" style="max-width: 800px;">
            <div class="spark-modal-header">
                <h3 id="edit-role-title">Edit Role Permissions</h3>
                <button onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="close-modal">&times;</button>
            </div>
            <form id="edit-permissions-form" action="" method="POST">
                @csrf
                <div style="max-height: 500px; overflow-y: auto; padding-right: 15px;">
                    @foreach($allPermissions as $group => $perms)
                        <div style="margin-bottom: 25px;">
                            <h5 style="margin: 0 0 12px; color: var(--spark-accent); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; font-weight: 900; border-bottom: 1px solid var(--spark-border); padding-bottom: 8px;">{{ $group }}</h5>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                @foreach($perms as $perm)
                                    <label class="perm-checkbox-wrap">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="perm-checkbox">
                                        <span>{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid var(--spark-border); padding-top: 20px;">
                    <button type="button" onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="spark-btn" style="background: rgba(255,255,255,0.05);">Cancel</button>
                    <button type="submit" class="spark-btn spark-btn-primary">Save Permissions</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- STYLES --}}
    {{-- ══════════════════════════════════════════ --}}
    <style>
        .role-card:hover {
            transform: translateY(-4px);
            border-color: rgba(var(--spark-accent-rgb), 0.3);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        .role-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--spark-border);
            color: var(--spark-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 0.8rem;
        }
        .role-action-btn:hover {
            background: var(--spark-accent);
            color: #fff;
            border-color: var(--spark-accent);
            transform: scale(1.08);
        }
        .role-action-delete:hover {
            background: #ef4444 !important;
            border-color: #ef4444 !important;
        }
        .perm-pill {
            font-size: 0.6rem;
            padding: 4px 10px;
            border-radius: 8px;
            background: rgba(var(--spark-accent-rgb), 0.06);
            color: var(--spark-text-muted);
            border: 1px solid rgba(var(--spark-accent-rgb), 0.08);
            font-weight: 600;
            font-family: 'Outfit', monospace;
            letter-spacing: 0.02em;
        }
        .perm-checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--spark-border);
            background: rgba(255,255,255,0.02);
            transition: all 0.2s ease;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--spark-text);
        }
        .perm-checkbox-wrap:hover {
            border-color: var(--spark-accent);
            background: rgba(var(--spark-accent-rgb), 0.04);
        }
        .perm-checkbox {
            width: 17px;
            height: 17px;
            accent-color: var(--spark-accent);
            flex-shrink: 0;
        }
    </style>

    {{-- ══════════════════════════════════════════ --}}
    {{-- SCRIPTS --}}
    {{-- ══════════════════════════════════════════ --}}
    <script>
        function editRole(id, name, permissions) {
            const modal = document.getElementById('edit-permissions-modal');
            const form = document.getElementById('edit-permissions-form');
            const title = document.getElementById('edit-role-title');

            title.innerText = 'Manage: ' + name;
            let updateUrl = '{{ route("spark-admin.roles.update", ":id") }}';
            form.action = updateUrl.replace(':id', id);

            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);

            permissions.forEach(pid => {
                const cb = document.querySelector(`.perm-checkbox[value="${pid}"]`);
                if (cb) cb.checked = true;
            });

            modal.style.display = 'flex';
        }
    </script>
</x-spark-admin-layout>
