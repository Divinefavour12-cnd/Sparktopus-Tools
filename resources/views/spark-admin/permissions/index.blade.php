<x-spark-admin-layout title="Capability Control">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
            <div>
                <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-key-fill"></i> System Capabilities</h3>
                <p style="margin: 5px 0 0; color: var(--spark-text-muted); font-size: 0.85rem;">Define granular permissions for administrative actions.</p>
            </div>
            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('permission.manage'))
            <button onclick="document.getElementById('create-perm-modal').style.display = 'flex'" class="spark-btn spark-btn-primary" style="padding: 10px 25px;">
                <i class="bi bi-key"></i> Define New Capability
            </button>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
            @foreach($permissions as $group => $groupPerms)
                <div class="permission-group-card" style="background: rgba(var(--spark-accent-rgb), 0.015); border: 1px solid var(--spark-border); border-radius: 20px; overflow: hidden;">
                    <div style="background: rgba(var(--spark-accent-rgb), 0.05); padding: 15px 20px; border-bottom: 1px solid var(--spark-border); display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; color: var(--spark-accent);">{{ $group }}</h4>
                        <span style="font-size: 0.75rem; color: var(--spark-text-muted); font-weight: 700;">{{ count($groupPerms) }} Actions</span>
                    </div>
                    <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px;">
                        @foreach($groupPerms as $perm)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                                <span style="font-size: 0.9rem; font-weight: 600; color: var(--spark-text);">{{ $perm->name }}</span>
                                <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 0.85rem; opacity: 0.5;"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Permission Modal -->
    <div id="create-perm-modal" class="spark-modal-overlay" style="display: none;">
        <div class="spark-modal-content" style="max-width: 500px;">
            <div class="spark-modal-header">
                <h3>Define System Capability</h3>
                <button onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="close-modal">&times;</button>
            </div>
            <form action="{{ route('spark-admin.permissions.store') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 700; color: var(--spark-text-muted); font-size: 0.75rem;">Capability Name (slug)</label>
                        <input type="text" name="name" required placeholder="e.g. manage-api-keys" class="spark-input" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 700; color: var(--spark-text-muted); font-size: 0.75rem;">Module Grouping</label>
                        <select name="group" required class="spark-input" style="width: 100%; height: 50px;">
                            <option value="users">Users</option>
                            <option value="roles">Roles & Permissions</option>
                            <option value="content">Content</option>
                            <option value="billing">Billing / Finance</option>
                            <option value="system">System</option>
                            <option value="analytics">Analytics</option>
                            <option value="tools">Tools</option>
                            <option value="ads">Advertisements</option>
                            <option value="feedback">Feedback</option>
                            <option value="developer">Developer</option>
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="this.closest('.spark-modal-overlay').style.display = 'none'" class="spark-btn" style="background: rgba(255,255,255,0.05);">Cancel</button>
                    <button type="submit" class="spark-btn spark-btn-primary">Register Capability</button>
                </div>
            </form>
        </div>
    </div>
</x-spark-admin-layout>
