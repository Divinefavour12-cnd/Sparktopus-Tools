<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Purchase | Sparktopus Tools</title>
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
        .installer-wrap { width: 100%; max-width: 720px; }
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
        .installer-header h1 { font-size: 1.5rem; font-weight: 600; color: #f1f5f9; }
        .installer-header p { color: #94a3b8; margin-top: 0.5rem; }
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
            background: #1e1e2e;
        }
        .step.done:not(:last-child)::after { background: #22c55e; }
        .step-num {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #1e1e2e;
            border: 2px solid #2d2d3f;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700;
            color: #64748b;
            position: relative; z-index: 1;
        }
        .step.active .step-num { background: #a78bfa; border-color: #a78bfa; color: #fff; }
        .step.done .step-num { background: #22c55e; border-color: #22c55e; color: #fff; }
        .step-label { font-size: 0.75rem; color: #64748b; text-align: center; }
        .step.active .step-label { color: #a78bfa; font-weight: 600; }
        .step.done .step-label { color: #22c55e; }
        .card {
            background: #1a1a2e;
            border: 1px solid #2d2d3f;
            border-radius: 16px;
            padding: 2.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .card-icon {
            font-size: 3.5rem;
            color: #a78bfa;
            margin-bottom: 1.5rem;
        }
        .card h2 { font-size: 1.3rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.75rem; }
        .card p { color: #94a3b8; line-height: 1.6; margin-bottom: 2rem; }
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            text-align: left;
        }
        .alert-danger { background: #450a0a; border: 1px solid #7f1d1d; color: #fca5a5; }
        .alert-success { background: #14532d; border: 1px solid #166534; color: #86efac; }
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
        .btn-outline {
            background: transparent;
            color: #a78bfa;
            border: 1px solid #a78bfa;
        }
        .btn-outline:hover { background: #a78bfa; color: #fff; }
        .actions { display: flex; justify-content: space-between; margin-top: 1.5rem; }
    </style>
</head>
<body>
<div class="installer-wrap">
    <div class="installer-header">
        <div class="installer-logo">
            <i class="bi bi-rocket-takeoff-fill"></i>
            Sparktopus Tools
        </div>
        <h1>Installation Wizard</h1>
        <p>Verify your purchase to continue.</p>
    </div>

    <div class="steps">
        <div class="step done">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Requirements</div>
        </div>
        <div class="step active">
            <div class="step-num">2</div>
            <div class="step-label">Purchase</div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div class="step-label">Configuration</div>
        </div>
        <div class="step">
            <div class="step-num">4</div>
            <div class="step-label">Complete</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-icon"><i class="bi bi-patch-check-fill"></i></div>
        <h2>Verify Your Purchase</h2>
        <p>To activate Sparktopus Tools, you need to verify your purchase.<br>
        Click the button below to be redirected to our verification portal.</p>

        @if ($verifyPurchase->satisfied())
            <div class="alert alert-success" style="justify-content:center;">
                <i class="bi bi-check-circle-fill"></i>
                Purchase verified successfully!
            </div>
            <a href="{{ route('installconfig.get') }}" class="btn btn-primary">
                Continue to Configuration <i class="bi bi-arrow-right"></i>
            </a>
        @else
            <a href="{{ route('verify.redirect') }}" class="btn btn-primary">
                <i class="bi bi-shield-check"></i> Verify Purchase
            </a>
        @endif
    </div>

    <div class="actions">
        <a href="{{ route('preinstall') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        @if ($verifyPurchase->satisfied())
            <a href="{{ route('installconfig.get') }}" class="btn btn-primary">
                Next: Configuration <i class="bi bi-arrow-right"></i>
            </a>
        @endif
    </div>
</div>
</body>
</html>