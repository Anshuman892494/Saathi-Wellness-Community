@extends('layouts.app')
@section('title', 'Dashboard')
@section('meta_description', 'Your personal health & wellness dashboard')

@section('content')

    {{-- ── Hero Greeting ─────────────────────────────────────────── --}}
    <div class="page-hero">
        <div class="container">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="avatar-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <h1 class="mb-1" style="font-size:clamp(1.4rem,3vw,2rem)">
                        Welcome back, {{ $user->name }}! <i class="bi bi-hand-thumbs-up-fill text-brand"></i>
                    </h1>
                    <p class="lead mb-0" style="font-size:.95rem">
                        @if($user->bio) {{ $user->bio }} @else Ready to share your wellness journey? @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">

        {{-- ── Stats Row ─────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(45,170,111,0.15)"><i class="bi bi-file-earmark-text text-brand"></i></div>
                    <div class="stat-number">{{ $stats['my_posts'] }}</div>
                    <div class="stat-label">My Posts</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(23,163,184,0.15)"><i class="bi bi-globe text-info"></i></div>
                    <div class="stat-number">{{ $stats['total_posts'] }}</div>
                    <div class="stat-label">Community Posts</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(255,214,10,0.15)"><i class="bi bi-bookmark-heart text-warning"></i></div>
                    <div class="stat-number">{{ $stats['bookmarks'] }}</div>
                    <div class="stat-label">Saved Posts</div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── Left Column ───────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Latest Community Posts --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-700"><i class="bi bi-stars text-brand me-2"></i>Latest from the Community</h5>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>

                @if($latestPosts->count())
                    <div class="row g-3">
                        @foreach($latestPosts as $post)
                            <div class="col-md-6">
                                <div class="card-wellness post-card h-100">
                                    <span class="category-badge cat-{{ $post->category }}">
                                        {{ ucfirst(str_replace('-', ' ', $post->category ?? 'general')) }}
                                    </span>
                                    <a href="{{ route('posts.show', $post->_id) }}" class="post-title text-decoration-none d-block">
                                        {{ $post->title }}
                                    </a>
                                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                    <div class="post-meta">
                                        <span class="meta-stat"><i class="bi bi-heart-fill" style="color:#e85d73"></i>
                                            {{ count($post->likes ?? []) }}</span>
                                        <span class="meta-stat"><i class="bi bi-chat-fill" style="color:var(--brand-teal)"></i>
                                            {{ $post->comments()->count() }}</span>
                                        <span class="meta-stat ms-auto"><i class="bi bi-clock"></i>
                                            {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state card-wellness py-5">
                        <span class="empty-icon"><i class="bi bi-mailbox"></i></span>
                        <p>No posts yet. Be the first to share!</p>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create First Post</a>
                    </div>
                @endif
            </div>

            {{-- ── Right Sidebar ─────────────────────────────────── --}}
            <div class="col-lg-4">

                {{-- Quick actions --}}
                <div class="sidebar-card mb-3">
                    <h6>Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Write a Post
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('bookmarks.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-bookmark-heart me-2"></i>Saved Posts
                        </a>
                    </div>
                </div>

                {{-- Trending --}}
                <div class="sidebar-card mb-3">
                    <h6><i class="bi bi-fire text-danger me-2"></i>Trending Posts</h6>
                    @foreach($trendingPosts as $p)
                        <div class="d-flex align-items-start gap-2 mb-2 pb-2"
                            style="border-bottom:1px solid var(--border-color)">
                            <span
                                style="color:var(--brand-green);font-weight:700;font-size:.85rem">{{ count($p->likes ?? []) }}</span>
                            <a href="{{ route('posts.show', $p->_id) }}" class="text-decoration-none"
                                style="font-size:.85rem;color:var(--text-primary)">
                                {{ Str::limit($p->title, 55) }}
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- My Recent Posts --}}
                @if($myPosts->count())
                    <div class="sidebar-card">
                        <h6>My Recent Posts</h6>
                        @foreach($myPosts as $p)
                            <div class="mb-2 pb-2" style="border-bottom:1px solid var(--border-color)">
                                <a href="{{ route('posts.show', $p->_id) }}" class="text-decoration-none"
                                    style="font-size:.85rem;color:var(--text-primary);display:block;margin-bottom:.15rem">
                                    {{ Str::limit($p->title, 50) }}
                                </a>
                                <small class="text-muted">{{ $p->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection