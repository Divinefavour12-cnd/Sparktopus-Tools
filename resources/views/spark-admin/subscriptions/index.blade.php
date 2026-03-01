<x-spark-admin-layout title="Subscriptions">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.25rem;"><i class="bi bi-credit-card-2-front-fill"></i> Subscription Monitoring</h3>
            <form action="{{ route('spark-admin.subscriptions.index') }}" method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search User/Email..." style="height: 40px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px; font-size: 0.875rem;">
                <button type="submit" class="spark-btn spark-btn-primary" style="padding: 0 20px; height: 40px; font-size: 0.875rem;">Search</button>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr style="text-align: left; color: var(--spark-voodoo-text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 10px 20px;">Subscriber</th>
                        <th style="padding: 10px 20px;">Plan</th>
                        <th style="padding: 10px 20px;">Amount</th>
                        <th style="padding: 10px 20px;">Type</th>
                        <th style="padding: 10px 20px;">Status</th>
                        <th style="padding: 10px 20px;">Expiry</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                    <tr style="background: rgba(255,255,255,0.02); transition: background 0.3s;">
                        <td style="padding: 20px; border-radius: 16px 0 0 16px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border); border-left: 1px solid var(--spark-voodoo-border);">
                            <div style="font-weight: 600; font-size: 0.95rem;">{{ $sub->user->name }}</div>
                            <div style="color: var(--spark-voodoo-text-muted); font-size: 0.8rem;">{{ $sub->user->email }}</div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <div style="font-weight: 500;">{{ $sub->plan ? $sub->plan->name : 'N/A' }}</div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <div style="font-weight: 700; color: var(--spark-voodoo-accent);">{{ $sub->currency ?: '$' }}{{ number_format($sub->amount, 2) }}</div>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            <span style="font-size: 0.85rem; color: var(--spark-voodoo-text-muted);">{{ ucfirst($sub->plan_type) }}</span>
                        </td>
                        <td style="padding: 20px; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border);">
                            @if($sub->status == 1)
                                <span style="color: #10b981; font-weight: 700; font-size: 0.8rem;"><i class="bi bi-check-circle-fill"></i> ACTIVE</span>
                            @else
                                <div style="display: flex; gap: 5px; align-items: center;">
                                    <span style="color: #ef4444; font-weight: 700; font-size: 0.8rem; margin-right: 10px;">PENDING</span>
                                    @if($me->hasRole('Super Admin') || $me->hasPermissionTo('billing.manage-plans'))
                                    <form action="{{ route('spark-admin.subscriptions.status', $sub) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button name="status" value="1" class="spark-btn" style="padding: 4px 8px; font-size: 0.7rem; background: rgba(16, 185, 129, 0.1); color: #10b981;">Approve</button>
                                    </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td style="padding: 20px; border-radius: 0 16px 16px 0; border-top: 1px solid var(--spark-voodoo-border); border-bottom: 1px solid var(--spark-voodoo-border); border-right: 1px solid var(--spark-voodoo-border);">
                            <div style="color: var(--spark-voodoo-text-muted); font-size: 0.85rem;">
                                {{ $sub->expiry_date ? $sub->expiry_date->format('M d, Y') : 'N/A' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--spark-voodoo-text-muted);">
                            No subscriptions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            {{ $subscriptions->appends(['q' => $search])->links() }}
        </div>
    </div>
</x-spark-admin-layout>
