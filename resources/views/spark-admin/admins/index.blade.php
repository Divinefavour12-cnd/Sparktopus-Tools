<x-spark-admin-layout title="Admin Operations Hierarchy">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
            <div>
                <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-person-workspace"></i> Staff & Operators</h3>
                <p style="margin: 5px 0 0; color: var(--spark-text-muted); font-size: 0.85rem;">Manage access for administrative personnel.</p>
            </div>
            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
            <button onclick="document.getElementById('create-admin-modal').style.display = 'flex'" class="spark-btn spark-btn-primary" style="padding: 10px 25px;">
                <i class="bi bi-person-plus-fill"></i> Add New Staff
            </button>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr style="text-align: left; color: var(--spark-text-muted); font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 10px 20px;">Staff Member</th>
                        <th style="padding: 10px 20px;">Assigned Role</th>
                        <th style="padding: 10px 20px;">Email Identity</th>
                        <th style="padding: 10px 20px; text-align: right;">Master Controls</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr style="background: rgba(var(--spark-accent-rgb), 0.02); transition: all 0.3s ease; border-radius: 16px;">
                        <td style="padding: 20px; border-radius: 16px 0 0 16px; border-left: 1px solid var(--spark-border); border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 40px; height: 40px; border-radius: 12px; background: var(--spark-accent); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div style="font-weight: 700; color: var(--spark-text);">{{ $admin->name }}</div>
                            </div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            @php $role = $admin->roles->first(); @endphp
                            @if($role)
                                <span style="padding: 6px 14px; border-radius: 12px; background: rgba(var(--spark-accent-rgb), 0.1); color: var(--spark-accent); font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(var(--spark-accent-rgb), 0.2);">
                                    {{ $role->name }}
                                </span>
                            @else
                                <span style="padding: 6px 14px; border-radius: 12px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(239,68,68,0.2);">
                                    NO ROLE ASSIGNED
                                </span>
                            @endif
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border); color: var(--spark-text-muted); font-size: 0.85rem;">
                            {{ $admin->email }}
                        </td>
                        <td style="padding: 20px; text-align: right; border-radius: 0 16px 16px 0; border-right: 1px solid var(--spark-border); border-top: 1px solid var(--spark-border); border-bottom: 1px solid var(--spark-border);">
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
                                <button onclick="editAdminRole({{ $admin->id }}, '{{ $admin->name }}', {{ $role ? $role->id : 'null' }})" class="action-btn" style="background: rgba(var(--spark-accent-rgb), 0.1); color: var(--spark-accent);">
                                    <i class="bi bi-shield-check"></i>
                                </button>
                                @endif
                                
                                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('role.manage'))
                                    @if($admin->id !== auth('admin')->id())
                                        <form action="{{ route('spark-admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('SYSTEM PURGE: Remove staff member permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Admin Modal -->
    <div id="create-admin-modal" class="spark-modal-overlay" style="display: none;">
        <div class="spark-modal-content" style="max-width: 500px;">
            <div class="spark-modal-header">
                <h3>Authorize New Staff Identity</h3>
                <button onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="close-modal">&times;</button>
            </div>
            <form action="{{ route('spark-admin.admins.store') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label class="modal-label">Full Legal Name</label>
                        <input type="text" name="name" required placeholder="Master John Doe" class="spark-input">
                    </div>
                    <div>
                        <label class="modal-label">Email Access</label>
                        <input type="email" name="email" required placeholder="admin@sparktopus.com" class="spark-input">
                    </div>
                    <div>
                        <label class="modal-label">Temporary Access Key (Password)</label>
                        <input type="password" name="password" required placeholder="••••••••" class="spark-input">
                    </div>
                    <div>
                        <label class="modal-label">Security Role Clearance</label>
                        <select name="role_id" required class="spark-input" style="height: 50px;">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="margin-top: 35px; display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="spark-btn" style="background: rgba(255,255,255,0.05);">Dismiss</button>
                    <button type="submit" class="spark-btn spark-btn-primary">Provision Account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="edit-admin-role-modal" class="spark-modal-overlay" style="display: none;">
        <div class="spark-modal-content" style="max-width: 450px;">
            <div class="spark-modal-header">
                <h3 id="edit-admin-name">Update Clearance</h3>
                <button onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="close-modal">&times;</button>
            </div>
            <form id="edit-admin-role-form" action="" method="POST">
                @csrf
                <div>
                    <label class="modal-label">Assign New Operational Role</label>
                    <select name="role_id" id="edit-admin-role-select" required class="spark-input" style="height: 50px; width: 100%;">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="spark-btn" style="background: rgba(255,255,255,0.05);">Cancel</button>
                    <button type="submit" class="spark-btn spark-btn-primary">Update Privileges</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal-label { display: block; margin-bottom: 10px; font-weight: 700; color: var(--spark-text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .action-btn { width: 40px; height: 40px; border-radius: 12px; border: none; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; }
        .action-btn:hover { transform: scale(1.1); filter: brightness(1.2); }
    </style>

    <script>
        function editAdminRole(id, name, roleId) {
            const modal = document.getElementById('edit-admin-role-modal');
            const form = document.getElementById('edit-admin-role-form');
            const select = document.getElementById('edit-admin-role-select');
            
            document.getElementById('edit-admin-name').innerText = 'Clearance: ' + name;
            let updateUrl = '{{ route("spark-admin.admins.update", ":id") }}';
            form.action = updateUrl.replace(':id', id);
            select.value = roleId;
            
            modal.style.display = 'flex';
        }
    </script>
</x-spark-admin-layout>
