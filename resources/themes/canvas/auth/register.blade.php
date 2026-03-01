<x-canvas-guest-layout title="Register">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --spark-black: #000000;
            --spark-white: #ffffff;
            --spark-gray-50: #f9fafb;
            --spark-gray-100: #f3f4f6;
            --spark-gray-300: #d1d5db;
            --spark-gray-500: #6b7280;
            --spark-gray-700: #374151;
        }

        body.auth-body {
            background: var(--spark-white);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .auth-container {
            display: flex;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* ========== LEFT SHOWCASE SIDE (50%) ========== */
        .showcase-side {
            flex: 0 0 50%;
            width: 50%;
            height: 100vh;
            background: var(--spark-black);
            position: relative;
            overflow: hidden;
        }

        /* Logo */
        .showcase-logo {
            position: absolute;
            top: 40px;
            left: 50px;
            z-index: 20;
        }

        .showcase-logo img {
            height: 36px;
            filter: brightness(0) invert(1);
        }

        /* Progress Indicators */
        .progress-indicators {
            position: absolute;
            top: 45px;
            right: 50px;
            display: flex;
            gap: 8px;
            z-index: 20;
        }

        .progress-bar {
            width: 50px;
            height: 3px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: var(--spark-white);
            transition: width 0.1s linear;
        }

        /* Slides */
        .showcase-slides {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .showcase-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
        }

        .showcase-slide.active {
            opacity: 1;
        }

        .showcase-slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);
        }

        .slide-content {
            position: absolute;
            bottom: 80px;
            left: 50px;
            right: 50px;
            color: var(--spark-white);
            z-index: 10;
        }

        .slide-content h2 {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .slide-content p {
            font-size: 1.15rem;
            font-weight: 400;
            opacity: 0.9;
            line-height: 1.5;
        }

        /* ========== RIGHT FORM SIDE (50%) ========== */
        .form-side {
            flex: 0 0 50%;
            width: 50%;
            height: 100vh;
            background: var(--spark-white);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 50px 60px;
            overflow-y: auto;
        }

        .form-wrapper {
            width: 100%;
            max-width: 520px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--spark-black);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .form-header p {
            font-size: 1rem;
            color: var(--spark-gray-500);
            font-weight: 400;
        }

        /* Social Auth Divider */
        .divider-section {
            position: relative;
            text-align: center;
            margin: 28px 0;
        }

        .divider-line {
            border-top: 1px solid var(--spark-gray-300);
        }

        .divider-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--spark-white);
            padding: 0 16px;
            font-size: 0.85rem;
            color: var(--spark-gray-500);
            font-weight: 500;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--spark-gray-700);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--spark-gray-500);
            font-size: 1.05rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            height: 52px;
            padding: 0 20px 0 50px;
            background: var(--spark-gray-50);
            border: 2px solid transparent;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--spark-black);
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            background: var(--spark-white);
            border-color: var(--spark-black);
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
        }

        .form-input:focus + .input-icon {
            color: var(--spark-black);
        }

        .form-input::placeholder {
            color: var(--spark-gray-500);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            height: 52px;
            background: var(--spark-black);
            color: var(--spark-white);
            border: none;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 28px;
            font-size: 0.95rem;
            color: var(--spark-gray-500);
        }

        .login-link a {
            color: var(--spark-black);
            font-weight: 700;
            text-decoration: none;
            border-bottom: 2px solid var(--spark-black);
            padding-bottom: 2px;
        }

        .login-link a:hover {
            opacity: 0.8;
        }

        /* Error Messages */
        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 6px;
            font-weight: 500;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .showcase-side {
                flex: 0 0 45%;
                width: 45%;
            }
            
            .form-side {
                flex: 0 0 55%;
                width: 55%;
                padding: 40px;
            }

            .slide-content h2 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            body.auth-body {
                overflow-y: auto;
            }

            .auth-container {
                flex-direction: column;
                height: auto;
                overflow-y: auto;
                overflow-x: hidden;
            }

            .showcase-side {
                flex: 0 0 auto;
                width: 100%;
                height: 50vh;
                min-height: 400px;
            }

            .form-side {
                flex: 0 0 auto;
                width: 100%;
                height: auto;
                min-height: auto;
                padding: 40px 24px 60px;
            }

            .showcase-logo {
                top: 24px;
                left: 24px;
            }

            .progress-indicators {
                top: 28px;
                right: 24px;
            }

            .slide-content {
                bottom: 40px;
                left: 24px;
                right: 24px;
            }

            .slide-content h2 {
                font-size: 1.8rem;
            }

            .slide-content p {
                font-size: 1rem;
            }

            .form-header h1 {
                font-size: 1.8rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>

    <div class="auth-container">
        <!-- LEFT: Showcase -->
        <div class="showcase-side">
            <div class="showcase-logo">
                <a href="{{ route('front.index') }}">
                    <img src="{{ asset('storage/img/logo.png') }}" alt="Sparktopus Tools" onerror="this.src='{{ asset('themes/canvas/assets/img/logo.png') }}'">
                </a>
            </div>

            <div class="progress-indicators">
                <div class="progress-bar"><div class="progress-fill" id="progress-1"></div></div>
                <div class="progress-bar"><div class="progress-fill" id="progress-2"></div></div>
                <div class="progress-bar"><div class="progress-fill" id="progress-3"></div></div>
            </div>

            <div class="showcase-slides">
                <div class="showcase-slide active" style="background-image: url('https://images.unsplash.com/photo-1620712943543-bcc4638d9f8e?q=80&w=2400');">
                    <div class="slide-content">
                        <h2>Sparktopus Tools</h2>
                        <p>Professional AI-powered utilities for creators and businesses.</p>
                    </div>
                </div>
                <div class="showcase-slide" style="background-image: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2400');">
                    <div class="slide-content">
                        <h2>Unmatched Precision</h2>
                        <p>Advanced algorithms delivering human-quality results every time.</p>
                    </div>
                </div>
                <div class="showcase-slide" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2400');">
                    <div class="slide-content">
                        <h2>Built for Scale</h2>
                        <p>Reliable infrastructure supporting millions of operations daily.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <div class="form-header">
                    <h1>Create Account</h1>
                    <p>Start your journey with <strong>Sparktopus Tools</strong> today.</p>
                </div>

                {{-- Social Auth (if enabled) --}}
                <x-application-social-auth />
                
                <div class="divider-section">
                    <div class="divider-line"></div>
                    <span class="divider-text">or join with email</span>
                </div>

                <form id="frm-register" method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name & Username Row --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <div class="input-wrapper">
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input" 
                                    value="{{ $data['name'] ?? old('name') }}" 
                                    placeholder="John Doe"
                                    required 
                                    autofocus
                                >
                                <i class="bi bi-person input-icon"></i>
                            </div>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <div class="input-wrapper">
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    class="form-input" 
                                    value="{{ $data['username'] ?? old('username') }}" 
                                    placeholder="johndoe"
                                    required
                                >
                                <i class="bi bi-at input-icon"></i>
                            </div>
                            @error('username')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                value="{{ old('email') }}" 
                                placeholder="john@company.com"
                                required
                            >
                            <i class="bi bi-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password & Confirm Row --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input" 
                                    placeholder="••••••••"
                                    required 
                                    autocomplete="new-password"
                                >
                                <i class="bi bi-shield-lock input-icon"></i>
                            </div>
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm</label>
                            <div class="input-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input" 
                                    placeholder="••••••••"
                                    required
                                >
                                <i class="bi bi-shield-check input-icon"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    @if (setting('recaptcha_status', 0) && setting('recaptcha_signup', 0))
                        <button 
                            type="submit" 
                            class="submit-btn g-recaptcha" 
                            data-sitekey="{{ setting('recaptcha_site') }}" 
                            data-callback="onSubmit"
                        >
                            Create Account
                        </button>
                    @else
                        <button type="submit" class="submit-btn">
                            Create Account
                        </button>
                    @endif

                    {{-- Login Link --}}
                    <div class="login-link">
                        Already a member? <a href="{{ route('login') }}">Sign in here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('page_scripts')
    @if (setting('recaptcha_status', 0) && setting('recaptcha_signup', 0))
        <script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>
        <script>
            function onSubmit(token) {
                document.getElementById("frm-register").submit();
            }
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.showcase-slide');
            const progressBars = [
                document.getElementById('progress-1'),
                document.getElementById('progress-2'),
                document.getElementById('progress-3')
            ];
            
            let currentSlide = 0;
            const slideDuration = 5000;
            let startTime = Date.now();

            function updateSlideshow() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min((elapsed / slideDuration) * 100, 100);
                
                progressBars[currentSlide].style.width = progress + '%';

                if (progress >= 100) {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.add('active');
                    
                    progressBars.forEach((bar, idx) => {
                        if (idx < currentSlide) {
                            bar.style.width = '100%';
                        } else if (idx === currentSlide) {
                            bar.style.width = '0%';
                        } else {
                            bar.style.width = '0%';
                        }
                    });
                    
                    startTime = Date.now();
                }
                
                requestAnimationFrame(updateSlideshow);
            }

            updateSlideshow();
        });
    </script>
    @endpush
</x-canvas-guest-layout>