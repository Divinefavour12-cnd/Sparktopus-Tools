<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.read')->only(['index', 'show']);
        $this->middleware('permission:user.suspend')->only(['suspend', 'unsuspend', 'ban']);
        $this->middleware('permission:user.delete')->only(['destroy']);
        $this->middleware('permission:user.reset-password')->only(['resetUsage']);
        $this->middleware('permission:billing.manage-plans')->only(['upgrade']);
    }

    /**
     * Display a listing of users in SparkAdmin style.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $status = $request->get('status');

        $users = User::query()
            ->when($search, function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })
            ->when($status !== null, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('spark-admin.users.index', compact('users', 'search', 'status'));
    }

    /**
     * View detailed user information.
     */
    public function show(User $user)
    {
        $transactions = $user->transactions()->with('plan')->get();
        $usages = $user->toolUsages()->orderBy('last_used_at', 'desc')->get();
        
        return view('spark-admin.users.show', compact('user', 'transactions', 'usages'));
    }

    /**
     * Suspend a user.
     */
    public function suspend(User $user)
    {
        $user->update(['status' => 0]);
        return back()->with('success', 'User suspended successfully.');
    }

    /**
     * Unsuspend a user.
     */
    public function unsuspend(User $user)
    {
        $user->update(['status' => 1]);
        return back()->with('success', 'User unsuspended successfully.');
    }

    /**
     * Ban a user permanently.
     */
    public function ban(User $user)
    {
        $user->update(['status' => 2]);
        return back()->with('success', 'User banned from the platform.');
    }

    /**
     * Reset tool usage limits for a user.
     */
    public function resetUsage(User $user)
    {
        $user->toolUsages()->delete();
        return back()->with('success', 'All tool usage limits have been reset for ' . $user->name);
    }

    /**
     * Manually upgrade or downgrade a user's plan.
     */
    public function upgrade(Request $request, User $user, $plan_id)
    {
        // Simple manual transaction injection for plan override
        $user->transactions()->create([
            'transaction_id' => 'ADMIN_OVERRIDE_' . strtoupper(uniqid()),
            'plan_id' => $plan_id,
            'amount' => 0,
            'currency' => 'USD',
            'status' => 'active',
            'expiry_date' => now()->addMonth(), // Standard 1 month extension
        ]);

        return back()->with('success', 'User plan has been updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
