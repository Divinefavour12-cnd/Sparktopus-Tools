<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuration | Sparktopus Tools</title>
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
        .installer-wrap { width: 100%; max-width: 780px; }
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
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #2d2d3f;
        }
        .card-title i { color: #a78bfa; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 0.85rem; font-weight: 600; color: #94a3b8; }
        input {
            background: #0f0f1a;
            border: 1px solid #2d2d3f;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: #f1f5f9;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s;
            outline: none;
        }
        input:focus { border-color: #a78bfa; }
        input::placeholder { color: #4b5563; }
        .error-text { font-size: 0.8rem; color: #fca5a5; margin-top: 0.25rem; }
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9rem;
        }
        .alert-danger { background: #450a0a; border: 1px solid #7f1d1d; color: #fca5a5; }
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
        <p>Configure your database, admin account and website settings.</p>
    </div>

    <div class="steps">
        <div class="step done">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Requirements</div>
        </div>
        <div class="step done">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Purchase</div>
        </div>
        <div class="step active">
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
            <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;margin-top:2px;"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('installconfig.post') }}" method="POST">
        @csrf

        {{-- Database --}}
        <div class="card">
            <div class="card-title"><i class="bi bi-database-fill"></i> Database Configuration</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>DB Host</label>
                    <input type="text" name="db[host]" value="{{ old('db.host', '127.0.0.1') }}" placeholder="127.0.0.1" required>
                    @error('db.host')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>DB Port</label>
                    <input type="text" name="db[port]" value="{{ old('db.port', '3306') }}" placeholder="3306" required>
                    @error('db.port')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>DB Name</label>
                    <input type="text" name="db[database]" value="{{ old('db.database') }}" placeholder="sparktopus" required>
                    @error('db.database')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>DB Username</label>
                    <input type="text" name="db[username]" value="{{ old('db.username', 'root') }}" placeholder="root" required>
                    @error('db.username')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label>DB Password</label>
                    <input type="password" name="db[password]" placeholder="Leave empty if none">
                    @error('db.password')<span class="error-text">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Admin Account --}}
        <div class="card">
            <div class="card-title"><i class="bi bi-person-fill-gear"></i> Admin Account</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="admin[name]" value="{{ old('admin.name') }}" placeholder="Admin Name" required>
                    @error('admin.name')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="admin[email]" value="{{ old('admin.email') }}" placeholder="admin@example.com" required>
                    @error('admin.email')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="admin[password]" placeholder="Min. 8 characters" required>
                    @error('admin.password')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="admin[password_confirmation]" placeholder="Repeat password" required>
                </div>
            </div>
        </div>

        {{-- Website Settings --}}
        <div class="card">
            <div class="card-title"><i class="bi bi-globe2"></i> Website Settings</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Application Name</label>
                    <input type="text" name="website[app_name]" value="{{ old('website.app_name', 'Sparktopus Tools') }}" placeholder="My SaaS App" required>
                    @error('website.app_name')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="website[app_email]" value="{{ old('website.app_email') }}" placeholder="contact@example.com" required>
                    @error('website.app_email')<span class="error-text">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('verifypurchase') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <button type="submit" class="btn btn-primary">
                Install Now <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</div>
</body>
</html>