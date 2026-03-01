<x-spark-admin-layout title="User Feedback">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-inbox"></i> Feedback Inbox</h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('spark-admin.feedback.index') }}" class="spark-btn {{ !$status ? 'spark-btn-primary' : '' }}" style="background: {{ !$status ? '' : 'rgba(255,255,255,0.05)' }}; color: #fff; text-decoration: none; font-size: 0.875rem;">All</a>
                <a href="{{ route('spark-admin.feedback.index', ['status' => 'new']) }}" class="spark-btn {{ $status == 'new' ? 'spark-btn-primary' : '' }}" style="background: {{ $status == 'new' ? '' : 'rgba(255,255,255,0.05)' }}; color: #fff; text-decoration: none; font-size: 0.875rem;">New</a>
                <a href="{{ route('spark-admin.feedback.index', ['status' => 'reviewed']) }}" class="spark-btn {{ $status == 'reviewed' ? 'spark-btn-primary' : '' }}" style="background: {{ $status == 'reviewed' ? '' : 'rgba(255,255,255,0.05)' }}; color: #fff; text-decoration: none; font-size: 0.875rem;">Reviewed</a>
                <a href="{{ route('spark-admin.feedback.index', ['status' => 'resolved']) }}" class="spark-btn {{ $status == 'resolved' ? 'spark-btn-primary' : '' }}" style="background: {{ $status == 'resolved' ? '' : 'rgba(255,255,255,0.05)' }}; color: #fff; text-decoration: none; font-size: 0.875rem;">Resolved</a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr style="text-align: left; color: var(--spark-voodoo-text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 10px 20px;">User</th>
                        <th style="padding: 10px 20px;">Message</th>
                        <th style="padding: 10px 20px;">Status</th>
                        <th style="padding: 10px 20px;">Date</th>
                        <th style="padding: 10px 20px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                    <tr style="background: rgba(255,255,255,0.02); transition: background 0.3s;">
                        <td style="padding: 20px; border-radius: 16px 0 0 16px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border); border-left: 1px solid var(--spark-voodoo-border);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--spark-voodoo-accent); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">
                                    {{ strtoupper(substr($feedback->user ? $feedback->user->name : 'G', 0, 1)) }}
                                </div>
                                <span style="font-weight: 600; font-size: 0.95rem;">{{ $feedback->user ? $feedback->user->name : 'Guest' }}</span>
                            </div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <div style="color: var(--spark-voodoo-text-muted); font-size: 0.9rem; max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $feedback->message }}
                            </div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <span style="padding: 6px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; background: {{ $feedback->status === 'new' ? 'rgba(239, 68, 68, 0.1)' : ($feedback->status === 'reviewed' ? 'rgba(59, 130, 246, 0.1)' : 'rgba(16, 185, 129, 0.1)') }}; color: {{ $feedback->status === 'new' ? '#ef4444' : ($feedback->status === 'reviewed' ? '#3b82f6' : '#10b981') }};">
                                {{ $feedback->status }}
                            </span>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <div style="color: var(--spark-voodoo-text-muted); font-size: 0.85rem;">
                                {{ $feedback->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td style="padding: 20px; text-align: right; border-radius: 0 16px 16px 0; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border); border-right: 1px solid var(--spark-voodoo-border);">
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                <a href="{{ route('spark-admin.feedback.show', $feedback) }}" class="spark-btn" style="padding: 8px; background: rgba(255,255,255,0.05); color: #fff;"><i class="bi bi-eye"></i></a>
                                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('feedback.delete'))
                                <form action="{{ route('spark-admin.feedback.destroy', $feedback) }}" method="POST" onsubmit="return confirm('Delete this feedback?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="spark-btn" style="padding: 8px; background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: var(--spark-voodoo-text-muted);">
                            No feedback submissions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $feedbacks->links() }}
        </div>
    </div>
</x-spark-admin-layout>
