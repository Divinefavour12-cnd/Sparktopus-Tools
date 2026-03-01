<x-app-layout>
    <div class="row mb-3">
        <div class="col-md-12 d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="lni lni-comments-alt me-2"></i> Feedback #{{ $feedback->id }}</h5>
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="lni lni-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Main content --}}
        <div class="col-lg-8">
            {{-- Message --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Feedback Message</h6></div>
                <div class="card-body">
                    <p style="font-size:15px;line-height:1.7;white-space:pre-wrap;">{{ $feedback->message }}</p>
                </div>
            </div>

            {{-- Screenshot --}}
            @if($feedback->screenshot_url)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Screenshot</h6>
                        <a href="{{ $feedback->screenshot_url }}" download="feedback-screenshot.png" class="btn btn-sm btn-outline-primary">
                            <i class="lni lni-download me-1"></i> Download
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <img src="{{ $feedback->screenshot_url }}" alt="Feedback Screenshot"
                             class="img-fluid w-100" style="border-radius:0 0 .375rem .375rem;max-height:600px;object-fit:contain;background:#f8f9fa;">
                    </div>
                </div>
            @else
                <div class="card mb-3">
                    <div class="card-body text-center text-muted py-4">
                        <i class="lni lni-image d-block mb-2" style="font-size:2rem;"></i>
                        No screenshot was captured for this feedback.
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar: metadata + status --}}
        <div class="col-lg-4">
            {{-- Status Update --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Status</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        @if($feedback->status === 'new')
                            <span class="badge bg-danger fs-6">● New</span>
                        @elseif($feedback->status === 'reviewed')
                            <span class="badge bg-warning text-dark fs-6">● Reviewed</span>
                        @else
                            <span class="badge bg-success fs-6">● Resolved</span>
                        @endif
                    </div>
                    <form action="{{ route('admin.feedback.status', $feedback) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="new" {{ $feedback->status === 'new' ? 'selected' : '' }}>New</option>
                                <option value="reviewed" {{ $feedback->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            {{-- Details --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Details</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Submitted by</div>
                        @if($feedback->user)
                            <div class="fw-medium">{{ $feedback->user->name }}</div>
                            <div class="small text-muted">{{ $feedback->user->email }}</div>
                        @else
                            <span class="badge bg-secondary">Guest</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Date</div>
                        <div>{{ $feedback->created_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div>
                        <div class="small text-muted mb-1">Page URL</div>
                        @if($feedback->page_url)
                            <a href="{{ $feedback->page_url }}" target="_blank" class="small text-break">
                                {{ $feedback->page_url }}
                            </a>
                        @else
                            <span class="text-muted small">Not recorded</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Delete --}}
            <div class="card border-danger">
                <div class="card-body">
                    <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 warning-delete frm-submit">
                            <i class="lni lni-trash me-1"></i> Delete Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
