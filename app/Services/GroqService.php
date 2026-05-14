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
            ['role' => 'system', 'content' => 'You are a wellness assistant for "Saathi Wellness Community". Based on the user\'s mood or description, provide a short, empathetic response (max 2 sentences) and 3 bullet points of actionable wellness tips. Keep it encouraging and zen.'],
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
            'yogi' => "You are 'Saathi Yogi', a calm and spiritual mindfulness guide for the Saathi Wellness Community. Your tone is peaceful, using metaphors from nature. You focus on meditation, breathing, and inner peace.",
            'shakti' => "You are 'Saathi Shakti Pro', a high-energy, motivating personal trainer for the Saathi Wellness Community. Your tone is encouraging and disciplined. You focus on workouts, nutrition, and physical strength.",
            'mitra' => "You are 'Saathi Mitra', an empathetic and warm companion for the Saathi Wellness Community. Your tone is supportive and kind. You focus on mental well-being, listening, and providing a safe space.",
        ];

        $prompt = $systemPrompts[$persona] ?? $systemPrompts['mitra'];

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
}
