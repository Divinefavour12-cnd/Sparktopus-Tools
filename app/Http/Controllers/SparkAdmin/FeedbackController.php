<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:feedback.view')->only(['index', 'show']);
        $this->middleware('permission:feedback.respond')->only(['updateStatus']);
        $this->middleware('permission:feedback.delete')->only(['destroy']);
    }

    /**
     * Display a listing of feedback in the premium SparkAdmin style.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        $feedbacks = Feedback::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('spark-admin.feedback.index', compact('feedbacks', 'status'));
    }

    /**
     * Show a specific feedback entry.
     */
    public function show(Feedback $feedback)
    {
        return view('spark-admin.feedback.show', compact('feedback'));
    }

    /**
     * Update feedback status.
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate(['status' => 'required|in:new,reviewed,resolved']);
        $oldStatus = $feedback->status;
        $feedback->update(['status' => $request->status]);

        // Notify User
        if ($feedback->user && $oldStatus !== $request->status) {
            $feedback->user->notify(new \App\Notifications\AppNotification(
                __('Feedback Updated'),
                __('Your feedback status has been updated to :status.', ['status' => ucfirst($request->status)]),
                $request->status === 'resolved' ? 'success' : 'info',
                route('front.index') // Or user dashboard if applicable
            ));
        }

        return back()->with('success', 'Feedback status updated to ' . $request->status);
    }

    /**
     * Delete feedback.
     */
    public function destroy(Feedback $feedback)
    {
        if ($feedback->screenshot_path) {
            Storage::disk('public')->delete($feedback->screenshot_path);
        }
        $feedback->delete();

        return redirect()->route('spark-admin.feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
