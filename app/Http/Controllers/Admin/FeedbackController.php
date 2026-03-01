<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    /**
     * List all feedback submissions.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        $feedbacks = Feedback::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        $counts = [
            'new'      => Feedback::where('status', 'new')->count(),
            'reviewed' => Feedback::where('status', 'reviewed')->count(),
            'resolved' => Feedback::where('status', 'resolved')->count(),
        ];

        return view('feedback.index', compact('feedbacks', 'counts', 'status'));
    }

    /**
     * Show a single feedback item in detail.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('user');
        return view('feedback.show', compact('feedback'));
    }

    /**
     * Update the status of a feedback item.
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:new,reviewed,resolved',
        ]);

        $feedback->update(['status' => $request->status]);

        return redirect()
            ->route('admin.feedback.show', $feedback)
            ->withSuccess('Status updated successfully.');
    }

    /**
     * Delete a feedback item and its screenshot.
     */
    public function destroy(Feedback $feedback)
    {
        if ($feedback->screenshot_path) {
            Storage::disk('public')->delete($feedback->screenshot_path);
        }

        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->withSuccess('Feedback deleted.');
    }
}
