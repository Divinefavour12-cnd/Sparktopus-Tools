@php
    $adModel = get_advert_model('popup');
@endphp

@if($adModel && $adModel->status)
    @php
        // Resolve image/video paths through asset() so they work with or without /public
        $adImage = isset($adModel->options['image']) && $adModel->options['image']
            ? (str_starts_with($adModel->options['image'], 'http') ? $adModel->options['image'] : asset($adModel->options['image']))
            : null;
        $adVideo = isset($adModel->options['video']) && $adModel->options['video']
            ? (str_starts_with($adModel->options['video'], 'http') ? $adModel->options['video'] : asset($adModel->options['video']))
            : null;
        $adUrl = $adModel->options['url'] ?? '#';
        $adCountdown = $adModel->options['countdown'] ?? 5;
    @endphp

    <style>
        .popup-ad-modal {
            width: fit-content !important;
            min-width: 300px !important;
            max-width: 85vw !important;
            max-height: 80vh !important;
            overflow: hidden !important;
            padding: 1rem !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(12px) saturate(180%) !important;
            border-radius: 20px !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            position: relative !important;
            animation: popupFadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        [theme-mode="dark"] .popup-ad-modal {
            background: rgba(30, 30, 35, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
        }

        @keyframes popupFadeUp {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .popup-ad-close {
            position: absolute !important;
            top: 12px !important;
            right: 12px !important;
            width: 32px !important;
            height: 32px !important;
            background: rgba(0, 0, 0, 0.05) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 50% !important;
            color: #444 !important;
            font-size: 20px !important;
            line-height: 1 !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
            z-index: 100 !important;
        }

        [theme-mode="dark"] .popup-ad-close {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #eee !important;
        }

        .popup-ad-close:hover {
            background: #ff4757 !important;
            color: white !important;
            border-color: #ff4757 !important;
            transform: rotate(90deg) !important;
        }

        .popup-ad-content {
            width: 100% !important;
            overflow-y: auto !important;
            padding-top: 15px !important;
        }

        .popup-ad-content img, 
        .popup-ad-content video {
            width: auto !important;
            max-width: 100% !important;
            max-height: 60vh !important; 
            height: auto !important;
            object-fit: contain !important;
            display: block !important;
            margin: 0 auto !important;
            border-radius: 12px !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }

        .ad-remove-wrapper {
            margin-top: 15px !important;
            padding: 10px 0 !important;
            width: 100% !important;
            text-align: center !important;
            border-top: 1px solid rgba(0,0,0,0.05) !important;
        }

        [theme-mode="dark"] .ad-remove-wrapper {
            border-top-color: rgba(255,255,255,0.05) !important;
        }

        .ad-remove-link {
            font-size: 13px !important;
            color: #7c3aed !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 6px 16px !important;
            background: rgba(124, 58, 237, 0.08) !important;
            border-radius: 20px !important;
            transition: all 0.2s ease !important;
        }

        .ad-remove-link:hover {
            background: #7c3aed !important;
            color: white !important;
            transform: translateY(-1px) !important;
        }
    </style>

    <div id="popup-ad-overlay" class="popup-ad-overlay" style="display: none;">
        <div class="popup-ad-modal">
            <button id="popup-ad-close" class="popup-ad-close" aria-label="Close" style="display: none;">&times;</button>
            
            {{-- Countdown indicator --}}
            <div id="popup-ad-timer" class="popup-ad-timer" style="display: none;">
                <span id="popup-timer-count">{{ $adCountdown }}</span>
            </div>
            
            <div class="popup-ad-content">
                @if($adVideo)
                    <video id="adVideo" autoplay muted playsinline>
                        <source src="{{ $adVideo }}" type="video/mp4">
                    </video>
                @elseif($adImage)
                     <a href="{{ $adUrl }}" target="_blank">
                        <img src="{{ $adImage }}" alt="Ad">
                    </a>
                @endif
                
                <div class="ad-remove-wrapper">
                    <a href="{{ route('plans.list') }}" class="ad-remove-link">
                         <i class="bi bi-shield-lock me-1"></i> Ad-Free Sparktopus [Upgrade]
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script>
    (function() {
        var overlay = document.getElementById('popup-ad-overlay');
        var closeBtn = document.getElementById('popup-ad-close');
        var timerWrap = document.getElementById('popup-ad-timer');
        var timerCount = document.getElementById('popup-timer-count');
        var video = document.getElementById('adVideo');
        
        if (!overlay) return;
        
        var countdown = parseInt("{{ $adCountdown }}");
        
        // Show overlay immediately on page load
        setTimeout(function() {
            overlay.style.display = 'flex';
            
            if (countdown > 0) {
                timerWrap.style.display = 'flex';
                var interval = setInterval(function() {
                    countdown--;
                    timerCount.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        timerWrap.style.display = 'none';
                        closeBtn.style.display = 'flex';
                    }
                }, 1000);
            } else {
                closeBtn.style.display = 'flex';
            }
            
            // Try to play video if exists
            if (video) {
                video.play().catch(function() {
                    console.log('Autoplay blocked');
                });
            }
        }, 300);
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
                if (video) video.pause();
            });
        }
        
        // Close on overlay click if countdown finished
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay && closeBtn.style.display !== 'none') {
                overlay.style.display = 'none';
                if (video) video.pause();
            }
        });
    })();
    </script>
    @endpush
@endif
