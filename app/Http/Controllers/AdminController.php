<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AdminController — handles administrative operations (managing users, posts, moderation).
 */
class AdminController extends Controller
{
    /**
     * List all registered users with their statistics.
     */
    public function usersIndex()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action. Administrators only.');
        }

        $users = User::all();

        // Calculate counts for posts and comments for each user in MongoDB
        foreach ($users as $user) {
            $user->posts_count = Post::where('user_id', (string) $user->_id)->count();
            $user->comments_count = Comment::where('user_id', (string) $user->_id)->count();
        }

        return view('admin.users', compact('users'));
    }

    /**
     * Permanently delete a user and all their associated posts, comments, and messages.
     */
    public function destroyUser(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action. Administrators only.');
        }

        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ((string) Auth::user()->_id === (string) $user->_id) {
            return back()->with('error', 'Aap swayam ko delete nahi kar sakte! ❌');
        }

        // Delete all posts authored by this user
        Post::where('user_id', $id)->delete();

        // Delete all comments written by this user
        Comment::where('user_id', $id)->delete();

        // Delete all chat messages sent by this user
        ChatMessage::where('user_id', $id)->delete();

        // Finally, delete the user account
        $user->delete();

        return back()->with('success', 'User aur unka sabhi data permanently delete kar diya gaya hai! 🗑️');
    }
}
