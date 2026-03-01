<x-canvas-layout>
    <div class="container-fluid py-4 px-lg-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">@lang('Notifications')</h1>
                <p class="text-muted small mb-0">@lang('Stay updated with your latest activities and system alerts.')</p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('user.notifications.readAll') }}" method="GET">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-check-all me-1"></i> @lang('Mark all as read')
                    </button>
                </form>
            @endif
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item p-3 border-start-0 border-end-0 {{ $notification->read_at ? 'bg-white' : 'bg-light border-start border-primary border-4' }}" id="notification-{{ $notification->id }}">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                @php
                                    $icon = 'bi-info-circle';
                                    $color = 'text-info';
                                    if(isset($notification->data['type'])) {
                                        switch($notification->data['type']) {
                                            case 'success': $icon = 'bi-check-circle-fill'; $color = 'text-success'; break;
                                            case 'warning': $icon = 'bi-exclamation-triangle-fill'; $color = 'text-warning'; break;
                                            case 'danger': $icon = 'bi-x-circle-fill'; $color = 'text-danger'; break;
                                        }
                                    }
                                @endphp
                                <div class="notification-icon {{ $color }} fs-4">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0 fw-bold {{ $notification->read_at ? 'text-dark' : 'text-primary' }}">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2 text-muted small">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <div class="d-flex align-items-center gap-3">
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-link p-0 text-decoration-none small">
                                            @lang('View Details') <i class="bi bi-arrow-right small"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$notification->read_at)
                                        <button class="btn btn-sm btn-link p-0 text-decoration-none text-muted small mark-read-btn" data-id="{{ $notification->id }}">
                                            @lang('Mark as read')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center">
                        <div class="mb-3 text-muted opacity-25">
                            <i class="bi bi-bell-slash" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold">@lang('No notifications')</h5>
                        <p class="text-muted">@lang('You\'re all caught up! Check back later for updates.')</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>

    @push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.mark-read-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const item = document.getElementById(`notification-${id}`);
                    
                    fetch(`{{ url('user/notifications/mark-as-read') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        if(data.success) {
                            item.classList.remove('bg-light', 'border-start', 'border-primary', 'border-4');
                            item.classList.add('bg-white');
                            this.remove();
                            // Update count in header if visible
                            const countBadge = document.getElementById('notification-count');
                            if(countBadge) {
                                let count = parseInt(countBadge.textContent);
                                if(count > 1) {
                                    countBadge.textContent = count - 1;
                                } else {
                                    countBadge.remove();
                                }
                            }
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-canvas-layout>
