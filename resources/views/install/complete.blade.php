<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installation Complete | Sparktopus Tools</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', sans-serif;
            background: #0f0f1a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .installer-wrap { width: 100%; max-width: 620px; }
        .installer-header { text-align: center; margin-bottom: 2.5rem; }
        .installer-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.8rem;
            font-weight: 800;
            color: #a78bfa;
            margin-bottom: 1rem;
        }
        .installer-logo i { font-size: 2rem; }
        .steps {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 2.5rem;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            flex: 1;
            position: relative;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 16px;
            left: 60%;
            width: 80%;
            height: 2px;
            background: #22c55e;
        }
        .step-num {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid #22c55e;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700;
            color: #fff;
            position: relative; z-index: 1;
        }
        .step-label { font-size: 0.75rem; color: #22c55e; text-align: center; font-weight: 600; }
        .card {
            background: #1a1a2e;
            border: 1px solid #2d2d3f;
            border-radius: 16px;
            padding: 3rem 2rem;
            text-align: center;
        }
        .success-icon {
            width: 90px; height: 90px;
            background: #14532d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #86efac;
            border: 3px solid #22c55e;
        }
        .card h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #f1f5f9;
            margin-bottom: 0.75rem;
        }
        .card p {
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        @media (max-width: 500px) { .info-grid { grid-template-columns: 1fr; } }
        .info-item {
            background: #0f0f1a;
            border: 1px solid #2d2d3f;
            border-radius: 10px;
            padding: 1rem;
        }
        .info-item-label { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
        .info-item-value { font-size: 0.9rem; color: #f1f5f9; font-weight: 600; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-primary { background: #a78bfa; color: #fff; }
        .btn-primary:hover { background: #8b5cf6; }
        .confetti { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
    </style>
</head>
<body>
<div class="installer-wrap">
    <div class="installer-header">
        <div class="installer-logo">
            <i class="bi bi-rocket-takeoff-fill"></i>
            Sparktopus Tools
        </div>
    </div>

    <div class="steps">
        <div class="step">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Requirements</div>
        </div>
        <div class="step">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Purchase</div>
        </div>
        <div class="step">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Configuration</div>
        </div>
        <div class="step">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Complete</div>
        </div>
    </div>

    <div class="card">
        <span class="confetti">🎉</span>
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h2>Installation Complete!</h2>
        <p>Sparktopus Tools has been successfully installed and configured.<br>
        You can now log in to your admin panel and start managing your platform.</p>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-item-label">Admin Panel</div>
                <div class="info-item-value">/admin</div>
            </div>
            <div class="info-item">
                <div class="info-item-label">Status</div>
                <div class="info-item-value" style="color:#86efac;">✓ Active</div>
            </div>
        </div>

        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house-fill"></i> Go to Homepage
        </a>
    </div>
</div>
</body>
</html>