<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:billing.view-revenue')->only(['index']);
        $this->middleware('permission:billing.manage-plans')->only(['updateStatus']);
    }

    /**
     * Display a listing of subscriptions (transactions) in SparkAdmin style.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        
        $subscriptions = Transaction::with(['plan', 'user'])
            ->whereHas('plan', function ($query) {
                $query->orWhere('transactions.plan_id', 0);
            })
            ->has('user')
            ->when(!empty($search), function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('spark-admin.subscriptions.index', compact('subscriptions', 'search'));
    }

    /**
     * Update subscription/transaction status (e.g. for bank transfers).
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|integer']);
        $transaction->update(['status' => $request->status]);

        if ($request->status == 1) {
            $transaction->expiry_date = $transaction->plan_type == "yearly" ? now()->addYear() : now()->addMonth();
            $transaction->response = 'Approved manually via SparkAdmin';
            $transaction->update();
        }

        return back()->with('success', 'Subscription status updated.');
    }
}
