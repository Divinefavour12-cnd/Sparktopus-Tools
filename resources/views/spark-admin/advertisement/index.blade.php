<x-spark-admin-layout title="Ads Management">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-cash-stack"></i> Revenue Slots</h3>
            <div style="display: flex; gap: 15px;">
                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('ad.create'))
                <div class="dropdown" style="position: relative; display: inline-block;">
                    <button class="spark-btn spark-btn-primary" style="font-size: 0.875rem;" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display == 'none' ? 'block' : 'none'">
                        <i class="bi bi-plus-lg"></i> New Ad Slot
                    </button>
                    <div style="display: none; position: absolute; right: 0; top: 100%; background: var(--spark-voodoo-surface); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; z-index: 100; min-width: 180px; margin-top: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <a href="{{ route('spark-admin.advertisement.create', 1) }}" style="display: block; padding: 12px 20px; color: #fff; text-decoration: none; font-size: 0.85rem; border-bottom: 1px solid var(--spark-voodoo-border);">Google AdSense</a>
                        <a href="{{ route('spark-admin.advertisement.create', 2) }}" style="display: block; padding: 12px 20px; color: #fff; text-decoration: none; font-size: 0.85rem; border-bottom: 1px solid var(--spark-voodoo-border);">Custom Image</a>
                        <a href="{{ route('spark-admin.advertisement.create', 3) }}" style="display: block; padding: 12px 20px; color: #fff; text-decoration: none; font-size: 0.85rem;">Custom Script</a>
                    </div>
                </div>
                @endif
                 <form action="{{ route('spark-admin.advertisement.index') }}" method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search ads..." style="height: 40px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px; font-size: 0.875rem;">
                </form>
            </div>
        </div>

        {{-- Slot Mapping Section --}}
        <div style="background: rgba(96, 0, 194, 0.05); border: 1px solid rgba(96, 0, 194, 0.2); border-radius: 16px; padding: 20px; margin-bottom: 30px;">
            <h4 style="margin: 0 0 15px 0; font-size: 1rem; color: var(--spark-voodoo-accent);"><i class="bi bi-gear-fill me-2"></i> Slot Assignments</h4>
            <form action="{{ route('spark-admin.settings.update') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; align-items: flex-end;">
                @csrf
                <div class="input-group">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.8rem; color: var(--spark-voodoo-text-muted);">Pop-up Ad Slot (Global)</label>
                    <select name="settings[popup]" style="width: 100%; height: 40px; background: rgba(0,0,0,0.2); border: 1px solid var(--spark-voodoo-border); border-radius: 8px; color: #fff; padding: 0 10px;">
                        <option value="">-- None --</option>
                        @foreach($advertisements as $ad)
                            <option value="{{ $ad->id }}" {{ setting('popup') == $ad->id ? 'selected' : '' }}>{{ $ad->title }} (#{{ $ad->id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.8rem; color: var(--spark-voodoo-text-muted);">Horizontal Tool Slot (Above Area)</label>
                    <select name="settings[above-tool]" style="width: 100%; height: 40px; background: rgba(0,0,0,0.2); border: 1px solid var(--spark-voodoo-border); border-radius: 8px; color: #fff; padding: 0 10px;">
                        <option value="">-- None --</option>
                        @foreach($advertisements as $ad)
                            <option value="{{ $ad->id }}" {{ setting('above-tool') == $ad->id ? 'selected' : '' }}>{{ $ad->title }} (#{{ $ad->id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <button type="submit" class="spark-btn spark-btn-primary" style="height: 40px; padding: 0 25px; font-size: 0.85rem;">Save Assignments</button>
                </div>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr style="text-align: left; color: var(--spark-voodoo-text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 10px 20px;">Slot Name</th>
                        <th style="padding: 10px 20px;">Type</th>
                        <th style="padding: 10px 20px;">Display Area</th>
                        <th style="padding: 10px 20px;">Status</th>
                        <th style="padding: 10px 20px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advertisements as $ad)
                    <tr style="background: rgba(255,255,255,0.02); transition: background 0.3s;">
                        <td style="padding: 20px; border-radius: 16px 0 0 16px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border); border-left: 1px solid var(--spark-voodoo-border);">
                            <div style="font-weight: 600; font-size: 0.95rem;">{{ $ad->title }}</div>
                            <div style="color: var(--spark-voodoo-text-muted); font-size: 0.8rem;">ID: #{{ $ad->id }}</div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            @php
                                $types = [1 => 'Google Ads', 2 => 'Custom Content', 3 => 'Raw Script'];
                                $hasVideo = ($ad->type == 2 && !empty($ad->options['video']));
                                $hasImage = ($ad->type == 2 && !empty($ad->options['image']));
                            @endphp
                            <span style="font-size: 0.85rem; color: #fff; background: rgba(255,255,255,0.05); padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px;">
                                {{ $types[$ad->type] ?? 'Unknown' }}
                                @if($hasVideo) <i class="bi bi-camera-video-fill text-info" title="Video Content"></i> @endif
                                @if($hasImage) <i class="bi bi-image text-success" title="Image Content"></i> @endif
                            </span>
                            @if($ad->type == 2 && !empty($ad->options['countdown']))
                                <div style="font-size: 0.7rem; color: var(--spark-voodoo-text-muted); margin-top: 4px;">Timer: {{ $ad->options['countdown'] }}s</div>
                            @endif
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <div style="font-weight: 500; font-size: 0.85rem; color: var(--spark-voodoo-accent);">{{ $ad->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--spark-voodoo-text-muted); margin-top: 2px;">
                                @if($ad->name == 'popup') 
                                    <i class="bi bi-info-circle me-1"></i> Global/Tool Pop-up
                                @elseif($ad->name == 'above-tool')
                                    <i class="bi bi-info-circle me-1"></i> Above tool editors
                                @else
                                    <i class="bi bi-info-circle me-1"></i> Theme Slot
                                @endif
                            </div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            @if($me->hasRole('Super Admin') || $me->hasPermissionTo('ad.edit'))
                                <a href="{{ route('spark-admin.advertisement.status', $ad) }}" style="text-decoration: none;">
                                    <span style="color: {{ $ad->status ? '#10b981' : '#ef4444' }}; font-weight: 700; font-size: 0.8rem;">
                                        <i class="bi bi-circle-fill" style="font-size: 0.6rem; margin-right: 5px;"></i> {{ $ad->status ? 'ACTIVE' : 'DISABLED' }}
                                    </span>
                                </a>
                            @else
                                <span style="color: {{ $ad->status ? '#10b981' : '#ef4444' }}; font-weight: 700; font-size: 0.8rem; opacity: 0.7;">
                                    <i class="bi bi-circle-fill" style="font-size: 0.6rem; margin-right: 5px;"></i> {{ $ad->status ? 'ACTIVE' : 'DISABLED' }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 20px; text-align: right; border-radius: 0 16px 16px 0; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border); border-right: 1px solid var(--spark-voodoo-border);">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('ad.edit'))
                                <a href="{{ route('spark-admin.advertisement.edit', $ad) }}" class="spark-btn" style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: #fff; font-size: 0.85rem; text-decoration: none;">Edit</a>
                                @endif

                                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('ad.delete'))
                                <form action="{{ route('spark-admin.advertisement.destroy', $ad) }}" method="POST" onsubmit="return confirm('Delete this ad slot?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="spark-btn" style="padding: 8px 16px; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 0.85rem;">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: var(--spark-voodoo-text-muted);">
                            No advertisement slots configured.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $advertisements->appends(['q' => $search])->links() }}
        </div>
    </div>
</x-spark-admin-layout>
