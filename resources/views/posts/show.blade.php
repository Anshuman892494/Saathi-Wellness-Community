@extends('layouts.app')
@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->content), 160))

@section('content')

<div class="container py-4">
    <div class="row g-4">

        {{-- ── Main Post ────────────────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- Back link --}}
            <a href="{{ route('posts.index') }}" class="d-inline-flex align-items-center gap-1 mb-3 text-muted text-decoration-none"
               style="font-size:.875rem;transition:var(--transition)">
                <i class="bi bi-arrow-left"></i> Back to Community
            </a>

            <article class="card-wellness p-4 p-md-5 mb-4">
                {{-- Category & meta --}}
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    <span class="category-badge cat-{{ $post->category ?? 'general' }}">
                        {{ ucfirst(str_replace('-', ' ', $post->category ?? 'general')) }}
                    </span>
                    <span class="text-muted" style="font-size:.8rem">
                        <i class="bi bi-clock me-1"></i>{{ $post->created_at->format('M d, Y') }}
                    </span>
                    <span class="text-muted" style="font-size:.8rem">
                        <i class="bi bi-eye me-1"></i>{{ number_format($post->views ?? 0) }} views
                    </span>
                </div>

                <h1 class="mb-3" style="font-family:var(--font-display);font-size:clamp(1.5rem,4vw,2rem)">
                    {{ $post->title }}
                </h1>

                {{-- Author --}}
                <div class="d-flex align-items-center gap-2 mb-4 pb-4" style="border-bottom:1px solid var(--border-color)">
                    <div class="avatar-sm">{{ strtoupper(substr($post->user->name ?? '?', 0, 1)) }}</div>
                    <div>
                        <a href="{{ route('profile.show', $post->user_id) }}"
                           class="fw-600 text-decoration-none" style="color:var(--brand-green);font-size:.9rem">
                            {{ $post->user->name ?? 'Anonymous' }}
                        </a>
                        <div class="text-muted" style="font-size:.78rem">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="post-body mb-4">
                    {!! nl2br(e($post->content)) !!}
                </div>

                {{-- Tags --}}
                @if($post->tags && count($post->tags))
                <div class="mb-4">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('posts.index') }}?search={{ $tag }}" class="tag-pill">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
                @endif

                {{-- Interaction bar --}}
                <div class="d-flex align-items-center flex-wrap gap-2 py-3" style="border-top:1px solid var(--border-color);border-bottom:1px solid var(--border-color)">

                    {{-- Like button --}}
                    @auth
                    <button id="likeBtn"
                            class="btn-like {{ $post->isLikedBy((string) Auth::user()->_id) ? 'liked' : '' }}"
                            data-post-id="{{ $post->_id }}">
                        <i class="bi bi-heart{{ $post->isLikedBy((string) Auth::user()->_id) ? '-fill' : '' }} me-1"></i>
                        <span id="likesCount">{{ $post->likes_count }}</span> Likes
                    </button>

                    {{-- Bookmark button --}}
                    <button id="bookmarkBtn"
                            class="btn-bookmark {{ in_array((string)$post->_id, Auth::user()->bookmarks ?? []) ? 'bookmarked' : '' }}"
                            data-post-id="{{ $post->_id }}">
                        <i class="bi bi-bookmark{{ in_array((string)$post->_id, Auth::user()->bookmarks ?? []) ? '-fill' : '' }} me-1"></i>
                        Save
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn-like">
                        <i class="bi bi-heart me-1"></i>{{ $post->likes_count }} Likes
                    </a>
                    @endauth

                    {{-- Comment count --}}
                    <span class="btn-like" style="cursor:default">
                        <i class="bi bi-chat-fill me-1" style="color:var(--brand-teal)"></i>
                        {{ $comments->count() }} Comments
                    </span>

                    {{-- Edit/Delete (author only) --}}
                    @auth
                    @if((string) $post->user_id === (string) Auth::user()->_id)
                    <div class="ms-auto d-flex gap-2">
                        <a href="{{ route('posts.edit', $post->_id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('posts.destroy', $post->_id) }}"
                              onsubmit="return confirm('Delete this post? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                    @endif
                    @endauth
                </div>
            </article>

            {{-- ── Comments ──────────────────────────────────── --}}
            <div class="mb-3">
                <h5 class="fw-700">💬 {{ $comments->count() }} Comment{{ $comments->count() != 1 ? 's' : '' }}</h5>
            </div>

            {{-- Add comment form --}}
            @auth
            <div class="card-wellness p-3 mb-4">
                <div class="d-flex gap-3">
                    <div class="avatar-sm flex-shrink-0">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <form method="POST" action="{{ route('comments.store', $post->_id) }}" class="flex-grow-1">
                        @csrf
                        <textarea name="comment" class="form-control mb-2 @error('comment') is-invalid @enderror"
                                  rows="3" placeholder="Share a supportive thought or tip…" required></textarea>
                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i>Post Comment
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card-wellness p-3 mb-4 text-center">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Sign in to comment</a>
            </div>
            @endauth

            {{-- Comments list --}}
            @forelse($comments as $comment)
            <div class="comment-card">
                <div class="d-flex align-items-start gap-3">
                    <div class="comment-avatar flex-shrink-0">
                        {{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-600" style="font-size:.875rem;color:var(--brand-green)">
                                {{ $comment->user->name ?? 'Anonymous' }}
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted" style="font-size:.75rem">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                                @auth
                                @if((string) $comment->user_id === (string) Auth::user()->_id)
                                <form method="POST" action="{{ route('comments.destroy', $comment->_id) }}"
                                      onsubmit="return confirm('Remove this comment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 text-danger" style="font-size:.8rem;background:transparent">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </div>
                        <p class="mb-0" style="font-size:.9rem;color:var(--text-primary)">{{ $comment->comment }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <span style="font-size:2rem">💭</span>
                <p class="mt-2">No comments yet. Start the conversation!</p>
            </div>
            @endforelse

        </div>

        {{-- ── Sidebar ────────────────────────────────────────── --}}
        <div class="col-lg-4">

            {{-- Related Posts --}}
            @if($relatedPosts->count())
            <div class="sidebar-card mb-3">
                <h6>Related Posts</h6>
                @foreach($relatedPosts as $rp)
                <div class="mb-2 pb-2" style="border-bottom:1px solid var(--border-color)">
                    <a href="{{ route('posts.show', $rp->_id) }}"
                       class="text-decoration-none" style="font-size:.875rem;color:var(--text-primary)">
                        {{ Str::limit($rp->title, 55) }}
                    </a>
                    <div class="text-muted" style="font-size:.75rem">{{ $rp->created_at->diffForHumans() }}</div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Author card --}}
            <div class="sidebar-card">
                <h6>About the Author</h6>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="avatar-sm">{{ strtoupper(substr($post->user->name ?? '?', 0, 1)) }}</div>
                    <a href="{{ route('profile.show', $post->user_id) }}"
                       class="fw-600 text-decoration-none" style="color:var(--brand-green)">
                        {{ $post->user->name ?? 'Anonymous' }}
                    </a>
                </div>
                @if($post->user && $post->user->bio)
                    <p class="text-muted small mb-0">{{ $post->user->bio }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Like button (AJAX) ────────────────────────────────────────
const likeBtn = document.getElementById('likeBtn');
if (likeBtn) {
    likeBtn.addEventListener('click', async function () {
        const postId = this.dataset.postId;
        const resp = await fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        const data = await resp.json();
        document.getElementById('likesCount').textContent = data.likes_count;
        const icon = likeBtn.querySelector('i');
        if (data.liked) {
            likeBtn.classList.add('liked');
            icon.className = 'bi bi-heart-fill me-1';
        } else {
            likeBtn.classList.remove('liked');
            icon.className = 'bi bi-heart me-1';
        }
        // pulse animation
        likeBtn.style.transform = 'scale(1.15)';
        setTimeout(() => likeBtn.style.transform = '', 200);
    });
}

// ── Bookmark button (AJAX) ────────────────────────────────────
const bookmarkBtn = document.getElementById('bookmarkBtn');
if (bookmarkBtn) {
    bookmarkBtn.addEventListener('click', async function () {
        const postId = this.dataset.postId;
        const resp = await fetch(`/posts/${postId}/bookmark`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        const data = await resp.json();
        const icon = bookmarkBtn.querySelector('i');
        if (data.bookmarked) {
            bookmarkBtn.classList.add('bookmarked');
            icon.className = 'bi bi-bookmark-fill me-1';
        } else {
            bookmarkBtn.classList.remove('bookmarked');
            icon.className = 'bi bi-bookmark me-1';
        }
    });
}
</script>
@endpush
@endsection
