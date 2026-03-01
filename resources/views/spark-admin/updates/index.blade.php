<x-spark-admin-layout title="System Updates">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-lightning-charge-fill"></i> Managed Updates</h3>
            <div style="display: flex; gap: 15px;">
                 <form action="{{ route('spark-admin.updates.index') }}" method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search title..." style="height: 40px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px; font-size: 0.875rem;">
                </form>
                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('content.create'))
                <a href="{{ route('spark-admin.updates.create') }}" class="spark-btn spark-btn-primary" style="text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-plus-lg"></i> New Update
                </a>
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @forelse($posts as $post)
            <div class="spark-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; background: rgba(255,255,255,0.015);">
                 @if($post->getFirstMediaUrl('featured-image'))
                    <div style="height: 180px; width: 100%; overflow: hidden;">
                        <img src="{{ $post->getFirstMediaUrl('featured-image') }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">
                    </div>
                 @else
                    <div style="height: 180px; width: 100%; background: linear-gradient(135deg, #1e1b4b 0%, #121216 100%); display: flex; align-items: center; justify-content: center; color: var(--spark-voodoo-accent);">
                        <i class="bi bi-rocket-takeoff" style="font-size: 3rem;"></i>
                    </div>
                 @endif

                 <div style="padding: 24px; flex-grow: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: {{ $post->status == 1 ? '#10b981' : '#f59e0b' }};">
                            {{ $post->status == 1 ? 'Published' : 'Draft' }}
                        </span>
                        <span style="font-size: 0.75rem; color: var(--spark-voodoo-text-muted);">{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <h4 style="margin: 0 0 10px 0; font-size: 1.1rem; font-weight: 700; line-height: 1.4;">{{ $post->title }}</h4>
                    <p style="color: var(--spark-voodoo-text-muted); font-size: 0.875rem; margin-bottom: 20px;">By {{ $post->author ? $post->author->name : 'Admin' }}</p>
                 </div>

                 <div style="padding: 15px 24px; background: rgba(255,255,255,0.02); border-top: 1px solid var(--spark-voodoo-border); display: flex; justify-content: flex-end; gap: 10px;">
                     @if($me->hasRole('Super Admin') || $me->hasPermissionTo('content.edit'))
                     <a href="{{ route('spark-admin.updates.edit', $post) }}" class="spark-btn" style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: #fff; font-size: 0.8rem; text-decoration: none;">Edit</a>
                     @endif
                     
                     @if($me->hasRole('Super Admin') || $me->hasPermissionTo('content.delete'))
                     <form action="{{ route('spark-admin.updates.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this update?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="spark-btn" style="padding: 8px 16px; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 0.8rem;">Delete</button>
                     </form>
                     @endif
                 </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; padding: 60px; text-align: center; color: var(--spark-voodoo-text-muted);">
                <i class="bi bi-lightning-charge" style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
                No updates have been posted yet. Start by creating the first one!
            </div>
            @endforelse
        </div>

        <div style="margin-top: 40px;">
            {{ $posts->appends(['q' => $search])->links() }}
        </div>
    </div>
</x-spark-admin-layout>
