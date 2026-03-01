@php
    $user = Auth::user();
    $toolSlug = $tool->slug;
    $plan = $user->planLevel();
    $usageToday = $tool->usage_today_count;
    $dailyLimit = $user->getDailyLimit($toolSlug);
    $isUnlimited = \Setting::get('unlimited_usage', 0) == 1 || ($user && $user->hasRole((int) config('artisan.super_admin_role', 'Super Admin')));
    
    // Usage stats for the progress bar
    $percentage = $isUnlimited ? 0 : ($dailyLimit > 0 ? min(100, ($usageToday / $dailyLimit) * 100) : 0);
    $remaining = $isUnlimited ? '∞' : max(0, $dailyLimit - $usageToday);
    
    // Plan-specific reset logic
    $resetTimes = ['free' => 24, 'classic' => 22, 'plus' => 20, 'pro' => 18];
    $resetHours = $resetTimes[$plan] ?? 24;
@endphp

<div class="usage-showcase mb-4 p-3 border rounded bg-light">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">
            <i class="lni lni-stats-up me-2"></i>@lang('tools.usageLimit') 
            <span class="badge bg-primary ms-2">{{ ucfirst($plan) }} Plan</span>
        </h5>
        <div class="text-muted small">
            Resets every {{ $resetHours }}h
        </div>
    </div>
    
    <div class="progress mb-2" style="height: 10px;">
        <div class="progress-bar {{ $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}" 
             role="progressbar" 
             style="width: {{ $percentage }}%" 
             aria-valuenow="{{ $percentage }}" 
             aria-valuemin="0" 
             aria-valuemax="100">
        </div>
    </div>
    
    <div class="d-flex justify-content-between small">
        <span>Used: <strong>{{ $usageToday }}</strong> / {{ $isUnlimited ? '∞' : $dailyLimit }}</span>
        <span>Remaining: <strong>{{ $remaining }}</strong></span>
    </div>
</div>
