{{-- ========================================================
     SPARKTOPUS FEEDBACK WIDGET
     Floating button → Slide-in sidebar → Screenshot capture
     Works for guests + logged-in users | Dark/Light mode
     ======================================================== --}}

{{-- Load html2canvas for screenshot capture --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
/* ── Variables ─────────────────────────────────────── */
:root {
    --fb-purple: #6000C2;
    --fb-purple-light: #7b1de8;
    --fb-radius: 14px;
    --fb-transition: 0.32s cubic-bezier(0.4, 0, 0.2, 1);
    --fb-sidebar-width: 400px;
    --fb-z: 99990;
}

/* ── Floating Action Button ─────────────────────────── */
#feedback-fab {
    position: fixed;
    bottom: 28px;
    right: 24px;
    z-index: var(--fb-z);
    background: var(--fb-purple);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 11px 20px 11px 16px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: .02em;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    box-shadow: none;
    transition: transform 0.15s ease, background 0.15s ease;
    font-family: inherit;
}
#feedback-fab:hover {
    background: var(--fb-purple-light);
    transform: translateY(-2px);
}
#feedback-fab svg { width: 17px; height: 17px; flex-shrink: 0; }

/* ── Overlay ────────────────────────────────────────── */
#feedback-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: calc(var(--fb-z) + 1);
    opacity: 0;
    pointer-events: none;
    transition: opacity var(--fb-transition);
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
}
#feedback-overlay.fb-visible {
    opacity: 1;
    pointer-events: all;
}

/* ── Sidebar ────────────────────────────────────────── */
#feedback-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: min(var(--fb-sidebar-width), 100vw);
    height: 100%;
    z-index: calc(var(--fb-z) + 2);
    background: #fff;
    box-shadow: -2px 0 20px rgba(0,0,0,.1);
    transform: translateX(100%);
    transition: transform var(--fb-transition);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
#feedback-sidebar.fb-open { transform: translateX(0); }

/* Dark mode sidebar */
[theme-mode="dark"] #feedback-sidebar {
    background: #1a1a2e;
    box-shadow: -2px 0 20px rgba(0,0,0,.3);
}

/* ── Sidebar Header ──────────────────────────────────── */
.fb-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px 16px;
    border-bottom: 1px solid rgba(0,0,0,.08);
    flex-shrink: 0;
    background: var(--fb-purple);
    color: #fff;
}
.fb-sidebar-title {
    font-size: 16px;
    font-weight: 700;
    letter-spacing: -.01em;
    display: flex;
    align-items: center;
    gap: 8px;
}
.fb-sidebar-title svg { width: 20px; height: 20px; opacity: .9; }
.fb-close-btn {
    background: rgba(255,255,255,.18);
    border: none;
    cursor: pointer;
    color: #fff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .15s;
    flex-shrink: 0;
}
.fb-close-btn:hover { background: rgba(255,255,255,.32); }
.fb-close-btn svg { width: 16px; height: 16px; }

/* ── Sidebar Body ────────────────────────────────────── */
.fb-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* ── Label ──────────────────────────────────────────── */
.fb-label {
    font-size: 13px;
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
    display: block;
}
[theme-mode="dark"] .fb-label { color: #bbb; }

/* ── Textarea ────────────────────────────────────────── */
#fb-message {
    width: 100%;
    min-height: 130px;
    max-height: 260px;
    padding: 12px 14px;
    border: 1.5px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    transition: border-color .2s, box-shadow .2s;
    background: #fafafa;
    color: #222;
    line-height: 1.6;
    box-sizing: border-box;
}
#fb-message:focus {
    outline: none;
    border-color: var(--fb-purple);
    box-shadow: 0 0 0 3px rgba(96,0,194,.14);
    background: #fff;
}
[theme-mode="dark"] #fb-message {
    background: #12122a;
    border-color: #333;
    color: #eee;
}
[theme-mode="dark"] #fb-message:focus {
    background: #1a1a3e;
    border-color: var(--fb-purple);
}

/* ── Screenshot Button ───────────────────────────────── */
#fb-capture-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: transparent;
    border: 1.5px dashed var(--fb-purple);
    border-radius: 10px;
    color: var(--fb-purple);
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, transform .1s;
    font-family: inherit;
    width: 100%;
    justify-content: center;
}
#fb-capture-btn:hover {
    background: rgba(96,0,194,.07);
    transform: scale(1.01);
}
#fb-capture-btn svg { width: 16px; height: 16px; }

