<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * BookmarkController — save and remove bookmarked posts.
 */
class BookmarkController extends Controller
{
    /** Toggle bookmark status of a post for the current user. */
    public function toggle(Request $request, string $postId)
    {
        Post::findOrFail($postId); // ensure post exists

        $user      = Auth::user();
        $bookmarks = $user->bookmarks ?? [];

        if (in_array($postId, $bookmarks)) {
            $bookmarks = array_values(array_filter($bookmarks, fn($id) => $id !== $postId));
            $bookmarked = false;
        } else {
            $bookmarks[] = $postId;
            $bookmarked  = true;
        }

        User::where('_id', $user->_id)->update(['bookmarks' => $bookmarks]);

        if ($request->expectsJson()) {
            return response()->json(['bookmarked' => $bookmarked]);
        }

        return back()->with('success', $bookmarked ? 'Post saved to your bookmarks.' : 'Post removed from bookmarks.');
    }

    /** List all bookmarked posts for the authenticated user. */
    public function index()
    {
        $user      = Auth::user();
        $bookmarks = $user->bookmarks ?? [];

        $posts = Post::whereIn('_id', $bookmarks)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookmarks.index', compact('posts'));
    }
}
