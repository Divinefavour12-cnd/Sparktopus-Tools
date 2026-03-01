<x-spark-admin-layout title="Overview">
    @php $me = auth('admin')->user(); @endphp
    
    {{-- ── Global Stats ──────────────────────────────── --}}
    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div class="spark-card" style="position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; font-size: 5rem; opacity: 0.05; transform: rotate(15deg); color: #3b82f6;">
                <i class="bi bi-people"></i>
            </div>
            <div class="spark-stat-card">
                <div class="spark-icon-box" style="color: #3b82f6; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">{{ number_format($total_users) }}</div>
                    <div style="color: var(--spark-text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Total Users</div>
                </div>
            </div>
        </div>

        @if($finance_stats)
        <div class="spark-card" style="position: relative; overflow: hidden; border: 1px solid rgba(16, 185, 129, 0.2); background: linear-gradient(145deg, rgba(16, 185, 129, 0.05), transparent);">
            <div style="position: absolute; top: -20px; right: -20px; font-size: 5rem; opacity: 0.1; transform: rotate(15deg); color: #10b981;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="spark-stat-card">
                <div class="spark-icon-box" style="color: #10b981; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">${{ number_format($finance_stats['monthly_revenue'], 2) }}</div>
                    <div style="color: var(--spark-text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Monthly Revenue</div>
                </div>
            </div>
        </div>
        @else
        <div class="spark-card" style="position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; font-size: 5rem; opacity: 0.05; transform: rotate(15deg); color: #10b981;">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="spark-stat-card">
                <div class="spark-icon-box" style="color: #10b981; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    @if($me->hasRole('Super Admin') || $me->hasPermissionTo('billing.view-revenue'))
                        <div style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">{{ $subscription_breakdown->sum() }}</div>
                        <div style="color: var(--spark-text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Active Subs</div>
                    @else
                        <div style="font-size: 1.25rem; font-weight: 800; letter-spacing: -0.02em; color: var(--spark-text-muted); opacity: 0.5;">LOCKED</div>
                        <div style="color: var(--spark-text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Subscription Data</div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="spark-card" style="position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; font-size: 5rem; opacity: 0.05; transform: rotate(15deg); color: #f59e0b;">
                <i class="bi bi-tools"></i>
            </div>
            <div class="spark-stat-card">
                <div class="spark-icon-box" style="color: #f59e0b; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2);">
                    <i class="bi bi-hammer"></i>
                </div>
                <div>
                    <div style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">{{ number_format($total_tools) }}</div>
                    <div style="color: var(--spark-text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Live Tools</div>
                </div>
            </div>
        </div>

        <div class="spark-card" style="position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; font-size: 5rem; opacity: 0.05; transform: rotate(15deg); color: #ef4444;">
                <i class="bi bi-chat-heart"></i>
            </div>
            <div class="spark-stat-card">
                <div class="spark-icon-box" style="color: #ef4444; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);">
                    <i class="bi bi-chat-heart-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">{{ number_format($total_feedback) }}</div>
                    <div style="color: var(--spark-text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Feedback</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Role-Specific Analytics ──────────────────────── --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 30px; margin-bottom: 40px;">
        
        @if($finance_stats)
        {{-- Finance Deep-Dive --}}
        <div class="spark-card" style="border-top: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #10b981;"><i class="bi bi-cash-stack"></i> Revenue Deep-Dive</h3>
                <span style="font-size: 0.75rem; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 4px 10px; border-radius: 20px;">FINANCE VIEW</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div style="padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                    <div style="font-size: 0.75rem; color: var(--spark-text-muted); font-weight: 600;">LIFETIME EARNINGS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; margin-top: 5px;">${{ number_format($finance_stats['total_revenue'], 2) }}</div>
                </div>
                <div style="padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                    <div style="font-size: 0.75rem; color: var(--spark-text-muted); font-weight: 600;">PENDING APPROVALS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; margin-top: 5px; color: {{ $finance_stats['pending_approvals'] > 0 ? '#f59e0b' : 'inherit' }}">{{ $finance_stats['pending_approvals'] }}</div>
                </div>
            </div>
            <h4 style="font-size: 0.85rem; margin-bottom: 15px; color: var(--spark-text-muted);">RECENT TRANSACTIONS</h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($finance_stats['recent_transactions'] as $tx)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: rgba(255,255,255,0.01); border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.8rem;">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600;">{{ $tx->user ? $tx->user->name : 'N/A' }}</div>
                            <div style="font-size: 0.7rem; color: var(--spark-text-muted);">{{ $tx->gateway }}</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: #10b981;">+${{ number_format($tx->amount, 2) }}</div>
                        <div style="font-size: 0.65rem; color: var(--spark-text-muted);">{{ $tx->created_at?->format('M d') ?? 'N/A' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($support_stats)
        {{-- Support Deep-Dive --}}
        <div class="spark-card" style="border-top: 4px solid #ef4444;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #ef4444;"><i class="bi bi-chat-dots-fill"></i> Support Metrics</h3>
                <span style="font-size: 0.75rem; font-weight: 700; color: #ef4444; background: rgba(239, 68, 68, 0.1); padding: 4px 10px; border-radius: 20px;">SUPPORT VIEW</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div style="padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                    <div style="font-size: 0.75rem; color: var(--spark-text-muted); font-weight: 600;">UNREAD FEEDBACK</div>
                    <div style="font-size: 1.3rem; font-weight: 800; margin-top: 5px; color: #ef4444;">{{ $support_stats['pending_feedback'] }}</div>
                </div>
                <div style="padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                    <div style="font-size: 0.75rem; color: var(--spark-text-muted); font-weight: 600;">SUSPENDED ACCOUNTS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; margin-top: 5px;">{{ $support_stats['suspended_users'] }}</div>
                </div>
            </div>
            <h4 style="font-size: 0.85rem; margin-bottom: 15px; color: var(--spark-text-muted);">RECENT MESSAGES</h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($support_stats['recent_feedback'] as $fb)
                <div style="display: flex; gap: 12px; padding: 10px; background: rgba(255,255,255,0.01); border-radius: 8px;">
                    <div style="flex-shrink: 0; width: 35px; height: 35px; border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 0.8rem; font-weight: 700;">{{ $fb->user ? $fb->user->name : 'Guest' }}</span>
                            <span style="font-size: 0.65rem; color: var(--spark-text-muted);">{{ $fb->created_at->diffForHumans() }}</span>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--spark-text-muted); margin-top: 2px;">{{ Str::limit($fb->message, 80) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($dev_stats)
        {{-- Developer Deep-Dive --}}
        <div class="spark-card" style="border-top: 4px solid #3b82f6;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #3b82f6;"><i class="bi bi-gear-fill"></i> System Vitals</h3>
                <span style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; background: rgba(59, 130, 246, 0.1); padding: 4px 10px; border-radius: 20px;">ENGINEER VIEW</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div style="padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                    <div style="font-size: 0.65rem; color: var(--spark-text-muted); font-weight: 700; text-transform: uppercase;">PHP Runtime</div>
                    <div style="font-size: 1.1rem; font-weight: 800; margin-top: 5px;">v{{ $dev_stats['php_version'] }}</div>
                </div>
                <div style="padding: 15px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--spark-border);">
                    <div style="font-size: 0.65rem; color: var(--spark-text-muted); font-weight: 700; text-transform: uppercase;">SQL Engine</div>
                    <div style="font-size: 0.9rem; font-weight: 800; margin-top: 5px; opacity: 0.8;">{{ Str::limit($dev_stats['db_version'], 15) }}</div>
                </div>
            </div>
            
            <div style="padding: 15px; background: rgba(59,130,246,0.03); border-radius: 15px; border: 1px solid rgba(59,130,246,0.1); margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 0.7rem; color: #3b82f6; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">Total Page Views (Tools)</div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: var(--spark-text);">{{ number_format($dev_stats['total_views']) }}</div>
                    </div>
                    <i class="bi bi-graph-up-arrow" style="font-size: 1.5rem; color: #3b82f6; opacity: 0.3;"></i>
                </div>
            </div>

            <h4 style="font-size: 0.85rem; margin-bottom: 12px; color: var(--spark-text-muted);">LATEST COMPUTE LOGS</h4>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; background: #000; padding: 12px; border-radius: 10px; color: #10b981; max-height: 120px; overflow: hidden; opacity: 0.8;">
                @foreach($dev_stats['usage_trends'] as $usage)
                <div style="margin-bottom: 4px;">[{{ $usage->created_at?->format('H:i:s') ?? '00:00:00' }}] TRIGGER: {{ $usage->tool_name }} unit processed ({{ $usage->usage_count }} ops)</div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ── Shared Tool Performance ──────────────────────── --}}
    <div class="spark-card">
        <h3 style="margin-top: 0; margin-bottom: 25px; font-size: 1.25rem;">
            <i class="bi bi-fire" style="color: #f97316; margin-right: 8px;"></i> Global Tool Performance
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
            @foreach($most_used_tools as $usage)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: rgba(var(--spark-accent-rgb), 0.03); border-radius: 18px; border: 1px solid var(--spark-border); transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 42px; height: 42px; background: var(--spark-accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; box-shadow: 0 4px 12px var(--spark-accent-glow);">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.95rem; color: var(--spark-text);">{{ str_replace('-', ' ', ucwords($usage->tool_name, '-')) }}</div>
                        <div style="font-size: 0.75rem; color: var(--spark-text-muted);">Master Tool Unit</div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <span style="font-weight: 800; color: var(--spark-accent); font-size: 1.1rem;">{{ number_format($usage->total_usage) }}</span>
                    <div style="font-size: 0.65rem; color: var(--spark-text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Usages</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-spark-admin-layout>
