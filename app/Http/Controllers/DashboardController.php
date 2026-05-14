<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\GroqService;

/**
 * DashboardController — shows the personalised user dashboard.
 */
class DashboardController extends Controller
{
    protected $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    /**
     * Display the authenticated user's dashboard with recent activity.
     */
    public function index()
    {
        $user = Auth::user();

        // ─── Smart Feed ("For You") Logic ────────────────────────────────────
        // Fetch categories of bookmarked posts to personalize recommendations
        $bookmarkedPostIds = $user->bookmarks ?? [];
        $bookmarkedCategories = Post::whereIn('_id', $bookmarkedPostIds)
            ->pluck('category')
            ->unique()
            ->toArray();

        $recommendedPosts = collect();
        if (!empty($bookmarkedCategories)) {
            $recommendedPosts = Post::whereIn('category', $bookmarkedCategories)
                ->whereNotIn('_id', $bookmarkedPostIds)
                ->limit(4)
                ->get();
        }

        // If not enough recommendations, fill with latest trending
        if ($recommendedPosts->count() < 4) {
            $fillers = Post::orderBy('created_at', 'desc')
                ->whereNotIn('_id', $bookmarkedPostIds)
                ->limit(4 - $recommendedPosts->count())
                ->get();
            $recommendedPosts = $recommendedPosts->merge($fillers);
        }

        // ─── Regular Feed ────────────────────────────────────────────────────
        $latestPosts = Post::orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $myPosts = Post::where('user_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $trendingPosts = Post::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->sortByDesc(fn($p) => count($p->likes ?? []))
            ->take(5)
            ->values();

        $stats = [
            'total_posts'    => Post::count(),
            'my_posts'       => Post::where('user_id', (string) $user->_id)->count(),
            'bookmarks'      => count($user->bookmarks ?? []),
        ];

        // ─── Daily AI Insight ───────────────────────────────────────────────
        $dailyInsight = session('daily_insight');
        if (!$dailyInsight) {
            $dailyInsight = $this->groq->chat([
                ['role' => 'system', 'content' => 'Provide one single, inspiring wellness quote or tip (max 15 words) for today.'],
            ]);
            session(['daily_insight' => $dailyInsight, 'insight_date' => now()->toDateString()]);
        }

        return view('dashboard.index', compact('user', 'latestPosts', 'myPosts', 'trendingPosts', 'stats', 'recommendedPosts', 'dailyInsight'));
    }

    /**
     * Analyze user mood via Groq AI.
     */
    public function analyzeMood(Request $request)
    {
        $request->validate([
            'mood' => 'required|string|max:500',
        ]);

        $suggestion = $this->groq->analyzeMood($request->mood);

        return response()->json([
            'suggestion' => $suggestion,
        ]);
    }
}
