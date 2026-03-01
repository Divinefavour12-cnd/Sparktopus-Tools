<x-canvas-guest-layout title="Member Login">
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
            align-items: center;
            justify-content: center;
            padding: 60px;
            overflow-y: auto;
        }

        .form-wrapper {
            width: 100%;
            max-width: 460px;
        }

        .form-header {
            margin-bottom: 40px;
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

        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
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
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            height: 56px;
            padding: 0 20px 0 52px;
            background: var(--spark-gray-50);
            border: 2px solid transparent;
            border-radius: 14px;
            font-size: 1rem;
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

        /* Form Footer */
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            font-size: 0.9rem;
            color: var(--spark-gray-700);
            cursor: pointer;
            font-weight: 500;
        }

        .forgot-link {
            font-size: 0.9rem;
            color: var(--spark-black);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            height: 56px;
            background: var(--spark-black);
            color: var(--spark-white);
            border: none;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Sign Up Link */
        .signup-link {
            text-align: center;
            margin-top: 32px;
            font-size: 0.95rem;
            color: var(--spark-gray-500);
        }

        .signup-link a {
            color: var(--spark-black);
            font-weight: 700;
            text-decoration: none;
            border-bottom: 2px solid var(--spark-black);
            padding-bottom: 2px;
        }

        .signup-link a:hover {
            opacity: 0.8;
        }

        /* Error Messages */
        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Status Messages */
        .status-message {
            padding: 14px 18px;
            background: #dcfce7;
            border: 1px solid #86efac;
            border-radius: 12px;
            color: #166534;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 24px;
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
            .auth-container {
                flex-direction: column;
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
                min-height: 50vh;
                padding: 40px 24px;
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
        }
    </style>

    <div class="auth-container">
        <!-- LEFT: Showcase -->
        <div class="showcase-side">
            <div class="showcase-logo">
                <a href="{{ route('front.index') }}">
                    <img src="{{ asset('themes/canvas/assets/images/logo-dark.svg') }}" alt="Sparktopus Tools">
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
                    <h1>Log In</h1>
                    <p>Welcome back! Access your professional toolkit.</p>
                </div>

                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form id="frm-login" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">Email Identity</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                value="{{ old('email') }}" 
                                placeholder="name@company.com"
                                required 
                                autofocus
                            >
                            <i class="bi bi-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
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
                                autocomplete="current-password"
                            >
                            <i class="bi bi-shield-lock input-icon"></i>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="form-footer">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember_me">
                            <label for="remember_me">Remember Me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Recover Access</a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    @if (setting('recaptcha_status', 0) && setting('recaptcha_login', 0))
                        <button 
                            type="submit" 
                            class="submit-btn g-recaptcha" 
                            data-sitekey="{{ setting('recaptcha_site') }}" 
                            data-callback="onSubmit"
                        >
                            Enter Workspace
                        </button>
                    @else
                        <button type="submit" class="submit-btn">
                            Enter Workspace
                        </button>
                    @endif

                    <!-- Sign Up Link -->
                    <div class="signup-link">
                        New here? <a href="{{ route('register') }}">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('page_scripts')
    @if (setting('recaptcha_status', 0) && setting('recaptcha_login', 0))
        <script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>
        <script>
            function onSubmit(token) {
                document.getElementById("frm-login").submit();
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

            // Admin Secret Code
            const emailInput = document.getElementById('email');
            const adminCode = "{{ env('ADMIN_SECRET_CODE', 'sparkdovia') }}";
            
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    if (this.value.trim() === adminCode) {
                        window.location.href = "{{ route('admin.login') }}";
                    }
                });
            }
        });
    </script>
    @endpush
</x-canvas-guest-layout>