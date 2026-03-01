<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tool;
use App\Models\Feedback;
use App\Models\ToolUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Design the custom premium dashboard.
     */
    /**
     * Design the custom premium dashboard.
     */
    public function index()
    {
        $admin = auth('admin')->user();
        
        // Base stats for everyone
        $total_users = User::count();
        $total_tools = Tool::count();
        $total_feedback = Feedback::count();

        // Data containers
        $finance_stats = null;
        $support_stats = null;
        $dev_stats = null;

        // Role-Specific Data Fetching
        if ($admin->hasRole('Super Admin') || $admin->hasRole('Finance Admin')) {
            $finance_stats = $this->getFinanceStats();
        }

        if ($admin->hasRole('Super Admin') || $admin->hasRole('Support') || $admin->hasRole('Moderator')) {
            $support_stats = $this->getSupportStats();
        }

        if ($admin->hasRole('Super Admin') || $admin->hasRole('Developer')) {
            $dev_stats = $this->getDevStats();
        }

        // Shared analytics
        $most_used_tools = ToolUsage::select(DB::raw('LOWER(TRIM(tool_name)) as tool_name'), DB::raw('SUM(usage_count) as total_usage'))
            ->groupBy(DB::raw('LOWER(TRIM(tool_name))'))
            ->orderByDesc('total_usage')
            ->limit(8)
            ->get();

        $subscription_breakdown = User::all()->groupBy(function($user) {
            return $user->planLevel();
        })->map->count();

        return view('spark-admin.dashboard', compact(
            'total_users',
            'total_feedback',
            'total_tools',
            'subscription_breakdown',
            'most_used_tools',
            'finance_stats',
            'support_stats',
            'dev_stats'
        ));
    }

    private function getFinanceStats()
    {
        return [
            'total_revenue' => \App\Models\Transaction::active()->sum('amount'),
            'monthly_revenue' => \App\Models\Transaction::active()->where('created_at', '>=', now()->startOfMonth())->sum('amount'),
            'pending_approvals' => \App\Models\Transaction::where('status', 0)->count(),
            'recent_transactions' => \App\Models\Transaction::with('user')->latest()->limit(5)->get()
        ];
    }

    private function getSupportStats()
    {
        return [
            'pending_feedback' => Feedback::where('status', 'new')->count(),
            'suspended_users' => User::where('status', 0)->count(),
            'recent_feedback' => Feedback::with('user')->latest()->limit(5)->get()
        ];
    }

    private function getDevStats()
    {
        return [
            'php_version' => PHP_VERSION,
            'db_version' => DB::select('SELECT VERSION() as version')[0]->version,
            'total_views' => Schema::hasTable('tool_views') ? DB::table('tool_views')->sum('views') : 0,
            'usage_trends' => ToolUsage::orderBy('id', 'desc')->limit(12)->get()
        ];
    }
}
