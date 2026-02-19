<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class GrammarChecker implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.grammar-checker', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $user = Auth::user();
        $toolSlug = 'grammar-checker';

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
                'tool_name' => 'Grammar Checker'
            ]);
        }

        $wordLimit = $tool->wc_tool ?? 10000;
        $request->validate([
            'string' => "required|min:10|max_words:{$wordLimit}",
        ]);

        $inputText = $request->input('string');

        try {
            $aiService = app(AIService::class);
            $result = $aiService->generateResponse($toolSlug, $inputText, $tool);

            $user->incrementToolUsage($toolSlug);

            return view('tools.grammar-checker', [
                'tool' => $tool,
                'results' => [
                    'original_text' => $inputText,
                    'converted_text'=> $result,
                    'remaining'     => $user->getRemainingUsage($toolSlug),
                    'plan'          => ucfirst($user->planLevel()),
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