/* ── Screenshot Preview ─────────────────────────────── */
#fb-preview-section {
    display: none;
    flex-direction: column;
    gap: 8px;
}
#fb-preview-section.fb-visible { display: flex; }
.fb-preview-label {
    font-size: 12px;
    font-weight: 600;
    color: #777;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
[theme-mode="dark"] .fb-preview-label { color: #999; }
#fb-remove-screenshot {
    background: none;
    border: none;
    color: #e53935;
    cursor: pointer;
    font-size: 12px;
    font-family: inherit;
    padding: 0;
}
#fb-preview-img {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #ddd;
    max-height: 180px;
    object-fit: contain;
    background: #f5f5f5;
}
[theme-mode="dark"] #fb-preview-img { border-color: #333; background: #0d0d20; }

/* ── Submit Button ──────────────────────────────────── */
.fb-sidebar-footer {
    padding: 14px 20px;
    border-top: 1px solid rgba(0,0,0,.08);
    flex-shrink: 0;
}
[theme-mode="dark"] .fb-sidebar-footer { border-color: rgba(255,255,255,.08); }
#fb-submit-btn {
    width: 100%;
    padding: 12px;
    background: var(--fb-purple);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    letter-spacing: .01em;
    transition: opacity .15s, transform .1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
#fb-submit-btn:hover { opacity: .9; transform: scale(1.005); }
#fb-submit-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }
#fb-submit-btn svg { width: 17px; height: 17px; }

/* ── Error message ────────────────────────────────────── */
#fb-error-msg {
    display: none;
    color: #e53935;
    font-size: 12.5px;
    padding: 8px 12px;
    background: rgba(229,57,53,.08);
    border-radius: 8px;
    border-left: 3px solid #e53935;
}
#fb-error-msg.fb-visible { display: block; }

/* ── Countdown Overlay ──────────────────────────────── */
#fb-countdown-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000000;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    background: rgba(0,0,0,.5);
    flex-direction: column;
    gap: 16px;
}
#fb-countdown-overlay.fb-visible { display: flex; }
#fb-countdown-number {
    font-size: clamp(96px, 18vw, 160px);
    font-weight: 900;
    color: #fff;
    line-height: 1;
    text-shadow: none;
    animation: fb-pulse 0.8s ease-in-out;
}
.fb-countdown-hint {
    color: rgba(255,255,255,.75);
    font-size: 16px;
    font-weight: 500;
    letter-spacing: .04em;
}
@keyframes fb-pulse {
    0% { transform: scale(1.3); opacity: 0; }
    40% { opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}

/* ── Purple Flash ────────────────────────────────────── */
#fb-flash-overlay {
    position: fixed;
    inset: 0;
    z-index: calc(var(--fb-z) + 200);
    background: var(--fb-purple);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.06s ease;
}
#fb-flash-overlay.fb-flash {
    animation: fb-camera-flash 0.15s ease forwards;
}
@keyframes fb-camera-flash {
    0% { opacity: 0; }
    50% { opacity: 0.95; }
    100% { opacity: 0; }
}

/* ── Toast ───────────────────────────────────────────── */
#fb-toast {
    position: fixed;
    bottom: 80px;
    right: 24px;
    z-index: calc(var(--fb-z) + 300);
    background: #1a1a2e;
    color: #fff;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 20px rgba(0,0,0,.3);
    display: flex;
    align-items: center;
    gap: 8px;
    transform: translateY(20px);
    opacity: 0;
    transition: transform .3s ease, opacity .3s ease;
    pointer-events: none;
}
#fb-toast.fb-toast-show {
    transform: translateY(0);
    opacity: 1;
}
#fb-toast svg { width: 16px; height: 16px; color: #4caf50; }

/* ── Mobile ───────────────────────────────────────────── */
@media (max-width: 576px) {
    #feedback-fab { bottom: 72px; right: 14px; padding: 10px 16px 10px 13px; font-size: 13px; }
    #feedback-sidebar { width: 100%; }
}

/* ── Footer Legal Info ── */
.fb-footer-info {
    border-top: 1px solid rgba(0,0,0,.06);
    padding-top: 18px;
    margin-top: 4px;
}
[theme-mode="dark"] .fb-footer-info { border-color: rgba(255,255,255,.06); }

