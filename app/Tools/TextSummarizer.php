<?php

namespace App\Tools;

use App\Models\Tool;
use App\Models\ToolUsage;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TextSummarizer implements ToolInterface
{
    /*
    |--------------------------------------------------------------------------
    | PLAN LIMITS & RESET HOURS
    |--------------------------------------------------------------------------
    */
    protected $limits = [
        'free'    => 2,
        'classic' => 10,
        'plus'    => 20,
        'pro'     => 25,
    ];

    protected $resetHours = [
        'free'    => 24,
        'classic' => 22,
        'plus'    => 20,
        'pro'     => 18,
    ];

    /**
     * Render the tool page with usage data
     */
    public function render(Request $request, Tool $tool)
    {
        if (!Auth::check()) {
            return view('tools.text-summarizer', [
                'tool'  => $tool,
                'used'  => 0,
                'limit' => 2,
                'plan'  => 'free',
                'reset_hours'       => 24,
                'minutes_remaining' => 1440,
                'reset_at'          => now()->addHours(24)->toIso8601String(),
            ]);
        }

        $data = $this->getUsageData();

        return view('tools.text-summarizer', array_merge(
            ['tool' => $tool],
            $data
        ));
    }

    /**
     * Handle the summarization request
     */
    public function handle(Request $request, Tool $tool)
    {
        if (!Auth::check()) {
            return redirect()->back()->withErrors(__('Please login to use this tool.'));
        }

        $user = Auth::user();
        $currentPlan = $user->planLevel();
        $toolSlug = 'text-summarizer';

        $limit = $this->limits[$currentPlan] ?? 2;
        $resetInterval = $this->resetHours[$currentPlan] ?? 24;
        $resetMinutes = $resetInterval * 60;

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
        | PLAN-SPECIFIC RESET
        |--------------------------------------------------------------------------
        */
        $minutesSinceLastUse = 0;
        if ($usage->last_used_at) {
            $minutesSinceLastUse = Carbon::parse($usage->last_used_at)->diffInMinutes(now());
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
        | BLOCK IF LIMIT REACHED
        |--------------------------------------------------------------------------
        */
        if ($usage->usage_count >= $limit) {
            return redirect()->back()->with('show_upgrade_popup', [
                'current_plan'  => ucfirst($currentPlan),
                'current_limit' => $limit,
                'tool_name'     => 'Text Summarizer'
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
            'file'   => "nullable|mimes:pdf,txt|max:{$maxFileSize}",
            'length' => 'nullable|in:25,50,75,90',
            'format' => 'nullable|in:paragraph,bullets,brief',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TEXT EXTRACTION
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
                    $inputText = preg_replace('/\s+/', ' ', $inputText);
                    $inputText = trim($inputText);

                    if (empty($inputText)) {
                        return redirect()->back()->withErrors('Could not extract text from the PDF.');
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors('Failed to parse PDF: ' . $e->getMessage());
                }
            } elseif ($extension === 'txt') {
                $inputText = file_get_contents($file->getPathname());
                $inputText = trim($inputText);
            }
        } else {
            $inputText = $request->input('string');
        }

        if (empty($inputText) || strlen($inputText) < 10) {
            return redirect()->back()->withErrors('Please provide text to summarize (minimum 10 characters).');
        }

        $wordCount = str_word_count($inputText);
        if ($wordCount > $wordLimit) {
            return redirect()->back()->withErrors("Text exceeds the maximum word limit of {$wordLimit} words.");
        }

        /*
        |--------------------------------------------------------------------------
        | BUILD PROMPT
        |--------------------------------------------------------------------------
        */
        $lengthOption = $request->input('length', '50');
        $formatOption = $request->input('format', 'paragraph');

        $prompt = $this->buildPrompt($inputText, $lengthOption, $formatOption);

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
                        'content' => 'You are an expert text summarizer created by Sparktopus. You produce clear, accurate summaries. Never mention APIs, providers, or models. Always respond ONLY with the summary text, no preamble.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.5,
                'top_p' => 0.9,
                'max_tokens' => 4000,
            ]);

            if ($response->failed()) {
                throw new \Exception('AI request failed.');
            }

            $summaryText = trim($response->json('choices.0.message.content'));

            /*
            |--------------------------------------------------------------------------
            | UPDATE USAGE
            |--------------------------------------------------------------------------
            */
            $usage->increment('usage_count');
            $usage->update(['last_used_at' => now()]);

            /*
            |--------------------------------------------------------------------------
            | CALCULATE STATS
            |--------------------------------------------------------------------------
            */
            $originalWords = str_word_count($inputText);
            $summaryWords  = str_word_count($summaryText);
            $compressionPct = $originalWords > 0
                ? round((1 - ($summaryWords / $originalWords)) * 100)
                : 0;

            $originalReadMin = max(1, round($originalWords / 200));
            $summaryReadMin  = max(1, round($summaryWords / 200));
            $timeSavedPct    = $originalReadMin > 0
                ? round((1 - ($summaryReadMin / $originalReadMin)) * 100)
                : 0;

            $usageData = $this->getUsageData();

            return view('tools.text-summarizer', array_merge([
                'tool'    => $tool,
                'results' => [
                    'original_text'   => $inputText,
                    'summary_text'    => $summaryText,
                    'original_words'  => $originalWords,
                    'summary_words'   => $summaryWords,
                    'compression_pct' => $compressionPct,
                    'original_read_min' => $originalReadMin,
                    'summary_read_min'  => $summaryReadMin,
                    'time_saved_pct'    => $timeSavedPct,
                    'length_option'     => $lengthOption,
                    'format_option'     => $formatOption,
                ],
            ], $usageData));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Build the AI prompt with options
     */
    protected function buildPrompt(string $text, string $length, string $format): string
    {
        $lengthMap = [
            '25' => 'very short (about 25% of the original length)',
            '50' => 'medium (about 50% of the original length)',
            '75' => 'detailed (about 75% of the original length)',
            '90' => 'comprehensive (about 90% of the original length)',
        ];

        $formatMap = [
            'paragraph' => 'in flowing paragraph form',
            'bullets'   => 'as concise bullet points',
            'brief'     => 'as a professional executive brief with sections',
        ];

        $lengthDesc = $lengthMap[$length] ?? $lengthMap['50'];
        $formatDesc = $formatMap[$format] ?? $formatMap['paragraph'];

        return "Summarize the following text. "
             . "Make the summary {$lengthDesc}, formatted {$formatDesc}. "
             . "Keep the original meaning and key points intact.\n\n"
             . $text;
    }

    /**
     * Get current usage data for the authenticated user
     */
    protected function getUsageData(): array
    {
        $user = Auth::user();
        $currentPlan = $user->planLevel();
        $toolSlug = 'text-summarizer';

        $limit = $this->limits[$currentPlan] ?? 2;
        $resetInterval = $this->resetHours[$currentPlan] ?? 24;
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
            $minutesSinceLastUse = Carbon::parse($usage->last_used_at)->diffInMinutes(now());
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
            ? Carbon::parse($usage->last_used_at)->addMinutes($resetMinutes)
            : now()->addMinutes($resetMinutes);

        return [
            'used'              => $usage->usage_count,
            'limit'             => $limit,
            'plan'              => $currentPlan,
            'reset_hours'       => $resetInterval,
            'minutes_remaining' => $minutesRemaining,
            'reset_at'          => $resetAt->toIso8601String(),
        ];
    }
}
