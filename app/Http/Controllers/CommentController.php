<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CommentController — handles creating and deleting comments on posts.
 */
class CommentController extends Controller
{
    /** Store a new comment on a post. */
    public function store(Request $request, string $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Verify the post exists
        Post::findOrFail($postId);

        Comment::create([
            'post_id' => $postId,
            'user_id' => (string) Auth::user()->_id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comment added! 💬');
    }

    /** Delete a comment (only the comment author can delete). */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        if ((string) $comment->user_id !== (string) Auth::user()->_id) {
            abort(403, 'Unauthorized action.');
        }

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comment removed.');
    }
}
