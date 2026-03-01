{{-- Upgrade popup - shows when user hits tool usage limits --}}
<div id="upgrade-popup-overlay" class="upgrade-popup-overlay" style="display: none;">
    <div class="usage-limit-modal">
        <button id="upgrade-popup-close" class="upgrade-popup-close" aria-label="Close">&times;</button>
        <div class="upgrade-popup-content p-4">
            <div class="usage-limit-icon mb-3">
                <i class="bi bi-exclamation-octagon-fill" style="font-size: 3rem; color: #6000C2;"></i>
            </div>
            <h3 class="fw-bold h4 mb-3">@lang('You have reached the limit')</h3>
            <p class="text-muted mb-4 px-3">
                @lang('Upgrade to the next plan to get more limits and continue humanizing your content without interruptions.')
            </p>
            <div class="d-grid">
                <a href="{{ route('plans.list') }}" class="btn btn-primary btn-lg rounded-pill fw-bold" style="background: #6000C2; border: none;">
                    @lang('Upgrade Now')
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .usage-limit-modal {
        background: var(--bs-body-bg, #fff);
        border-radius: 20px;
        max-width: 420px;
        width: 90%;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        animation: modalFadeUp 0.3s ease;
        z-index: 10001;
    }
    .upgrade-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @keyframes modalFadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    [theme-mode="dark"] .usage-limit-modal {
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.05);
    }
</style>

@if(session('show_upgrade_popup'))
@push('page_scripts')
<script>
(function() {
    var upgradeOverlay = document.getElementById('upgrade-popup-overlay');
    var upgradeCloseBtn = document.getElementById('upgrade-popup-close');
    var currentPlanEl = document.getElementById('upgrade-current-plan');
    var currentLimitEl = document.getElementById('upgrade-current-limit');
    
    if (!upgradeOverlay) return;
    
    // Get plan data from session
    var planData = @json(session('show_upgrade_popup'));
    
    // Update current plan info
    if (currentPlanEl && planData.current_plan) {
        currentPlanEl.textContent = planData.current_plan;
    }
    if (currentLimitEl && planData.current_limit) {
        currentLimitEl.textContent = planData.current_limit;
    }
    
    // Show reset time if available
    var resetTimeEl = document.getElementById('upgrade-reset-time');
    var resetTimeMsgEl = document.getElementById('upgrade-reset-message');
    
    if (resetTimeEl && resetTimeMsgEl && planData.reset_time) {
        resetTimeEl.textContent = planData.reset_time;
        resetTimeMsgEl.style.display = 'block';
    } else if (resetTimeMsgEl) {
        resetTimeMsgEl.style.display = 'none';
    }
    
    // Show available upgrade options based on current plan
    var currentPlan = planData.current_plan ? planData.current_plan.toLowerCase() : 'free';
    
    if (currentPlan === 'free') {
        document.getElementById('upgrade-plan-classic').style.display = 'block';
        document.getElementById('upgrade-plan-plus').style.display = 'block';
        document.getElementById('upgrade-plan-pro').style.display = 'block';
    } else if (currentPlan === 'classic') {
        document.getElementById('upgrade-plan-plus').style.display = 'block';
        document.getElementById('upgrade-plan-pro').style.display = 'block';
    } else if (currentPlan === 'plus') {
        document.getElementById('upgrade-plan-pro').style.display = 'block';
    }
    
    // Show popup immediately
    upgradeOverlay.style.display = 'flex';
    var pageContent = document.getElementById('page-content-area');
    if (pageContent) pageContent.classList.add('page-content-blur');
    
    // Close button handler
    if (upgradeCloseBtn) {
        upgradeCloseBtn.addEventListener('click', function() {
            upgradeOverlay.style.display = 'none';
            if (pageContent) pageContent.classList.remove('page-content-blur');
        });
    }
    
    // Close on overlay click
    upgradeOverlay.addEventListener('click', function(e) {
        if (e.target === upgradeOverlay) {
            upgradeOverlay.style.display = 'none';
            if (pageContent) pageContent.classList.remove('page-content-blur');
        }
    });
})();
</script>
@endpush
@endif
