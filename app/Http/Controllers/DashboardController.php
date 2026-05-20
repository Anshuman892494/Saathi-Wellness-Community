<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\DailyStat;
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

        // Fetch or create today's daily stats
        $dailyStat = DailyStat::firstOrCreate(
            ['user_id' => (string) $user->_id, 'date' => now()->toDateString()],
            ['water_liters' => 0.0, 'steps' => 0, 'meditation_minutes' => 0, 'sleep_hours' => 0.0]
        );

        // ─── Daily AI Insight ───────────────────────────────────────────────
        $dailyInsight = session('daily_insight');
        if (!$dailyInsight) {
            $dailyInsight = $this->groq->chat([
                ['role' => 'system', 'content' => 'Provide one single, inspiring wellness quote or tip (max 15 words) for today.'],
            ]);
            session(['daily_insight' => $dailyInsight, 'insight_date' => now()->toDateString()]);
        }

        // ─── Admin Users List ───────────────────────────────────────────────
        $adminUsersList = collect();
        if ($user->isAdmin()) {
            $adminUsersList = \App\Models\User::all();
            foreach ($adminUsersList as $u) {
                $u->posts_count = Post::where('user_id', (string) $u->_id)->count();
                $u->comments_count = \App\Models\Comment::where('user_id', (string) $u->_id)->count();
            }
        }

        return view('dashboard.index', compact('user', 'latestPosts', 'myPosts', 'trendingPosts', 'stats', 'recommendedPosts', 'dailyInsight', 'adminUsersList', 'dailyStat'));
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

    /**
     * Update the user's daily stats.
     */
    public function updateStats(Request $request)
    {
        $request->validate([
            'water_liters' => 'nullable|numeric|min:0|max:10',
            'steps' => 'nullable|integer|min:0|max:100000',
            'meditation_minutes' => 'nullable|integer|min:0|max:1440',
            'sleep_hours' => 'nullable|numeric|min:0|max:24',
        ]);

        $user = Auth::user();
        $stat = DailyStat::firstOrCreate([
            'user_id' => (string) $user->_id,
            'date' => now()->toDateString()
        ]);

        if ($request->has('water_liters')) {
            $stat->water_liters = (float) $request->water_liters;
        }
        if ($request->has('steps')) {
            $stat->steps = (int) $request->steps;
        }
        if ($request->has('meditation_minutes')) {
            $stat->meditation_minutes = (int) $request->meditation_minutes;
        }
        if ($request->has('sleep_hours')) {
            $stat->sleep_hours = (float) $request->sleep_hours;
        }

        $stat->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'stat' => $stat
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Daily stats updated successfully!');
    }
}
