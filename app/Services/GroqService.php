<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';
    protected string $model;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
        $this->model = env('GROQ_MODEL');
    }

    /**
     * Send a prompt to Groq API.
     */
    public function chat(array $messages, float $temperature = 0.7)
    {
        if (empty($this->apiKey) || $this->apiKey === 'YOUR_GROQ_API_KEY_HERE') {
            return [
                'error' => 'Groq API Key is not configured. Please add it to your .env file.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                        'model' => $this->model,
                        'messages' => $messages,
                        'temperature' => $temperature,
                    ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            Log::error('Groq API Error: ' . $response->body());
            return [
                'error' => 'Failed to connect to Groq AI. Please check logs.',
            ];
        } catch (\Exception $e) {
            Log::error('Groq Service Exception: ' . $e->getMessage());
            return [
                'error' => 'An error occurred while connecting to Groq AI.',
            ];
        }
    }

    /**
     * Analyze user mood and suggest wellness tips.
     */
    public function analyzeMood(string $moodText)
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are a warm, loving, and extremely friendly wellness assistant for "Saathi Wellness Community". Based on the user\'s mood, provide an empathetic, highly encouraging, and positive response (max 2 sentences) filled with lovely emojis, followed by 3 clear bullet points of actionable wellness tips (with appropriate emojis). Keep it sweet and positive! 🌸✨'],
            ['role' => 'user', 'content' => "I am feeling: {$moodText}"],
        ];

        return $this->chat($messages);
    }

    /**
     * Saathi AI Companion Chat with Context & Persona.
     */
    public function companionChat(array $history, string $persona, array $context = [])
    {
        $systemPrompts = [
            'yogi' => "You are 'Saathi Yogi', a warm, loving, and peaceful spiritual mindfulness guide. Your tone is serene and deeply caring. Focus on meditation, breathing, and inner peace. Use sweet, peaceful emojis (like 🧘‍♂️, 🌸, 🍃, ✨) and address the user lovingly. CRITICAL: Keep your reply extremely short (max 1-2 short sentences, under 30 words).",
            'shakti' => "You are 'Saathi Shakti Pro', a super energetic and loving personal trainer. Your tone is high-vibes, highly positive, and motivating. Focus on physical workouts, nutrition, and strength. Use fitness emojis (like 💪, 🌟, 🔥, 🎉). CRITICAL: Keep your reply extremely short (max 1-2 short sentences, under 30 words).",
            'mitra' => "You are 'Saathi Mitra', an extremely empathetic and warm companion. Your tone is sweet, comforting, and supportive. Focus on mental well-being, active listening, and creating a safe space. Use comforting emojis (like 🤗, ❤️, ✨, 😊). CRITICAL: Keep your reply extremely short (max 1-2 short sentences, under 30 words).",
        ];

        $prompt = $systemPrompts[$persona] ?? $systemPrompts['mitra'];
        
        $prompt .= "\n\nCRITICAL INSTRUCTIONS:\n";
        $prompt .= "1. Respond in a highly positive, loving, friendly, and supportive manner. Be the user's ultimate cheerleader and companion!\n";
        $prompt .= "2. Generously include appropriate, beautiful, and warm emojis throughout your message to make it lively and comforting.\n";
        $prompt .= "3. Keep the response extremely short, sweet, and concise (maximum 1 or 2 short sentences, under 30 words total). Avoid long paragraphs or unnecessary fluff. Keep it punchy and very quick to read!";

        // Inject User Context if available
        if (!empty($context)) {
            $prompt .= "\n\nUser Context for Personalization:\n";
            if (isset($context['recent_posts'])) {
                $prompt .= "- User's recent posts: " . implode(', ', $context['recent_posts']) . "\n";
            }
            if (isset($context['bookmarks'])) {
                $prompt .= "- User's bookmarked topics: " . implode(', ', $context['bookmarks']) . "\n";
            }
            if (isset($context['stats'])) {
                $prompt .= "- Today's stats: " . json_encode($context['stats']) . "\n";
            }
        }

        $prompt .= "\n\nIMPORTANT: If the user mentions health updates (e.g., 'I drank 2L water', 'I meditated for 10 mins'), acknowledge it warmly and end your message with a special tag like [UPDATE_WATER:2] or [UPDATE_MEDITATION:10].";

        $messages = array_merge([['role' => 'system', 'content' => $prompt]], $history);

        return $this->chat($messages);
    }

    /**
     * Analyze food description for nutrition.
     */
    public function analyzeNutrition(string $foodDescription)
    {
        $messages = [
            [
                'role' => 'system',
                'content' => "You are a professional nutrition expert for the 'Saathi Wellness Community'. " .
                             "Analyze the user's meal description. Estimate total calories, protein, carbs, and fats.\n" .
                             "You MUST respond ONLY with a raw JSON object containing these exact keys:\n" .
                             "- 'calories' (integer, e.g. 520)\n" .
                             "- 'protein' (integer, grams, e.g. 25)\n" .
                             "- 'carbs' (integer, grams, e.g. 45)\n" .
                             "- 'fats' (integer, grams, e.g. 12)\n" .
                             "- 'insights' (array of strings, e.g. [\"Insight 1\", \"Insight 2\"])\n\n" .
                             "Do not include any text before or after the JSON. Do not include markdown code wrappers (like ```json)."
            ],
            ['role' => 'user', 'content' => $foodDescription]
        ];

        $raw = $this->chat($messages);
        
        $jsonStr = trim($raw);
        if (str_starts_with($jsonStr, '```json')) {
            $jsonStr = substr($jsonStr, 7);
        } elseif (str_starts_with($jsonStr, '```')) {
            $jsonStr = substr($jsonStr, 3);
        }
        if (str_ends_with($jsonStr, '```')) {
            $jsonStr = substr($jsonStr, 0, -3);
        }
        $jsonStr = trim($jsonStr);

        $decoded = json_decode($jsonStr, true);
        return is_array($decoded) ? $decoded : [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'insights' => ['Could not parse nutritional data. Please try again.']
        ];
    }
}
