<x-app-layout>
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="lni lni-comments-alt me-2"></i> User Feedback</h5>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.feedback.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-secondary' }}">
                        All ({{ $counts['new'] + $counts['reviewed'] + $counts['resolved'] }})
                    </a>
                    <a href="{{ route('admin.feedback.index', ['status' => 'new']) }}" class="btn btn-sm {{ $status === 'new' ? 'btn-danger' : 'btn-outline-danger' }}">
                        New ({{ $counts['new'] }})
                    </a>
                    <a href="{{ route('admin.feedback.index', ['status' => 'reviewed']) }}" class="btn btn-sm {{ $status === 'reviewed' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Reviewed ({{ $counts['reviewed'] }})
                    </a>
                    <a href="{{ route('admin.feedback.index', ['status' => 'resolved']) }}" class="btn btn-sm {{ $status === 'resolved' ? 'btn-success' : 'btn-outline-success' }}">
                        Resolved ({{ $counts['resolved'] }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-style mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Message</th>
                                <th>Page URL</th>
                                <th>User</th>
                                <th>Screenshot</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedbacks as $feedback)
                                <tr>
                                    <td>{{ $feedback->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.feedback.show', $feedback) }}" class="text-body fw-medium">
                                            {{ Str::limit($feedback->message, 80) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($feedback->page_url)
                                            <a href="{{ $feedback->page_url }}" target="_blank" class="text-muted small" style="max-width:180px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {{ $feedback->page_url }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($feedback->user)
                                            <span class="small">{{ $feedback->user->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">Guest</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($feedback->screenshot_path)
                                            <i class="lni lni-image text-primary" title="Has screenshot" data-coreui-toggle="tooltip"></i>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($feedback->status === 'new')
                                            <span class="badge bg-danger">New</span>
                                        @elseif($feedback->status === 'reviewed')
                                            <span class="badge bg-warning text-dark">Reviewed</span>
                                        @else
                                            <span class="badge bg-success">Resolved</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted text-nowrap">
                                        {{ $feedback->created_at->format('M j, Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.feedback.show', $feedback) }}"
                                            class="btn btn-link text-body" data-coreui-toggle="tooltip" title="View">
                                            <i class="lni lni-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" class="d-inline-block">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-link text-danger warning-delete frm-submit"
                                                data-coreui-toggle="tooltip" title="Delete">
                                                <i class="lni lni-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center skip-last py-4" colspan="8">No feedback submissions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($feedbacks->hasPages())
                    <div class="card-footer">
                        {{ $feedbacks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
