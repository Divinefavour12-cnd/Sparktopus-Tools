<x-spark-admin-layout :title="'Master User: ' . $user->name">
    <div style="display: grid; grid-template-columns: 350px 1fr; gap: 30px; align-items: start;">
        
        {{-- Profile Sidebar --}}
        <div style="display: flex; flex-direction: column; gap: 30px;">
            <div class="spark-card" style="text-align: center; padding: 40px 30px;">
                <div style="width: 100px; height: 100px; border-radius: 30px; background: linear-gradient(135deg, var(--spark-accent) 0%, #a78bfa 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 2.5rem; color: #fff; margin: 0 auto 20px; box-shadow: 0 15px 30px var(--spark-accent-glow);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800;">{{ $user->name }}</h2>
                <p style="color: var(--spark-text-muted); margin: 5px 0 20px 0;">{{ $user->email }}</p>
                
                <div style="display: flex; justify-content: center; gap: 12px; margin-bottom: 30px;">
                    @if($user->status == 1)
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; border: 1px solid rgba(16, 185, 129, 0.2);">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span> ACTIVE
                        </span>
                    @elseif($user->status == 0)
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; border: 1px solid rgba(245, 158, 11, 0.2);">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; box-shadow: 0 0 10px #f59e0b;"></span> SUSPENDED
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 12px; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; border: 1px solid rgba(239, 68, 68, 0.2);">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 10px #ef4444;"></span> BANNED
                        </span>
                    @endif
                    <span style="padding: 8px 16px; border-radius: 12px; background: var(--spark-surface); border: 1px solid var(--spark-border); color: var(--spark-accent); font-size: 0.7rem; font-weight: 900; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 1px solid rgba(var(--spark-accent-rgb), 0.2);">{{ strtoupper($user->planLevel()) }}</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; text-align: left; padding-top: 25px; border-top: 1px solid var(--spark-border);">
                    <div>
                        <div style="font-size: 0.75rem; color: var(--spark-text-muted); text-transform: uppercase; font-weight: 700;">Credits</div>
                        <div style="font-size: 1.1rem; font-weight: 800;">{{ number_format($user->credits) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: var(--spark-text-muted); text-transform: uppercase; font-weight: 700;">Joined</div>
                        <div style="font-size: 0.9rem; font-weight: 700;">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
                
                <div style="margin-top: 30px; text-align: left;">
                    <div style="font-size: 0.7rem; color: var(--spark-text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 12px; opacity: 0.7;">Quick Authorization</div>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <form action="{{ route('spark-admin.users.reset-usage', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="spark-btn-action" style="width: 100%; border: none; padding: 14px 18px; border-radius: 16px; background: rgba(var(--spark-accent-rgb), 0.1); color: var(--spark-accent); font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.3s ease;">
                                <i class="bi bi-arrow-clockwise-badge" style="font-size: 1.1rem;"></i> Reset Activity
                            </button>
                        </form>
                        @if($user->status == 1)
                            <form action="{{ route('spark-admin.users.suspend', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="spark-btn-action" style="width: 100%; border: none; padding: 14px 18px; border-radius: 16px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="bi bi-pause-circle-fill" style="font-size: 1.1rem;"></i> Suspend Identity
                                </button>
                            </form>
                        @else
                            <form action="{{ route('spark-admin.users.unsuspend', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="spark-btn-action" style="width: 100%; border: none; padding: 14px 18px; border-radius: 16px; background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="bi bi-play-circle-fill" style="font-size: 1.1rem;"></i> Restore Identity
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="spark-card" style="padding: 25px;">
                <h3 style="margin: 0 0 20px 0; font-size: 1rem; font-weight: 800;">System Info</h3>
                <div style="display: flex; flex-direction: column; gap: 15px; font-size: 0.85rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--spark-text-muted);">Last Login:</span>
                        <span style="font-weight: 700;">{{ $user->last_login_at ? $user->last_login_at->format('M d, H:i') : 'Never' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--spark-text-muted);">Email Verified:</span>
                        <span style="font-weight: 700; color: {{ $user->email_verified_at ? '#10b981' : '#ef4444' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content - Activity & History --}}
        <div style="display: flex; flex-direction: column; gap: 30px;">
            
            {{-- Tool Usage breakdown --}}
            <div class="spark-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">
                        <i class="bi bi-activity" style="color: var(--spark-accent);"></i> Tool Usage Breakdown
                    </h3>
                </div>
                
                @if($usages->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
                        @foreach($usages as $usage)
                            <div class="usage-card" style="padding: 20px; background: rgba(var(--spark-accent-rgb), 0.04); border: 1px solid var(--spark-border); border-radius: 20px; transition: all 0.3s ease;">
                                <div style="font-weight: 800; margin-bottom: 12px; color: var(--spark-text); font-size: 0.95rem; letter-spacing: -0.01em;">{{ str_replace('-', ' ', ucwords($usage->tool_name, '-')) }}</div>
                                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                    <div>
                                        <div style="font-size: 1.5rem; font-weight: 900; color: var(--spark-accent);">{{ $usage->usage_count }}</div>
                                        <div style="font-size: 0.7rem; color: var(--spark-text-muted); text-transform: uppercase; font-weight: 8s00; letter-spacing: 0.05em;">Total Hits</div>
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--spark-text-muted); text-align: right; line-height: 1.4;">
                                        <div style="font-weight: 700; color: var(--spark-text);">{{ \Carbon\Carbon::parse($usage->last_used_at)->format('H:i') }}</div>
                                        <div style="opacity: 0.7;">{{ \Carbon\Carbon::parse($usage->last_used_at)->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: var(--spark-text-muted);">
                        <i class="bi bi-box-seam" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                        No activity recorded yet.
                    </div>
                @endif
            </div>

            {{-- Transactions --}}
            <div class="spark-card">
                <h3 style="margin: 0 0 25px 0; font-size: 1.15rem; font-weight: 800;">
                    <i class="bi bi-credit-card-2-back-fill" style="color: var(--spark-accent);"></i> Payment & Subscription History
                </h3>
                
                @if($transactions->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                            <thead>
                                <tr style="text-align: left; color: var(--spark-text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <th style="padding: 10px;">Plan</th>
                                    <th style="padding: 10px;">Transaction ID</th>
                                    <th style="padding: 10px;">Amount</th>
                                    <th style="padding: 10px;">Status</th>
                                    <th style="padding: 10px;">Date</th>
                                    <th style="padding: 10px;">Expiry</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr class="transaction-row" style="background: rgba(var(--spark-accent-rgb), 0.03); font-size: 0.9rem; transition: all 0.3s ease;">
                                    <td style="padding: 20px; border-radius: 16px 0 0 16px;">
                                        <span style="font-weight: 800; color: var(--spark-accent); letter-spacing: 0.02em;">{{ strtoupper($transaction->plan->name ?? 'N/A') }}</span>
                                    </td>
                                    <td style="padding: 20px; font-family: monospace; font-size: 0.8rem; opacity: 0.8;">{{ $transaction->transaction_id }}</td>
                                    <td style="padding: 20px; font-weight: 800; font-size: 1rem;">{{ $transaction->amount }} <span style="font-size: 0.7rem; opacity: 0.7;">{{ $transaction->currency }}</span></td>
                                    <td style="padding: 20px;">
                                        <span style="padding: 6px 14px; border-radius: 10px; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.05em; background: {{ $transaction->status === 'active' ? 'rgba(16, 185, 129, 0.15)' : 'rgba(255,255,255,0.08)' }}; color: {{ $transaction->status === 'active' ? '#10b981' : 'var(--spark-text-muted)' }}; border: 1px solid {{ $transaction->status === 'active' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(255,255,255,0.1)' }}">
                                            {{ strtoupper($transaction->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 20px; color: var(--spark-text-muted); font-size: 0.8rem; font-weight: 600;">{{ $transaction->created_at->format('M d, Y') }}</td>
                                    <td style="padding: 20px; border-radius: 0 16px 16px 0; font-weight: 800; font-size: 0.85rem; color: {{ \Carbon\Carbon::parse($transaction->expiry_date)->isPast() ? '#ef4444' : 'var(--spark-text)' }}">
                                        {{ \Carbon\Carbon::parse($transaction->expiry_date)->format('M d, Y') }}
                                        @if(\Carbon\Carbon::parse($transaction->expiry_date)->isPast())
                                            <div style="font-size: 0.65rem; color: #ef4444; opacity: 0.8;">EXPIRED</div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: var(--spark-text-muted);">
                        <i class="bi bi-wallet2" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                        No payment history found.
                    </div>
                @endif
            </div>

        </div>
    </div>
    <style>
        .spark-btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            filter: brightness(1.1);
        }
        .usage-card:hover {
            background: rgba(var(--spark-accent-rgb), 0.08) !important;
            border-color: var(--spark-accent) !important;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(var(--spark-accent-rgb), 0.1);
        }
        .transaction-row:hover {
            background: rgba(var(--spark-accent-rgb), 0.06) !important;
        }
    </style>
</x-spark-admin-layout>
