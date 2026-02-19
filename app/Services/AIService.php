<?php

namespace App\Services;

use App\Models\Tool;
use Illuminate\Support\Facades\Http;

class AIService
{
    /**
     * Generate AI response based on tool slug and prompt
     *
     * @param string $toolSlug
     * @param string $prompt
     * @param Tool $tool
     * @return string
     * @throws \Exception
     */
    public function generateResponse(string $toolSlug, string $prompt, Tool $tool): string
    {
        $apiKey = $tool->settings->groq_apikey ?? env('GROQ_API_KEY');
        $model  = $tool->settings->model ?? 'llama-3.3-70b-versatile';

        if (!$apiKey) {
            throw new \Exception('AI service not configured.');
        }

        $systemPrompt = 'You are an AI writing assistant created by Spartopus. Never mention APIs, providers, or models.';
        $userPrompt = $this->getPromptByTool($toolSlug, $prompt);

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
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ],
            'temperature' => 0.7,
            'top_p' => 0.9,
            'max_tokens' => 8000,
        ]);

        if ($response->failed()) {
            throw new \Exception('AI request failed: ' . ($response->json('error.message') ?? 'Unknown error'));
        }

        return trim($response->json('choices.0.message.content'));
    }

    /**
     * Get specific prompt based on tool slug
     */
    protected function getPromptByTool(string $toolSlug, string $inputText): string
    {
        return match ($toolSlug) {
            'ai-humaniser' => "Humanize the following text. Make it sound natural, human-written, and professional while keeping the original meaning:\n\n" . $inputText,
            'text-summarizer' => "Summarize the following text clearly and concisely, highlighting the key points:\n\n" . $inputText,
            'grammar-checker' => "Check the following text for grammar, spelling, and punctuation errors. Provide the corrected version and briefly explain the changes if necessary:\n\n" . $inputText,
            'blog-title-generator' => (function() use ($inputText) {
                $data = json_decode($inputText, true);
                $topic = $data['topic'] ?? $inputText;
                $tone = $data['tone'] ?? 'Catchy';
                $audience = $data['audience'] ?? 'General';
                return "Generate 10 creative, high-converting blog titles about \"{$topic}\".\n" .
                       "Target Audience: {$audience}\n" .
                       "Tone: {$tone}\n" .
                       "Return ONLY a valid JSON array of strings (e.g. [\"Title 1\", \"Title 2\"]). Do not include markdown formatting or explanations.";
            })(),
            'seo-content-optimizer' => "Optimize the following content for SEO. Improve keyword placement, readability, and overall structure while maintaining the original message:\n\n" . $inputText,
            default => $inputText,
        };
    }
}
