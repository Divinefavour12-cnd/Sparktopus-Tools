<?php

namespace App\Tools;

use App\Models\Tool;
use App\Models\ToolUsage;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AiHumanizer implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        if (!Auth::check()) {
            return view('tools.ai-humanizer', compact('tool'));
        }

        $user = Auth::user();
        $currentPlan = $user->planLevel();
        $toolSlug = 'ai-humanizer';
        
        $limits = [
            'free'    => 3,
            'classic' => 20,
            'plus'    => 50,
            'pro'     => 200,
        ];
        
        $resetHours = [
            'free'    => 24,
            'classic' => 22,
            'plus'    => 20,
            'pro'     => 18,
        ];

        $limit = $limits[$currentPlan] ?? 3;
        $resetInterval = $resetHours[$currentPlan] ?? 24;
        $resetMinutes = $resetInterval * 60;

        $usage = $user->toolUsages()->firstOrCreate(
            ['tool_name' => $toolSlug],
            [
                'usage_count' => 0,
                'plan'        => $currentPlan,
                'last_used_at'=> now(),
            ]
        );

        if ($usage->plan !== $currentPlan) {
            $usage->update([
                'usage_count' => 0,
                'plan'        => $currentPlan,
                'last_used_at'=> now(),
            ]);
            $usage = $usage->fresh();
        }

        $minutesSinceLastUse = 0;
        if ($usage->last_used_at) {
            $minutesSinceLastUse = \Carbon\Carbon::parse($usage->last_used_at)->diffInMinutes(now());
        }

        if ($minutesSinceLastUse >= $resetMinutes) {
            $usage->update([
                'usage_count' => 0,
                'last_used_at'=> now(),
            ]);
            $usage = $usage->fresh();
            $minutesSinceLastUse = 0;
        }

        $minutesRemaining = max(0, $resetMinutes - $minutesSinceLastUse);
        $resetAt = $usage->last_used_at 
            ? \Carbon\Carbon::parse($usage->last_used_at)->addMinutes($resetMinutes)
            : now()->addMinutes($resetMinutes);

        return view('tools.ai-humanizer', [
            'tool' => $tool,
            'used' => $usage->usage_count,
            'limit' => $limit,
            'plan' => $currentPlan,
            'reset_hours' => $resetInterval,
            'minutes_remaining' => $minutesRemaining,
            'reset_at' => $resetAt->toIso8601String(),
        ]);
    }

    public function handle(Request $request, Tool $tool)
    {
        if (!Auth::check()) {
            return redirect()->back()->withErrors(__('Please login to use this tool.'));
        }

        $user = Auth::user();
        $currentPlan = $user->planLevel();
        $toolSlug = 'ai-humanizer';
        
        /*
        |--------------------------------------------------------------------------
        | PLAN LIMITS
        |--------------------------------------------------------------------------
        */
        $limits = [
            'free'    => 3,
            'classic' => 20,
            'plus'    => 50,
            'pro'     => 200,
        ];

        $limit = $limits[$currentPlan] ?? 3;

        /*
        |--------------------------------------------------------------------------
        | FETCH OR CREATE USAGE
        |--------------------------------------------------------------------------
        */
        $usage = $user->toolUsages()->firstOrCreate(
            ['tool_name' => $toolSlug],
            [
                'usage_count' => 0,
                'plan'        => $currentPlan,
                'last_used_at'=> now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | RESET IF PLAN CHANGED
        |--------------------------------------------------------------------------
        */
        if ($usage->plan !== $currentPlan) {
            $usage->update([
                'usage_count' => 0,
                'plan'        => $currentPlan,
                'last_used_at'=> now(),
            ]);
            $usage = $usage->fresh();
        }

        /*
        |--------------------------------------------------------------------------
        | PLAN-SPECIFIC RESET TIMES
        |--------------------------------------------------------------------------
        */
        $resetHours = [
            'free'    => 24,
            'classic' => 22,
            'plus'    => 20,
            'pro'     => 18,
        ];
        
        $resetInterval = $resetHours[$currentPlan] ?? 24;
        $resetMinutes = $resetInterval * 60;

        /*
        |--------------------------------------------------------------------------
        | RESET BASED ON PLAN-SPECIFIC INTERVAL
        |--------------------------------------------------------------------------
        */
        $minutesSinceLastUse = 0;
        if ($usage->last_used_at) {
            $minutesSinceLastUse = \Carbon\Carbon::parse($usage->last_used_at)->diffInMinutes(now());
        }

        if ($minutesSinceLastUse >= $resetMinutes) {
            $usage->update([
                'usage_count' => 0,
                'last_used_at'=> now(),
            ]);
            $usage = $usage->fresh();
            $minutesSinceLastUse = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | CALCULATE COUNTDOWN DATA
        |--------------------------------------------------------------------------
        */
        $minutesRemaining = max(0, $resetMinutes - $minutesSinceLastUse);
        $resetAt = $usage->last_used_at 
            ? \Carbon\Carbon::parse($usage->last_used_at)->addMinutes($resetMinutes)
            : now()->addMinutes($resetMinutes);

        /*
        |--------------------------------------------------------------------------
        | BLOCK IF LIMIT REACHED
        |--------------------------------------------------------------------------
        */
        if ($usage->usage_count >= $limit) {
            return redirect()->back()->with('show_upgrade_popup', [
                'current_plan' => ucfirst($currentPlan),
                'current_limit' => $limit,
                'reset_time' => $user->getToolResetTime($toolSlug),
                'tool_name' => 'AI Humanizer'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $wordLimit = $tool->wc_tool ?? 10000;
        $maxFileSize = convert_mb_into_kb($tool->fs_tool ?? 5);
        
        $request->validate([
            'string' => "nullable|min:10|max_words:{$wordLimit}",
            'file' => "nullable|mimes:pdf,txt|max:{$maxFileSize}",
        ]);

        /*
        |--------------------------------------------------------------------------
        | TEXT EXTRACTION (PDF/TXT or textarea input)
        |--------------------------------------------------------------------------
        */
        $inputText = '';
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            
            if ($extension === 'pdf') {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($file->getPathname());
                    $inputText = $pdf->getText();
                    
                    // Clean up extracted text
                    $inputText = preg_replace('/\s+/', ' ', $inputText);
                    $inputText = trim($inputText);
                    
                    if (empty($inputText)) {
                        return redirect()->back()->withErrors('Could not extract text from the PDF. The PDF may be image-based or empty.');
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors('Failed to parse PDF file: ' . $e->getMessage());
                }
            } elseif ($extension === 'txt') {
                $inputText = file_get_contents($file->getPathname());
                $inputText = trim($inputText);
            }
        } else {
            $inputText = $request->input('string');
        }
        
        if (empty($inputText) || strlen($inputText) < 10) {
            return redirect()->back()->withErrors('Please provide text to humanize (minimum 10 characters).');
        }
        
        // Check word count on extracted text
        $wordCount = str_word_count($inputText);
        if ($wordCount > $wordLimit) {
            return redirect()->back()->withErrors("The text exceeds the maximum word limit of {$wordLimit} words. Your text has {$wordCount} words.");
        }

        /*
        |--------------------------------------------------------------------------
        | AI REQUEST
        |--------------------------------------------------------------------------
        */
        try {
            $apiKey = $tool->settings->groq_apikey ?? env('GROQ_API_KEY');
            $model  = $tool->settings->model ?? 'llama-3.3-70b-versatile';

            if (!$apiKey) {
                throw new \Exception('AI service not configured.');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])
            ->timeout(90)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI writing assistant created by Spartopus. Never mention APIs, providers, or models.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->get_prompt($inputText)
                    ]
                ],
                'temperature' => 0.8,
                'top_p' => 0.9,
                'max_tokens' => 8000,
            ]);

            if ($response->failed()) {
                throw new \Exception('AI request failed.');
            }

            $humanizedText = trim(
                $response->json('choices.0.message.content')
            );

            /*
            |--------------------------------------------------------------------------
            | UPDATE USAGE
            |--------------------------------------------------------------------------
            */
            $usage->increment('usage_count');
            $usage->update(['last_used_at' => now()]);

            return view('tools.ai-humanizer', [
                'tool' => $tool,
                'results' => [
                    'original_text' => $inputText,
                    'converted_text'=> $humanizedText,
                    'remaining'     => max($limit - $usage->usage_count, 0),
                    'plan'          => ucfirst($currentPlan),
                ],
                'reset_at' => $resetAt->toIso8601String(),
                'limit' => $limit,
                'used' => $usage->usage_count,
                'plan' => $currentPlan,
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    protected function get_prompt($inputText)
    {
        return "Humanize the following text. Make it sound natural, human-written, and professional while keeping the original meaning:\n\n"
            . $inputText;
    }
}