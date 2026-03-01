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
            'text-summarizer' => "Summarize the following text clearly and concisely, highlighting the key points. Do not use any emojis in the output:\n\n" . $inputText,
            'grammar-checker' => "Check the following text for grammar, spelling, and punctuation errors. Provide the corrected version and briefly explain the changes if necessary:\n\n" . $inputText,
            'blog-title-generator' => (function() use ($inputText) {
                $data = json_decode($inputText, true);
                $topic = $data['topic'] ?? $inputText;
                $tone = $data['tone'] ?? 'Catchy';
                $audience = $data['audience'] ?? 'General';
                
                $masterPrompts = [
                    'Clickbait' => "Create sensational, high-curiosity titles that make people MUST click. Use emotional hooks, 'you won't believe' style phrasing, and strong curiosity gaps to drive viral engagement.",
                    'Crazy Friendly' => "Write in a super energetic, warm, and vibes-heavy tone. Use friendly language, occasional exclamation marks, and keep it very informal as if talking to a best friend. Make it feel alive and extremely approachable.",
                    'SEO-Optimized' => "Focus on high click-through rate (CTR) and search engine visibility. Incorporate numbers (e.g., '7 Ways', 'Top 10'), lists, and power words. Prioritize clarity and keyword prominence for better search ranking.",
                    'Professional' => "Maintain a formal, authoritative, and industry-standard tone. Use precise language and focus on value propositions suitable for a B2B or executive audience.",
                    'Question-Based' => "Frame the titles as intriguing questions that the blog post promises to answer. Target common pain points or areas of curiosity.",
                    'How-To' => "Focus on instructional and educational value. Clearly state the benefit or skill the reader will acquire.",
                    'Listicle' => "Use a list format. Start with numbers and promise a specific set of tips, tools, or resources.",
                    'Catchy' => "Generate engaging and memorable titles that stand out with clever phrasing or strong verbs."
                ];

                $toneBrief = $masterPrompts[$tone] ?? $masterPrompts['Catchy'];

                return "Task: Generate 10 high-converting blog titles about \"{$topic}\".\n" .
                       "Target Audience: {$audience}\n" .
                       "Tone Strategy: {$toneBrief}\n" .
                       "CRITICAL CONSTRAINTS:\n" .
                       "1. Each title MUST be 60 characters or less.\n" .
                       "2. DO NOT use any emojis.\n" .
                       "Return ONLY a valid JSON array of strings (e.g. [\"Title 1\", \"Title 2\"]). Do not include markdown formatting or explanations.";
            })(),
            'seo-content-optimizer' => "Optimize the following content for SEO. Improve keyword placement, readability, and overall structure while maintaining the original message:\n\n" . $inputText,
            'english-converter' => (function() use ($inputText) {
                $data = json_decode($inputText, true);
                $text = $data['text'] ?? $inputText;
                $targetLang = $data['target_lang'] ?? 'US English';
                $tone = $data['tone'] ?? 'Natural';
                
                return "Task: Translate/Convert the following English text meticulously.\n" .
                       "Target Language/Dialect: {$targetLang}\n" .
                       "Desired Tone: {$tone}\n" .
                       "Source Text:\n\"{$text}\"\n\n" .
                       "Instructions: Convert the text accurately while respecting local nuances, idioms, and the specified tone. If the target is a dialect like Nigerian Pidgin, ensure it sounds authentic. Return ONLY the converted text without any explanations or introductory remarks.";
            })(),
            'rewrite-article' => (function() use ($inputText) {
                $data = json_decode($inputText, true);
                $text = $data['text'] ?? $inputText;
                $mode = $data['mode'] ?? 'Smart';
                $creativity = $data['creativity'] ?? 'Medium';
                
                $modePrompts = [
                    'Smart' => "Rewrite the text to be clearer and more engaging while maintaining 100% of the original meaning. Use a balanced approach.",
                    'Creative' => "Use evocative language and varied sentence structures. Make it imaginative and vivid while staying true to the core message.",
                    'Professional' => "Use formal, sophisticated, and business-appropriate vocabulary. Ensure it sounds authoritative and polished.",
                    'Casual' => "Write in a relaxed, friendly, and conversational style. Use simple language as if talking to a friend.",
                    'Academic' => "Use scholarly language, objective tone, and complex structures suitable for research or formal reports.",
                    'Shorten' => "Condense the text to its absolute essentials. Remove fluff while retaining all key facts and the primary message.",
                    'Expand' => "Elaborate on the original ideas. Add descriptive detail and explanatory phrases to make it more comprehensive."
                ];

                $creativityPrompts = [
                    'Low' => "Stick very closely to the original phrasing with minimal changes.",
                    'Medium' => "Balance new phrasing with the original flow.",
                    'High' => "Radically change the sentence structures and vocabulary for a completely fresh perspective."
                ];

                $modeBrief = $modePrompts[$mode] ?? $modePrompts['Smart'];
                $creativityBrief = $creativityPrompts[$creativity] ?? $creativityPrompts['Medium'];

                return "Task: Rewrite the following article.\n" .
                       "Mode: {$mode}\n" .
                       "Creativity Level: {$creativity}\n" .
                       "Strategy: {$modeBrief} {$creativityBrief}\n" .
                       "Source Content:\n\"{$text}\"\n\n" .
                       "CRITICAL INSTRUCTIONS:\n" .
                       "1. Return ONLY the rewritten text.\n" .
                       "2. Do not include introductory phrases, quotes around the result, or explanations.\n" .
                       "3. Ensure the output is high-quality, coherent, and fits the requested mode.";
            })(),
            default => $inputText,
        };
    }
}