.fb-checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
}
.fb-checkbox-group input[type="checkbox"] {
    width: 17px;
    height: 17px;
    accent-color: var(--fb-purple);
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 2px;
}
.fb-checkbox-label {
    font-size: 13px;
    color: #555;
    cursor: pointer;
    line-height: 1.4;
}
[theme-mode="dark"] .fb-checkbox-label { color: #aaa; }

.fb-legal-text {
    font-size: 11px;
    line-height: 1.55;
    color: #777;
    margin-bottom: 0;
}
[theme-mode="dark"] .fb-legal-text { color: #888; }

.fb-legal-text a {
    color: var(--fb-purple) !important;
    text-decoration: none !important;
    font-weight: 500;
}
.fb-legal-text a:hover { text-decoration: underline !important; }
[theme-mode="dark"] .fb-legal-text a { color: var(--fb-purple-light) !important; }
</style>

{{-- Floating Button --}}
<button id="feedback-fab" aria-label="Send Feedback" title="Send Feedback">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    Feedback
</button>

{{-- Dark Overlay --}}
<div id="feedback-overlay"></div>

{{-- Sidebar --}}
<div id="feedback-sidebar" role="dialog" aria-modal="true" aria-label="Feedback Panel">
    {{-- Header --}}
    <div class="fb-sidebar-header">
        <div class="fb-sidebar-title">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Send feedback to Sparktopus
        </div>
        <button class="fb-close-btn" id="fb-close-btn" aria-label="Close feedback panel">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- Body --}}
    <div class="fb-sidebar-body">
        {{-- Error message --}}
        <div id="fb-error-msg"></div>

        {{-- Message textarea --}}
        <div>
            <label class="fb-label" for="fb-message">Describe your feedback *</label>
            <textarea id="fb-message" placeholder="Tell us what you think, what's broken, or what could be better…" maxlength="5000"></textarea>
        </div>

        {{-- Capture Screenshot Button --}}
        <button id="fb-capture-btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            Capture Screenshot
        </button>

        {{-- Screenshot Preview --}}
        <div id="fb-preview-section">
            <div class="fb-preview-label">
                <span>📎 Screenshot attached</span>
                <button id="fb-remove-screenshot" type="button">✕ Remove</button>
            </div>
            <img id="fb-preview-img" src="" alt="Screenshot preview">
        </div>

        {{-- Footer Legal Info --}}
        <div class="fb-footer-info">
            <div class="fb-checkbox-group">
                <input type="checkbox" id="fb-email-consent">
                <label for="fb-email-consent" class="fb-checkbox-label">
                    @lang('We may email you for more information or updates')
                </label>
            </div>
            
            <p class="fb-legal-text">
                @lang('Some account and system information may be sent to Sparktopus. We will use it to fix problems and improve our services, subject to our') 
                <a href="#">@lang('Privacy Policy')</a> @lang('and') 
                <a href="#">@lang('Terms of Service')</a>. 
                @lang('We may email you for more information or updates. Go to') 
                <a href="#">@lang('Legal Help')</a> @lang('to ask for content changes for legal reasons.')
            </p>
        </div>
    </div>

    {{-- Footer with Submit --}}
    <div class="fb-sidebar-footer">
        <button id="fb-submit-btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            Submit Feedback
        </button>
    </div>
</div>

{{-- 3-2-1 Countdown Overlay --}}
<div id="fb-countdown-overlay">
    <div id="fb-countdown-number">3</div>
    <div class="fb-countdown-hint">Capturing your screen…</div>
</div>

{{-- Purple Flash Overlay --}}
<div id="fb-flash-overlay"></div>

{{-- Thank-you Toast --}}
<div id="fb-toast">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
    </svg>
    Thank you for your feedback!
</div>

