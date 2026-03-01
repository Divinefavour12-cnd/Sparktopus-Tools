<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use DB;

class EnglishConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'english-converter';

        // Defaults
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

        return view('tools.english-converter', compact(
            'tool', 'plan', 'limit', 'used', 'remaining', 'isLimitReached', 'reset_at'
        ));
    }

    public function handle(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'english-converter';

        if (!$user) {
             return redirect()->back()->with('show_guest_modal', true);
        }

        if (!$user->canUseTool($toolSlug)) {
            $plan = $user->planLevel();
            return redirect()->back()->with('show_upgrade_popup', [
                'current_plan' => ucfirst($plan),
                'current_limit' => $user->getDailyLimit($toolSlug),
                'reset_time' => $user->getToolResetTime($toolSlug),
                'tool_name' => 'English AI Converter'
            ]);
        }

        $wordLimit = $tool->wc_tool ?? 5000;
        $request->validate([
            'string' => "required|min:3|max_words:{$wordLimit}",
            'target_lang' => 'required|string',
            'tone' => 'nullable|string',
        ]);

        $inputText = $request->input('string');
        $targetLang = $request->input('target_lang');
        $tone = $request->input('tone') ?? 'Natural';

        $aiInput = json_encode([
            'text' => $inputText,
            'target_lang' => $targetLang,
            'tone' => $tone,
        ]);

        try {
            $aiService = app(AIService::class);
            $convertedText = $aiService->generateResponse($toolSlug, $aiInput, $tool);

            $user->incrementToolUsage($toolSlug);

            // Fetch updated stats
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

            return view('tools.english-converter', [
                'tool' => $tool,
                'plan' => $user->planLevel(),
                'limit' => $limit,
                'used' => $used,
                'remaining' => $remaining,
                'isLimitReached' => $isLimitReached,
                'reset_at' => $reset_at,
                'results' => [
                    'original_text' => $inputText,
                    'converted_text' => $convertedText,
                    'target_lang' => $targetLang,
                    'tone' => $tone
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public static function getFileds()
    {
        return [
            'title' => "AI Engine Settings",
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
