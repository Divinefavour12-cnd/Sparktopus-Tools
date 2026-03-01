<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tool;
use App\Models\Feedback;
use App\Models\ToolUsage;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $total_users = User::count();
        $total_feedback = Feedback::count();
        $total_tools = Tool::count();

        // Subscription breakdown
        // We categorize by planLevel logic from User model
        $subscription_breakdown = User::all()->groupBy(function($user) {
            return $user->planLevel();
        })->map->count();

        // Most used tools (last 30 days or total)
        $most_used_tools = ToolUsage::select('tool_name', DB::raw('SUM(usage_count) as total_usage'))
            ->groupBy('tool_name')
            ->orderByDesc('total_usage')
            ->limit(5)
            ->get();

        // Most viewed tools (using eloquent-viewable if possible, or simple views table query)
        // Since the trait adds views(), we can try to join or just query the views table directly if it exists.
        // Assuming cyrildewit/eloquent-viewable uses 'views' table.
        $most_viewed_tools = [];
        if (Schema::hasTable('views')) {
            $most_viewed_tools = DB::table('views')
                ->where('viewable_type', Tool::class)
                ->select('viewable_id', DB::raw('count(*) as view_count'))
                ->groupBy('viewable_id')
                ->orderByDesc('view_count')
                ->limit(5)
                ->get()
                ->map(function($v) {
                    $tool = Tool::find($v->viewable_id);
                    $v->tool_name = $tool ? $tool->name : 'Unknown';
                    return $v;
                });
        }

        // Recent feedback list
        $recent_feedback = Feedback::with('user')->latest()->limit(5)->get();

        return view('dashboard', compact(
            'total_users', 
            'total_feedback', 
            'total_tools', 
            'subscription_breakdown', 
            'most_used_tools', 
            'most_viewed_tools', 
            'recent_feedback'
        ));
    }
}
