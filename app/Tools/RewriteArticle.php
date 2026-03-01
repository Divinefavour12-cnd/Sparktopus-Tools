<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use DB;

class RewriteArticle implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'rewrite-article';

        // Defaults for UI state
        $plan = 'free';
        $limit = 3;
        $used = 0;
        $remaining = 3;
        $isLimitReached = false;
        $reset_at = now()->addHours(24)->toIso8601String();

        if ($user) {
            $plan = $user->planLevel();
            $limit = $user->getDailyLimit($toolSlug);
            $remaining = $user->getRemainingUsage($toolSlug);
            
            $usage = DB::table('tool_usages')
                ->where('user_id', $user->id)
                ->where('tool_name', $toolSlug)
                ->first();

            $used = $usage->usage_count ?? 0;
            $isLimitReached = $used >= $limit;

            if ($usage && $usage->last_used_at) {
                $reset_at = \Carbon\Carbon::parse($usage->last_used_at)->addMinutes(60)->toIso8601String();
            }
        }

        return view('tools.rewrite-article', compact(
            'tool', 'plan', 'limit', 'used', 'remaining', 'isLimitReached', 'reset_at'
        ));
    }

    public function handle(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'rewrite-article';

        // 1. Authenticated check
        if (!$user) {
             return redirect()->back()->with('show_guest_modal', true);
        }

        // 2. Usage limit check
        if (!$user->canUseTool($toolSlug)) {
            $plan = $user->planLevel();
            return redirect()->back()->with('show_upgrade_popup', [
                'current_plan' => ucfirst($plan),
                'current_limit' => $user->getDailyLimit($toolSlug),
                'reset_time' => $user->getToolResetTime($toolSlug),
                'tool_name' => 'AI Article Rewriter'
            ]);
        }

        // 3. Validation
        $wordLimit = $tool->wc_tool ?? 5000;
        $request->validate([
            'string' => "required|min:10|max_words:{$wordLimit}",
            'mode' => 'nullable|string',
            'creativity' => 'nullable|string',
        ]);

        $inputText = $request->input('string');
        $mode = $request->input('mode') ?? 'Smart';
        $creativity = $request->input('creativity') ?? 'Medium';

        // 4. Prepare AI payload
        $aiInput = json_encode([
            'text' => $inputText,
            'mode' => $mode,
            'creativity' => $creativity,
        ]);

        try {
            $aiService = app(AIService::class);
            $rewrittenText = $aiService->generateResponse($toolSlug, $aiInput, $tool);

            // 5. Track usage
            $user->incrementToolUsage($toolSlug);

            // 6. Fetch updated stats for UI
            $limit = $user->getDailyLimit($toolSlug);
            $usageRow = DB::table('tool_usages')
                ->where('user_id', $user->id)
                ->where('tool_name', $toolSlug)
                ->first();

            $used = $usageRow->usage_count ?? 0;
            $remaining = max(0, $limit - $used);
            $isLimitReached = $used >= $limit;
            $reset_at = ($usageRow && $usageRow->last_used_at)
                ? \Carbon\Carbon::parse($usageRow->last_used_at)->addMinutes(60)->toIso8601String()
                : now()->addHours(24)->toIso8601String();

            return view('tools.rewrite-article', [
                'tool' => $tool,
                'plan' => $user->planLevel(),
                'limit' => $limit,
                'used' => $used,
                'remaining' => $remaining,
                'isLimitReached' => $isLimitReached,
                'reset_at' => $reset_at,
                'results' => [
                    'original_text' => $inputText,
                    'article_rewrite' => $rewrittenText,
                    'mode' => $mode,
                    'creativity' => $creativity
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public static function getFileds()
    {
        return [
            'title' => "AI Rewriter Settings",
            'fields' => [
                [
                    'id' => "groq_apikey",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Enter Groq API Key",
                    'label' => "Groq API Key (Optional, uses ENV if empty)",
                    'required' => false,
                    'type' => 'text',
                ],
                [
                    'id' => "model",
                    'field' => "tool-options-textfield",
                    'placeholder' => "llama-3.3-70b-versatile",
                    'label' => "AI Model",
                    'required' => true,
                    'type' => 'text',
                ],
            ]
        ];
    }
}
