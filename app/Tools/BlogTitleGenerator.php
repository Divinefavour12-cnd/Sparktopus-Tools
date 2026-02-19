<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class BlogTitleGenerator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'blog-title-generator';

        // Safe defaults
        $plan = 'free';
        $limit = 3;
        $used = 0;
        $remaining = 3;
        $isLimitReached = false;
        $reset_at = now()->addHours(24)->toIso8601String();

        if ($user) {
            try {
                $plan = $user->planLevel();
                $limits = [
                    'free'    => 3,
                    'classic' => 20,
                    'plus'    => 50,
                    'pro'     => 200,
                ];
                $limit = $limits[$plan] ?? 3;
                $isLimitReached = !$user->canUseTool($toolSlug);

                // Get current usage count from the tool_usages table
                $usage = \DB::table('tool_usages')
                    ->where('user_id', $user->id)
                    ->where('tool_name', $toolSlug)
                    ->first();

                $used = $usage->usage_count ?? 0;
                $remaining = max(0, $limit - $used);

                // Build reset timestamp
                if ($usage && $usage->last_used_at) {
                    $reset_at = \Carbon\Carbon::parse($usage->last_used_at)->addMinutes(60)->toIso8601String();
                }
            } catch (\Exception $e) {
                // Keep defaults on any failure
            }
        }

        return view('tools.blog-title-generator', compact(
            'tool', 'plan', 'limit', 'used', 'remaining', 'isLimitReached', 'reset_at'
        ));
    }

    public function handle(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'blog-title-generator';

        if (!$user->canUseTool($toolSlug)) {
            $plan = $user->planLevel();
            $limits = [
                'free'    => 3,
                'classic' => 20,
                'plus'    => 50,
                'pro'     => 200,
            ];
            
            return redirect()->back()->with('show_upgrade_popup', [
                'current_plan' => ucfirst($plan),
                'current_limit' => $limits[$plan] ?? 3,
                'reset_time' => $user->getToolResetTime($toolSlug),
                'tool_name' => 'Blog Title Generator'
            ]);
        }

        $wordLimit = $tool->wc_tool ?? 10000;
        $request->validate([
            'string' => "required|min:3|max_words:{$wordLimit}",
            'tone' => 'nullable|string|max:50',
            'audience' => 'nullable|string|max:50',
        ]);

        $inputText = $request->input('string');
        $tone = $request->input('tone') ?? 'Catchy';
        $audience = $request->input('audience') ?? 'General';

        // Construct structured input for AI Service
        $aiInput = json_encode([
            'topic' => $inputText,
            'tone' => $tone,
            'audience' => $audience,
        ]);

        try {
            $aiService = app(AIService::class);
            $resultRaw = $aiService->generateResponse($toolSlug, $aiInput, $tool);

            // Attempt to parse JSON response
            $titles = [];
            $decoded = json_decode($resultRaw, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $titles = $decoded;
            } else {
                // Fallback: split by newlines if not valid JSON
                $lines = explode("\n", $resultRaw);
                foreach ($lines as $line) {
                    $clean = trim(preg_replace('/^\d+[\.\)]\s*|- /', '', $line));
                    if (!empty($clean)) {
                        $titles[] = $clean;
                    }
                }
            }

            $user->incrementToolUsage($toolSlug);

            // Recompute usage stats for the view
            $plan = $user->planLevel();
            $limits = [
                'free'    => 3,
                'classic' => 20,
                'plus'    => 50,
                'pro'     => 200,
            ];
            $limit = $limits[$plan] ?? 3;

            $usageRow = \DB::table('tool_usages')
                ->where('user_id', $user->id)
                ->where('tool_name', $toolSlug)
                ->first();

            $used = $usageRow->usage_count ?? 0;
            $remaining = max(0, $limit - $used);
            $isLimitReached = $used >= $limit;
            $reset_at = ($usageRow && $usageRow->last_used_at)
                ? \Carbon\Carbon::parse($usageRow->last_used_at)->addMinutes(60)->toIso8601String()
                : now()->addHours(24)->toIso8601String();

            return view('tools.blog-title-generator', [
                'tool' => $tool,
                'plan' => $plan,
                'limit' => $limit,
                'used' => $used,
                'remaining' => $remaining,
                'isLimitReached' => $isLimitReached,
                'reset_at' => $reset_at,
                'results' => [
                    'original_text' => $inputText,
                    'tone' => $tone,
                    'audience' => $audience,
                    'titles' => $titles,
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
