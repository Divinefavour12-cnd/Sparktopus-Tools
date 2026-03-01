<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackMail;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    /**
     * Store a new piece of feedback (used by the AJAX frontend widget).
     */
    public function store(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:5000',
            'screenshot' => 'nullable|string', // base64 PNG data URI
            'page_url'   => 'nullable|url|max:500',
        ]);

        $screenshotPath = null;

        // Save the screenshot if provided
        if ($request->filled('screenshot')) {
            $base64 = $request->input('screenshot');

            // Strip the data URI prefix if present: "data:image/png;base64,..."
            if (str_contains($base64, ',')) {
                $base64 = explode(',', $base64, 2)[1];
            }

            $decoded = base64_decode($base64, true);

            if ($decoded !== false) {
                $filename  = 'feedback/' . Str::uuid() . '.png';
                Storage::disk('public')->put($filename, $decoded);
                $screenshotPath = $filename;
            }
        }

        $feedback = Feedback::create([
            'user_id'         => auth()->id(),
            'message'         => $request->input('message'),
            'screenshot_path' => $screenshotPath,
            'page_url'        => $request->input('page_url') ?? url()->previous(),
            'status'          => 'new',
        ]);

        // Send email notification silently (don't crash if SMTP not configured)
        try {
            Mail::to('tools@sparktopus.com')->send(new FeedbackMail($feedback));
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            logger()->error('FeedbackMail failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Thank you for your feedback!']);
    }
}
