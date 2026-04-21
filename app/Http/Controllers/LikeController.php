<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LikeController — toggles a like on a post for the authenticated user.
 */
class LikeController extends Controller
{
    /**
     * Toggle the like status of a post.
     * Adds user ID to the likes array if not present; removes it if present.
     */
    public function toggle(Request $request, string $postId)
    {
        $post   = Post::findOrFail($postId);
        $userId = (string) Auth::user()->_id;
        $likes  = $post->likes ?? [];

        if (in_array($userId, $likes)) {
            // Already liked → unlike
            $likes = array_values(array_filter($likes, fn($id) => $id !== $userId));
            $liked = false;
        } else {
            // Not yet liked → like
            $likes[] = $userId;
            $liked   = true;
        }

        $post->update(['likes' => $likes]);

        // Respond to AJAX requests with JSON; otherwise redirect
        if ($request->expectsJson()) {
            return response()->json([
                'liked'      => $liked,
                'likes_count' => count($likes),
            ]);
        }

        return redirect()->route('posts.show', $postId);
    }
}
