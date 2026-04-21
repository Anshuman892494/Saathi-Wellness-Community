<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * PostController — full CRUD for community posts.
 */
class PostController extends Controller
{
    // ─── Index (List All Posts) ───────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Post::query();

        // Search by keyword in title or content
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        // Sort: popular (by likes count) or latest (default)
        if ($request->get('sort') === 'popular') {
            $posts = $query->orderBy('created_at', 'desc')->get()
                ->sortByDesc(fn($p) => count($p->likes ?? []))
                ->values();
        } else {
            $posts = $query->orderBy('created_at', 'desc')->get();
        }

        $categories = ['general', 'fitness', 'mental-health', 'nutrition', 'meditation'];

        return view('posts.index', compact('posts', 'categories'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create()
    {
        $categories = ['general', 'fitness', 'mental-health', 'nutrition', 'meditation'];
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string|min:10',
            'category' => 'required|string',
        ]);

        Post::create([
            'user_id'  => (string) Auth::user()->_id,
            'title'    => $request->title,
            'content'  => $request->content,
            'category' => $request->category,
            'tags'     => $request->tags
                ? array_map('trim', explode(',', $request->tags))
                : [],
            'likes'    => [],
            'views'    => 0,
        ]);

        return redirect()->route('posts.index')->with('success', 'Your post has been shared with the community! 🌟');
    }

    // ─── Read (Single Post) ───────────────────────────────────────────────────

    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        // Increment view count
        $post->increment('views');

        $comments = Comment::where('post_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Related posts (same category, excluding current)
        $relatedPosts = Post::where('category', $post->category)
            ->where('_id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('posts.show', compact('post', 'comments', 'relatedPosts'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        // Only the author may edit
        if ((string) $post->user_id !== (string) Auth::user()->_id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = ['general', 'fitness', 'mental-health', 'nutrition', 'meditation'];
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        if ((string) $post->user_id !== (string) Auth::user()->_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string|min:10',
            'category' => 'required|string',
        ]);

        $post->update([
            'title'    => $request->title,
            'content'  => $request->content,
            'category' => $request->category,
            'tags'     => $request->tags
                ? array_map('trim', explode(',', $request->tags))
                : ($post->tags ?? []),
        ]);

        return redirect()->route('posts.show', $id)->with('success', 'Post updated successfully! ✏️');
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ((string) $post->user_id !== (string) Auth::user()->_id) {
            abort(403, 'Unauthorized action.');
        }

        // Remove all comments belonging to this post
        Comment::where('post_id', $id)->delete();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
