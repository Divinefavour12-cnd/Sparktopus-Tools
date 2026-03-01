<x-app-layout>
    <div class="row">
        <!-- Stat Cards -->
        <div class="col-sm-6 col-lg-3">
            <div class="card mb-4 text-white bg-primary">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fs-4 fw-semibold">{{ $total_users }} <span class="fs-6 fw-normal"></span></div>
                        <div>Total Users</div>
                    </div>
                    <i class="lni lni-users icon icon-lg"></i>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;"></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card mb-4 text-white bg-info">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fs-4 fw-semibold">{{ $total_tools }}</div>
                        <div>Tool Collection</div>
                    </div>
                    <i class="lni lni-cog icon icon-lg"></i>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;"></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card mb-4 text-white bg-warning">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fs-4 fw-semibold">{{ $total_feedback }}</div>
                        <div>User Feedback</div>
                    </div>
                    <i class="lni lni-comments-alt icon icon-lg"></i>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;"></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card mb-4 text-white bg-danger">
                <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fs-4 fw-semibold">{{ ($subscription_breakdown['classic'] ?? 0) + ($subscription_breakdown['plus'] ?? 0) + ($subscription_breakdown['pro'] ?? 0) }}</div>
                        <div>Paid Subscriptions</div>
                    </div>
                    <i class="lni lni-credit-cards icon icon-lg"></i>
                </div>
                <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tool Analytics -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="lni lni-stats-up me-2"></i> Tool Analytics
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h6 class="text-center mb-3">🔥 Most Used Tools</h6>
                            <ul class="list-group list-group-flush">
                                @forelse($most_used_tools as $usage)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ str_replace('-', ' ', ucwords($usage->tool_name, '-')) }}
                                        <span class="badge bg-primary rounded-pill">{{ $usage->total_usage }} uses</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">No usage data found.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-center mb-3">👀 Most Viewed Tools</h6>
                            <ul class="list-group list-group-flush">
                                @forelse($most_viewed_tools as $view)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $view->tool_name }}
                                        <span class="badge bg-info rounded-pill">{{ $view->view_count }} views</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">No view data found.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Breakdown -->
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    <i class="lni lni-graph me-2"></i> User Plan Breakdown
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="text-medium-emphasis small">Free</div>
                            <div class="fs-5 fw-semibold">{{ $subscription_breakdown['free'] ?? 0 }}</div>
                        </div>
                        <div class="col-3 border-start">
                            <div class="text-medium-emphasis small">Classic</div>
                            <div class="fs-5 fw-semibold">{{ $subscription_breakdown['classic'] ?? 0 }}</div>
                        </div>
                        <div class="col-3 border-start">
                            <div class="text-medium-emphasis small">Plus</div>
                            <div class="fs-5 fw-semibold">{{ $subscription_breakdown['plus'] ?? 0 }}</div>
                        </div>
                        <div class="col-3 border-start">
                            <div class="text-medium-emphasis small">Pro</div>
                            <div class="fs-5 fw-semibold">{{ $subscription_breakdown['pro'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Feedback -->
        <div class="col-md-4">
            <div class="card mb-4" style="max-height: 500px; overflow-y: auto;">
                <div class="card-header fw-bold sticky-top bg-white d-flex justify-content-between align-items-center">
                    <span><i class="lni lni-comment-reply me-2"></i> Recent Feedback</span>
                    <a href="{{ route('admin.feedback.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recent_feedback as $feedback)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">{{ $feedback->user ? $feedback->user->name : 'Guest' }}</h6>
                                    <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-truncate small">{{ $feedback->message }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-{{ $feedback->status == 'new' ? 'danger' : ($feedback->status == 'reviewed' ? 'warning' : 'success') }} small">
                                        {{ ucfirst($feedback->status) }}
                                    </span>
                                    <a href="{{ route('admin.feedback.show', $feedback) }}" class="small">Detail</a>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">No feedback yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