<script>
(function () {
    'use strict';

    /* ── Elements ─────────────────────────────── */
    const fab          = document.getElementById('feedback-fab');
    const overlay      = document.getElementById('feedback-overlay');
    const sidebar      = document.getElementById('feedback-sidebar');
    const closeBtn     = document.getElementById('fb-close-btn');
    const captureBtn   = document.getElementById('fb-capture-btn');
    const submitBtn    = document.getElementById('fb-submit-btn');
    const messageEl    = document.getElementById('fb-message');
    const previewSec   = document.getElementById('fb-preview-section');
    const previewImg   = document.getElementById('fb-preview-img');
    const removeSsBtn  = document.getElementById('fb-remove-screenshot');
    const countdownEl  = document.getElementById('fb-countdown-overlay');
    const numberEl     = document.getElementById('fb-countdown-number');
    const flashEl      = document.getElementById('fb-flash-overlay');
    const errorEl      = document.getElementById('fb-error-msg');
    const toast        = document.getElementById('fb-toast');

    let capturedBase64 = null; // stores base64 PNG of screenshot

    /* ── Open / Close ─────────────────────────── */
    function openSidebar() {
        sidebar.classList.add('fb-open');
        overlay.classList.add('fb-visible');
        document.body.style.overflow = 'hidden';
        setTimeout(() => messageEl.focus(), 350);
    }

    function closeSidebar() {
        sidebar.classList.remove('fb-open');
        overlay.classList.remove('fb-visible');
        document.body.style.overflow = '';
    }

    function hideFeedbackUI() {
        sidebar.style.display = 'none';
        overlay.style.display = 'none';
        fab.style.display = 'none';
    }

    function showFeedbackUI() {
        sidebar.style.display = '';
        overlay.style.display = '';
        fab.style.display = '';
        sidebar.classList.add('fb-open');
        overlay.classList.add('fb-visible');
    }

    /* ── Error / Toast helpers ────────────────── */
    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.add('fb-visible');
        errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideError() {
        errorEl.classList.remove('fb-visible');
        errorEl.textContent = '';
    }

    function showToast() {
        toast.classList.add('fb-toast-show');
        setTimeout(() => toast.classList.remove('fb-toast-show'), 4000);
    }

    /* ── Screenshot Capture ───────────────────── */
    async function captureScreenshot() {
        // Step A: Hide ALL feedback elements
        hideFeedbackUI();
        countdownEl.style.display = 'none';
        flashEl.style.display = 'none';
        await sleep(150);

        // Step B: Show countdown 3 → 2 → 1 → 0
        countdownEl.style.display = '';
        countdownEl.classList.add('fb-visible');
        for (let i = 3; i >= 0; i--) {
            numberEl.textContent = i;
            numberEl.style.animation = 'none';
            numberEl.offsetHeight;
            numberEl.style.animation = '';
            await sleep(300); // Faster countdown
        }
        countdownEl.classList.remove('fb-visible');
        countdownEl.style.display = 'none';
        await sleep(50);

        // Step C: Capture clean screenshot (no overlays)
        let screenshotData = null;
        try {
            const feedbackIds = ['feedback-fab','feedback-overlay','feedback-sidebar','fb-countdown-overlay','fb-flash-overlay','fb-toast'];
            const canvas = await html2canvas(document.documentElement, {
                useCORS: true,
                allowTaint: false,
                scale: 1,
                logging: false,
                width: window.innerWidth,
                height: window.innerHeight,
                x: window.scrollX,
                y: window.scrollY,
                ignoreElements: function(el) {
                    return feedbackIds.includes(el.id);
                }
            });
            try {
                screenshotData = canvas.toDataURL('image/jpeg', 0.7);
            } catch(e) {
                // Tainted canvas fallback — draw to new clean canvas
                const w = canvas.width, h = canvas.height;
                const c2 = document.createElement('canvas');
                c2.width = w; c2.height = h;
                const ctx = c2.getContext('2d');
                ctx.drawImage(canvas, 0, 0);
                try { screenshotData = c2.toDataURL('image/jpeg', 0.7); } catch(e2) { /* give up */ }
            }
        } catch (err) {
            console.warn('html2canvas error:', err);
        }

        // Step D: Flash
        countdownEl.style.display = '';
        flashEl.style.display = '';
        flashEl.classList.add('fb-flash');
        await sleep(150); // Faster flick delay
        flashEl.classList.remove('fb-flash');

        // Step E: Reopen sidebar
        showFeedbackUI();

        // Step F: Show preview image
        if (screenshotData && screenshotData.length > 100) {
            capturedBase64 = screenshotData;
            // Use onload to guarantee image renders
            previewImg.onload = function() {
                previewSec.style.display = 'flex';
                previewSec.classList.add('fb-visible');
            };
            previewImg.src = screenshotData;
        }
    }

    /* ── Form Submit ──────────────────────────── */
    async function submitFeedback() {
        hideError();
        const message = messageEl.value.trim();

        if (!message) {
            showError('Please describe your feedback before submitting.');
            messageEl.focus();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg style="animation:fb-spin 1s linear infinite;width:16px;height:16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/>
                <line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/>
            </svg> Submitting…`;

        try {
            const payload = {
                message,
                email_consent: document.getElementById('fb-email-consent').checked ? 1 : 0,
                page_url: window.location.href,
                _token: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            };
            if (capturedBase64) payload.screenshot = capturedBase64;

            const resp = await fetch('{{ route("feedback.submit") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': payload._token, 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await resp.json();

            if (data.success) {
                submitBtn.innerHTML = `
                    <svg style="width:18px;height:18px;color:#4caf50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg> Submitted Successfully!`;

                setTimeout(() => {
                    closeSidebar();
                    showToast();
                    // Reset form
                    messageEl.value = '';
                    capturedBase64 = null;
                    previewImg.src = '';
                    previewSec.classList.remove('fb-visible');
                }, 1000);
            } else {
                showError(data.message || 'Something went wrong. Please try again.');
            }
        } catch (err) {
            showError('Network error. Please check your connection and try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
                Submit Feedback`;
        }
    }

    /* ── Event Listeners ──────────────────────── */
    fab.addEventListener('click', openSidebar);
    overlay.addEventListener('click', closeSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    captureBtn.addEventListener('click', captureScreenshot);
    submitBtn.addEventListener('click', submitFeedback);

    removeSsBtn.addEventListener('click', () => {
        capturedBase64 = null;
        previewImg.src = '';
        previewSec.classList.remove('fb-visible');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('fb-open')) closeSidebar();
    });

    /* ── Helpers ──────────────────────────────── */
    function sleep(ms) { return new Promise(resolve => setTimeout(resolve, ms)); }
})();
</script>

<style>
@keyframes fb-spin { to { transform: rotate(360deg); } }
</style>
