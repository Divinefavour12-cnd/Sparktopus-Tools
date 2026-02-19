<?php

namespace App\Http\Controllers;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use CyrildeWit\EloquentViewable\Support\Period;

class HomeController extends ToolController
{
    public function home()
    {
        $tool = Tool::with('translations')
            ->withCount('usageToday')
            ->with('category')
            ->index()
            ->active()
            ->first();

        if ($tool && class_exists($tool->class_name) && method_exists($tool->class_name, 'index') && method_exists($tool->class_name, 'handle')) {
            $tool->load('category');
            $instance = new $tool->class_name();
            $tool->createVisitLog(auth()->user());

            $relevant_tools = Tool::with('translations')
                ->withCount('usageToday')
                ->with('category')
                ->active()
                ->take('16')
                ->orderBy('display')
                ->get();
            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();
            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            Meta::setMeta($tool);

            return $instance->index($tool, $relevant_tools, $plans, $faqs, $properties);
        }
        Meta::setMeta();

        return $this->tools();
    }

    public function tools()
    {
        list($favorties, $tools) = Cache::rememberForever('homepage_tools_favorite', function () {
            $favorties = Auth::check() ? Auth::user()->favorite_tools : null;
            $tools = Category::query()
                ->active()
                ->tool()
                ->with('translations')
                ->with(['tools' => function ($query) {
                    $query->active()->with('translations')->orderBy('display');
                }])
                ->orderBy('order')
                ->get();
            return [$favorties, $tools];
        });

        Meta::setMeta();
        $ads = ['above-tool', 'above-form', 'below-form', 'above-result', 'below-result'];

        // Trending tools - Last 7 days most viewed
        $trending_tools = Tool::with('translations')
            ->active()
            ->orderByViews('desc', Period::pastDays(7))
            ->limit(6)
            ->get();

        // Fallback to popular_tools if no trending data
        $popular_tools = Tool::with('translations')
            ->active()
            ->withCount('usageToday')
            ->orderBy('usage_today_count', 'desc')
            ->limit(6)
            ->get();

        // Search suggestions - Last 24 hours top 3 most viewed
        $search_suggestions = Tool::with('translations')
            ->active()
            ->orderByViews('desc', Period::create(now()->subDay(), now()))
            ->limit(3)
            ->get();

        // Newly added tools - sorted by created_at desc, with view count
        $newly_added_tools = Tool::with('translations')
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('index', compact(
            'tools', 
            'ads', 
            'favorties', 
            'popular_tools',
            'trending_tools',
            'search_suggestions',
            'newly_added_tools'
        ));
    }

    /**
     * All Tools page - Shows all tools grouped by category
     */
    public function allTools()
    {
        $tools = Category::query()
            ->active()
            ->tool()
            ->with('translations')
            ->with(['tools' => function ($query) {
                $query->active()->with('translations')->orderBy('display');
            }])
            ->orderBy('order')
            ->get();

        $totalTools = Tool::active()->count();

        Meta::setMeta();

        return view('pages.all-tools', compact('tools', 'totalTools'));
    }

    public function homeTool(Request $request)
    {
        $tool = Tool::with('translations')->where('is_home', true)->active()->firstOrFail();
        Meta::setMeta($tool);

        return $this->handle($request, $tool->slug);
    }
}
