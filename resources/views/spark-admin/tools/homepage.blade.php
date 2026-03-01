<x-spark-admin-layout title="Homepage Selector">
    <div class="spark-card" style="max-width: 800px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
                <i class="bi bi-house-star-fill"></i>
            </div>
            <h1 style="margin: 0; font-size: 1.75rem; font-weight: 800;">Featured Tool</h1>
            <p style="color: var(--spark-voodoo-text-muted); margin-top: 10px;">Select which tool should be displayed as the main feature on your homepage landing page.</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            @foreach($tools as $tool)
            <div class="spark-card" style="padding: 20px; display: flex; align-items: center; justify-content: space-between; background: {{ $tool->is_home ? 'rgba(59, 130, 246, 0.05)' : 'rgba(255,255,255,0.02)' }}; border-color: {{ $tool->is_home ? '#3b82f6' : 'var(--spark-voodoo-border)' }};">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                         <i class="{{ $tool->icon_type == 'class' ? $tool->icon_class : 'bi bi-tools' }}"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700;">{{ $tool->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--spark-voodoo-text-muted);">/{{ $tool->slug }}</div>
                    </div>
                </div>

                @if($tool->is_home)
                    <div style="display: flex; align-items: center; gap: 10px; color: #10b981; font-weight: 800; font-size: 0.85rem;">
                        <i class="bi bi-patch-check-fill" style="font-size: 1.1rem;"></i> CURRENTLY FEATURED
                    </div>
                @else
                    <form action="{{ route('spark-admin.tools.set-home', $tool) }}" method="POST">
                        @csrf
                        <button type="submit" class="spark-btn" style="background: rgba(255,255,255,0.05); color: #fff; font-size: 0.85rem; padding: 8px 20px;">Select Feature</button>
                    </form>
                @endif
            </div>
            @endforeach
        </div>

        <div style="margin-top: 40px; text-align: center;">
            <a href="{{ route('spark-admin.tools.index') }}" style="color: var(--spark-voodoo-text-muted); text-decoration: none; font-size: 0.9rem;"><i class="bi bi-arrow-left"></i> Back to Tools List</a>
        </div>
    </div>
</x-spark-admin-layout>
