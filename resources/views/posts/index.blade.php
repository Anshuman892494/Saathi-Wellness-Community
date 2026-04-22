@extends('layouts.app')
@section('title', 'Community Posts')
@section('meta_description', 'Browse health & wellness posts from our community')

@section('content')

{{-- ── Page Hero ──────────────────────────────────────────────── --}}
<div class="page-hero">
    <div class="container">
        <h1 class="mb-2"><i class="bi bi-globe-americas text-brand me-2"></i>Community Pulse</h1>
        <p class="lead">Stories, tips, and experiences from our wellness community</p>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('posts.index') }}" class="mt-3" id="searchForm">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="search-bar-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" class="form-control" id="searchInput"
                               placeholder="Search posts…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <select name="category" class="form-select" style="border-radius:50px;min-width:160px">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('-', ' ', $cat)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="sort" class="form-select" style="border-radius:50px;min-width:140px">
                        <option value="latest" {{ request('sort','latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Liked</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search','category','sort']))
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary ms-1">Clear</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        {{-- ── Posts Grid ─────────────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- Result meta --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted" style="font-size:.875rem">
                    {{ $posts->count() }} post{{ $posts->count() != 1 ? 's' : '' }} found
                    @if(request('search')) for "{{ request('search') }}" @endif
                </span>
                @auth
                <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>New Post
                </a>
                @endauth
            </div>

            @if($posts->count())
                <div class="row g-3">
                    @foreach($posts as $post)
                    <div class="col-md-6">
                        <div class="card-wellness post-card h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="category-badge cat-{{ $post->category ?? 'general' }}">
                                    {{ ucfirst(str_replace('-', ' ', $post->category ?? 'general')) }}
                                </span>
                            </div>
                            @if($post->image)
                                <a href="{{ route('posts.show', $post->_id) }}" class="d-block mb-2" style="height: 140px; overflow: hidden; border-radius: 8px;">
                                    <img src="{{ $post->image }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                </a>
                            @endif
                            <a href="{{ route('posts.show', $post->_id) }}" class="post-title text-decoration-none d-block">
                                {{ $post->title }}
                            </a>
                            <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 110) }}</p>

                            {{-- Tags --}}
                            @if($post->tags && count($post->tags))
                            <div class="mb-2">
                                @foreach(array_slice($post->tags, 0, 3) as $tag)
                                    <a href="{{ route('posts.index') }}?search={{ $tag }}" class="tag-pill">
                                        #{{ $tag }}
                                    </a>
                                @endforeach
                            </div>
                            @endif

                            <div class="post-meta">
                                <span>
                                    <a href="{{ route('profile.show', $post->user_id) }}"
                                       style="color:var(--brand-green);font-size:.8rem;font-weight:600;text-decoration:none">
                                        {{ $post->user->name ?? 'Unknown' }}
                                    </a>
                                </span>
                                @auth
                                    <button type="button" class="btn btn-link p-0 text-decoration-none feed-like-btn meta-stat"
                                            data-post-id="{{ $post->_id }}"
                                            style="color: var(--text-muted);">
                                        <i class="bi bi-heart{{ $post->isLikedBy((string) Auth::user()->_id) ? '-fill' : '' }}" 
                                           style="color: {{ $post->isLikedBy((string) Auth::user()->_id) ? '#e85d73' : 'inherit' }}"></i> 
                                        <span class="like-count">{{ count($post->likes ?? []) }}</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="text-decoration-none meta-stat" style="color: var(--text-muted);">
                                        <i class="bi bi-heart"></i> {{ count($post->likes ?? []) }}
                                    </a>
                                @endauth

                                <a href="{{ route('posts.show', $post->_id) }}#comments" class="text-decoration-none meta-stat" style="color: var(--text-muted);">
                                    <i class="bi bi-chat-fill" style="color:var(--brand-teal)"></i> {{ $post->comments()->count() }}
                                </a>
                                <span class="meta-stat ms-auto text-muted" style="font-size:.75rem">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state card-wellness py-5 text-center">
                    <span class="empty-icon">🔍</span>
                    <h5>No posts found</h5>
                    <p class="text-muted">Try adjusting your search or be the first to post!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-primary mt-2">Create Post</a>
                    @endauth
                </div>
            @endif
        </div>

        {{-- ── Sidebar ──────────────────────────────────────────── --}}
        <div class="col-lg-4">
            {{-- Categories filter --}}
            <div class="sidebar-card">
                <h6>Browse by Category</h6>
                <div class="d-flex flex-column gap-1">
                    <a href="{{ route('posts.index') }}" class="sidebar-cat-link {{ !request('category') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill me-2"></i>All Posts
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('posts.index') }}?category={{ $cat }}"
                       class="sidebar-cat-link {{ request('category') == $cat ? 'active' : '' }}">
                        @php
                            $icons = ['general'=>'bi-chat-dots-fill','fitness'=>'bi-bicycle','mental-health'=>'bi-brain','nutrition'=>'bi-egg-fried','meditation'=>'bi-peace-fill'];
                        @endphp
                        <i class="bi {{ $icons[$cat] ?? 'bi-tag-fill' }} me-2"></i>
                        {{ ucfirst(str_replace('-', ' ', $cat)) }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- CTA for guests --}}
            @guest
            <div class="sidebar-card" style="background:linear-gradient(135deg,rgba(45,170,111,.15),rgba(23,163,184,.1));border-color:rgba(45,170,111,.2)">
                <h6 style="color:var(--brand-green)"><i class="bi bi-flower1 me-1"></i>Join Saathi</h6>
                <p class="text-muted small mb-3">
                    Create an account to post, comment, and connect with the wellness community.
                </p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm w-100">Join Free</a>
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">Sign In</a>
            </div>
            @endguest
        </div>

    </div>
</div>

@push('styles')
<style>
.sidebar-cat-link {
    display:flex;align-items:center;padding:.45rem .75rem;border-radius:8px;
    color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:500;
    transition:var(--transition);
}
.sidebar-cat-link:hover,.sidebar-cat-link.active {
    background:rgba(45,170,111,.12);color:var(--brand-green);
}
</style>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Auto-submit Search Bar (Debounce) ────────────────────────
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    if (searchInput && searchForm) {
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                searchForm.submit();
            }, 600); // Wait 600ms after last keystroke before submitting
        });
        
        // Ensure cursor is at the end of the text on reload
        if (searchInput.value.length > 0) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    }

    // ── Like button (AJAX) ───────────────────────────────────────
    const likeBtns = document.querySelectorAll('.feed-like-btn');
    likeBtns.forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            try {
                const resp = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });
                if (!resp.ok) throw new Error('Request failed');
                const data = await resp.json();
                
                // Update count
                this.querySelector('.like-count').textContent = data.likes_count;
                
                // Update icon
                const icon = this.querySelector('i');
                if (data.liked) {
                    icon.className = 'bi bi-heart-fill';
                    icon.style.color = '#e85d73';
                } else {
                    icon.className = 'bi bi-heart';
                    icon.style.color = 'inherit';
                }
                
                // Pop animation
                icon.style.transform = 'scale(1.2)';
                icon.style.transition = 'transform 0.2s';
                setTimeout(() => icon.style.transform = 'scale(1)', 200);
            } catch (error) {
                console.error('Like error:', error);
            }
        });
    });
});
</script>
@endpush
