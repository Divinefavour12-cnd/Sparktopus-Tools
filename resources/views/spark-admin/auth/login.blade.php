<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Master Access | SparkAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #0a0a0c;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #121216;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.5) 0%, transparent 100%);
            border-radius: 34px;
            z-index: -1;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.2rem;
            color: #8b5cf6;
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.2);
        }

        h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.02em; }
        p { color: #9ca3af; margin-bottom: 30px; font-weight: 400; font-size: 0.95rem; }

        .input-group { margin-bottom: 20px; text-align: left; }
        label { display: block; margin-bottom: 8px; font-size: 0.85rem; font-weight: 600; color: #9ca3af; }
        input {
            width: 100%;
            height: 54px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 0 18px;
            color: #fff;
            font-family: inherit;
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #8b5cf6;
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }

        .btn-login {
            width: 100%;
            height: 56px;
            background: #8b5cf6;
            border: none;
            border-radius: 16px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 5px;
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            background: #7c3aed;
            box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4);
        }

        .error-msg { color: #ef4444; font-size: 0.85rem; margin-top: 8px; }

        /* Glow effects */
        .glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
            z-index: -2;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="glow" style="top: -250px; left: -250px;"></div>
    <div class="glow" style="bottom: -250px; right: -250px;"></div>

    <div class="login-card">
        <div class="icon-box">
             <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h1>Master Access</h1>
        <p>Restricted to authorized personnel only.</p>

        <form method="POST" action="{{ route('spark-admin.login.post') }}">
            @csrf

            <div class="input-group">
                <label>Identifier</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@sparktopus.com" required autofocus>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="input-group">
                <label>Security Key</label>
                <input type="password" name="password" placeholder="••••••••" required>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-login">Unlock Workspace</button>
        </form>
    </div>
</body>
</html>
