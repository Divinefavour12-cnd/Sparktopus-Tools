<x-spark-admin-layout title="Tool Management">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-tools"></i> Installed Tools</h3>
            <div style="display: flex; gap: 15px;">
                 @if($me->hasRole('Super Admin') || $me->hasPermissionTo('tool.manage'))
                 <a href="{{ route('spark-admin.tools.homepage') }}" class="spark-btn" style="text-decoration: none; font-size: 0.875rem; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-house-gear-fill"></i> Homepage Selector
                </a>
                 @endif
                 <form action="{{ route('spark-admin.tools.index') }}" method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search tool name..." style="height: 40px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px; font-size: 0.875rem;">
                </form>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @forelse($tools as $tool)
            <div class="spark-card" style="padding: 24px; display: flex; flex-direction: column; background: {{ $tool->status ? 'rgba(255,255,255,0.02)' : 'rgba(239, 68, 68, 0.02)' }}; border-color: {{ $tool->status ? 'var(--spark-voodoo-border)' : 'rgba(239, 68, 68, 0.1)' }};">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div style="width: 50px; height: 50px; border-radius: 14px; background: {{ $tool->status ? 'rgba(139, 92, 246, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $tool->status ? 'var(--spark-voodoo-accent)' : '#ef4444' }}; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="{{ $tool->icon_type == 'class' ? $tool->icon_class : 'bi bi-tools' }}"></i>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end;">
                         <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: {{ $tool->status ? '#10b981' : '#ef4444' }}; margin-bottom: 4px;">{{ $tool->status ? 'Active' : 'Inactive' }}</span>
                         @if($tool->is_home)
                            <span style="font-size: 0.65rem; background: var(--spark-voodoo-accent); color: #fff; padding: 2px 6px; border-radius: 5px; font-weight: 800;">HOMEPAGE</span>
                         @endif
                    </div>
                </div>

                <h4 style="margin: 0 0 5px 0; font-size: 1.1rem; font-weight: 700;">{{ $tool->name }}</h4>
                <p style="color: var(--spark-voodoo-text-muted); font-size: 0.8rem; margin: 0 0 20px 0;">/{{ $tool->slug }}</p>

                <div style="display: flex; gap: 10px; margin-bottom: 20px; font-size: 0.85rem; color: var(--spark-voodoo-text-muted);">
                    <div style="display: flex; align-items: center; gap: 5px;"><i class="bi bi-eye"></i> {{ number_format($tool->views_count) }}</div>
                    <div style="display: flex; align-items: center; gap: 5px;"><i class="bi bi-folder2"></i> {{ $tool->category->first()->name ?? 'N/A' }}</div>
                </div>

                <div style="margin-top: auto; display: flex; gap: 10px;">
                    @if($me->hasRole('Super Admin') || $me->hasPermissionTo('tool.edit'))
                    <a href="{{ route('spark-admin.tools.edit', $tool) }}" class="spark-btn" style="flex: 1; padding: 10px; background: rgba(255,255,255,0.05); color: #fff; font-size: 0.8rem; text-decoration: none; text-align: center;">Settings</a>
                    <a href="{{ route('spark-admin.tools.status', $tool) }}" class="spark-btn" style="flex: 1; padding: 10px; background: {{ $tool->status ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)' }}; color: {{ $tool->status ? '#ef4444' : '#10b981' }}; font-size: 0.8rem; text-decoration: none; text-align: center;">
                        {{ $tool->status ? 'Disable' : 'Enable' }}
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; padding: 60px; text-align: center; color: var(--spark-voodoo-text-muted);">
                No tools match your criteria.
            </div>
            @endforelse
        </div>

        <div style="margin-top: 40px;">
            {{ $tools->appends(['q' => $search])->links() }}
        </div>
    </div>
</x-spark-admin-layout>
