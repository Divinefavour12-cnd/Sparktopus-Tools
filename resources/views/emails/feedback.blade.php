<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Inter, Arial, sans-serif; background: #f4f4f8; margin: 0; padding: 20px; }
        .card { background: #fff; border-radius: 12px; max-width: 600px; margin: auto; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
        h1 { color: #6000C2; font-size: 22px; margin-top: 0; }
        .label { font-size: 12px; text-transform: uppercase; letter-spacing: .06em; color: #888; margin-bottom: 4px; }
        .value { font-size: 15px; color: #222; margin-bottom: 20px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; background: #ede7f6; color: #6000C2; font-weight: 600; }
        .message-box { background: #f7f4fe; border-left: 4px solid #6000C2; border-radius: 6px; padding: 14px 16px; font-size: 15px; color: #333; line-height: 1.6; }
        .footer { margin-top: 28px; font-size: 12px; color: #aaa; border-top: 1px solid #eee; padding-top: 16px; }
        img.screenshot { max-width: 100%; border-radius: 8px; margin-top: 16px; border: 1px solid #ddd; }
    </style>
</head>
<body>
<div class="card">
    <h1>📬 New Feedback Received</h1>

    <div class="label">From</div>
    <div class="value">
        @if($feedback->user)
            {{ $feedback->user->name }} &lt;{{ $feedback->user->email }}&gt;
        @else
            <span class="badge">Guest</span>
        @endif
    </div>

    <div class="label">Page URL</div>
    <div class="value">
        <a href="{{ $feedback->page_url }}" style="color:#6000C2;">{{ $feedback->page_url }}</a>
    </div>

    <div class="label">Message</div>
    <div class="message-box">{{ $feedback->message }}</div>

    @if($feedback->screenshot_path)
        <div class="label" style="margin-top:20px;">Screenshot</div>
        <p style="font-size:13px;color:#888;">See attached file: <strong>screenshot.png</strong></p>
    @endif

    <div class="label" style="margin-top:20px;">Submitted</div>
    <div class="value">{{ $feedback->created_at->format('F j, Y \a\t g:i A') }}</div>

    <div class="footer">
        Sparktopus Tools — Feedback System &bull;
        <a href="{{ url('/admin/feedback') }}" style="color:#6000C2;">View in Admin Panel</a>
    </div>
</div>
</body>
</html>
