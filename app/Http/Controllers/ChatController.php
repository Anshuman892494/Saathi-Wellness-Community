<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\DailyStat;
use App\Models\Post;
use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    /**
     * Handle user message and return Saathi AI response.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'persona' => 'required|string|in:yogi,shakti,mitra',
        ]);

        $user = Auth::user();
        $userMessage = $request->message;
        $persona = $request->persona;

        // 1. Fetch History (last 10 messages)
        $historyData = ChatMessage::where('user_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse();

        $history = [];
        foreach ($historyData as $msg) {
            $history[] = ['role' => $msg->role, 'content' => $msg->content];
        }
        $history[] = ['role' => 'user', 'content' => $userMessage];

        // 2. Gather Context
        $context = [
            'recent_posts' => Post::where('user_id', (string) $user->_id)->latest()->limit(3)->pluck('title')->toArray(),
            'bookmarks' => Post::whereIn('_id', $user->bookmarks ?? [])->pluck('category')->unique()->toArray(),
            'stats' => DailyStat::where('user_id', (string) $user->_id)->where('date', now()->toDateString())->first(),
        ];

        // 3. Get AI Response
        $aiResponse = $this->groq->companionChat($history, $persona, $context);

        if (isset($aiResponse['error'])) {
            return response()->json($aiResponse, 500);
        }

        // 4. Parse Smart Tags [UPDATE_WATER:X] etc.
        $this->handleSmartUpdates($user, $aiResponse);

        // Clean response from tags for the user
        $cleanResponse = preg_replace('/\[UPDATE_.*?\]/', '', $aiResponse);

        // 5. Save Messages
        ChatMessage::create(['user_id' => (string) $user->_id, 'role' => 'user', 'content' => $userMessage, 'persona' => $persona]);
        ChatMessage::create(['user_id' => (string) $user->_id, 'role' => 'assistant', 'content' => $cleanResponse, 'persona' => $persona]);

        return response()->json([
            'reply' => $cleanResponse,
        ]);
    }

    /**
     * Parse AI response for health updates and save to DailyStat.
     */
    protected function handleSmartUpdates($user, $response)
    {
        $date = now()->toDateString();
        $stat = DailyStat::firstOrCreate(['user_id' => (string) $user->_id, 'date' => $date]);

        // Water update
        if (preg_match('/\[UPDATE_WATER:(\d+(\.\d+)?)\]/', $response, $matches)) {
            $stat->increment('water_liters', (float) $matches[1]);
        }

        // Meditation update
        if (preg_match('/\[UPDATE_MEDITATION:(\d+)\]/', $response, $matches)) {
            $stat->increment('meditation_minutes', (int) $matches[1]);
        }
        
        // Steps update
        if (preg_match('/\[UPDATE_STEPS:(\d+)\]/', $response, $matches)) {
            $stat->increment('steps', (int) $matches[1]);
        }
    }

    /**
     * Fetch chat history for initial UI load.
     */
    public function getHistory()
    {
        $history = ChatMessage::where('user_id', (string) Auth::user()->_id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->reverse()
            ->values();

        return response()->json($history);
    }
}
