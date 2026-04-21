<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController — shows the personalised user dashboard.
 */
class DashboardController extends Controller
{
    /**
     * Display the authenticated user's dashboard with recent activity.
     */
    public function index()
    {
        $user = Auth::user();

        // Latest posts from the community (most recent 6)
        $latestPosts = Post::orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Posts authored by this user
        $myPosts = Post::where('user_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top liked posts for "trending" widget
        $trendingPosts = Post::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->sortByDesc(fn($p) => count($p->likes ?? []))
            ->take(5)
            ->values();

        // Stats
        $stats = [
            'total_posts'    => Post::count(),
            'my_posts'       => Post::where('user_id', (string) $user->_id)->count(),
            'bookmarks'      => count($user->bookmarks ?? []),
        ];

        return view('dashboard.index', compact('user', 'latestPosts', 'myPosts', 'trendingPosts', 'stats'));
    }
}
