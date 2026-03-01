<x-spark-admin-layout title="Feedback Detail">
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Message & Info -->
        <section>
            <div class="spark-card">
                <h3 style="margin-top: 0; margin-bottom: 25px; font-size: 1.25rem;"><i class="bi bi-card-checklist"></i> Submission Details</h3>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; color: var(--spark-voodoo-text-muted); font-size: 0.8rem; margin-bottom: 5px;">Sender</label>
                    <div style="font-weight: 600; font-size: 1rem;">{{ $feedback->user ? $feedback->user->name : 'Guest' }}</div>
                    <div style="color: var(--spark-voodoo-text-muted); font-size: 0.85rem;">{{ $feedback->user ? $feedback->user->email : 'N/A' }}</div>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; color: var(--spark-voodoo-text-muted); font-size: 0.8rem; margin-bottom: 5px;">Created</label>
                    <div style="font-weight: 500;">{{ $feedback->created_at->format('M d, Y @ H:i') }}</div>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; color: var(--spark-voodoo-text-muted); font-size: 0.8rem; margin-bottom: 5px;">Page URL</label>
                    <a href="{{ $feedback->page_url }}" target="_blank" style="color: var(--spark-voodoo-accent); text-decoration: none; font-size: 0.9rem; word-break: break-all;">{{ $feedback->page_url }}</a>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; color: var(--spark-voodoo-text-muted); font-size: 0.8rem; margin-bottom: 5px;">Message</label>
                    <div style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 16px; border: 1px solid var(--spark-voodoo-border); line-height: 1.6;">
                        {{ $feedback->message }}
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--spark-voodoo-border); margin: 30px 0;">

                <form action="{{ route('spark-admin.feedback.status', $feedback) }}" method="POST">
                    @csrf
                    <label style="display: block; color: var(--spark-voodoo-text-muted); font-size: 0.8rem; margin-bottom: 15px;">Update Status</label>
                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        <button name="status" value="new" class="spark-btn {{ $feedback->status === 'new' ? 'spark-btn-primary' : '' }}" style="flex: 1; font-size: 0.8rem; background: {{ $feedback->status === 'new' ? '' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $feedback->status === 'new' ? '#fff' : '#ef4444' }};">New</button>
                        <button name="status" value="reviewed" class="spark-btn {{ $feedback->status === 'reviewed' ? 'spark-btn-primary' : '' }}" style="flex: 1; font-size: 0.8rem; background: {{ $feedback->status === 'reviewed' ? '' : 'rgba(59, 130, 246, 0.1)' }}; color: {{ $feedback->status === 'reviewed' ? '#fff' : '#3b82f6' }};">Reviewed</button>
                        <button name="status" value="resolved" class="spark-btn {{ $feedback->status === 'resolved' ? 'spark-btn-primary' : '' }}" style="flex: 1; font-size: 0.8rem; background: {{ $feedback->status === 'resolved' ? '' : 'rgba(16, 185, 129, 0.1)' }}; color: {{ $feedback->status === 'resolved' ? '#fff' : '#10b981' }};">Resolved</button>
                    </div>
                </form>

                <a href="{{ route('spark-admin.feedback.index') }}" class="spark-btn" style="width: 100%; display: block; text-align: center; text-decoration: none; background: rgba(255,255,255,0.05); color: #fff;">Back to List</a>
            </div>
        </section>

        <!-- Screenshot Display -->
        <section>
            <div class="spark-card" style="height: 100%;">
                <h3 style="margin-top: 0; margin-bottom: 25px; font-size: 1.25rem;"><i class="bi bi-camera"></i> Visual Proof</h3>
                @if($feedback->screenshot_path)
                    <div style="background: #000; border-radius: 16px; overflow: hidden; border: 1px solid var(--spark-voodoo-border);">
                        <img src="{{ $feedback->screenshot_url }}" alt="Feedback Screenshot" style="width: 100%; display: block;">
                    </div>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="{{ $feedback->screenshot_url }}" target="_blank" class="spark-btn" style="text-decoration: none; color: var(--spark-voodoo-accent); font-size: 0.9rem;"><i class="bi bi-box-arrow-up-right"></i> Open Full Image</a>
                    </div>
                @else
                    <div style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px dashed var(--spark-voodoo-border); color: var(--spark-voodoo-text-muted);">
                        <i class="bi bi-image" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        No screenshot was attached to this submission.
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-spark-admin-layout>
