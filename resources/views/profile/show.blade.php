@extends('layouts.app')
@section('title', $profileUser->name . "'s Profile")

@section('content')

{{-- ── Profile Banner ──────────────────────────────────────── --}}
<div class="profile-banner">
    <div class="container">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div class="avatar-lg" style="width:90px;height:90px;font-size:2rem">
                {{ strtoupper(substr($profileUser->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="mb-1" style="font-size:1.8rem">{{ $profileUser->name }}</h1>
                @if($profileUser->bio)
                    <p class="text-muted mb-2">{{ $profileUser->bio }}</p>
                @endif
                <div class="d-flex gap-4 profile-stats">
                    <div>
                        <span>{{ $posts->count() }}</span>
                        <small>Posts</small>
                    </div>
                    <div>
                        <span>{{ $posts->sum(fn($p) => count($p->likes ?? [])) }}</span>
                        <small>Total Likes</small>
                    </div>
                </div>
            </div>
            @auth
            @if((string) Auth::user()->_id === (string) $profileUser->_id)
            <div class="ms-auto">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Profile
                </a>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>

<div class="container py-4">
    <h5 class="fw-700 mb-3">📝 Posts by {{ $profileUser->name }}</h5>

    @if($posts->count())
        <div class="row g-3">
            @foreach($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card-wellness post-card h-100">
                    <span class="category-badge cat-{{ $post->category ?? 'general' }}">
                        {{ ucfirst(str_replace('-', ' ', $post->category ?? 'general')) }}
                    </span>
                    <a href="{{ route('posts.show', $post->_id) }}" class="post-title text-decoration-none d-block">
                        {{ $post->title }}
                    </a>
                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                    <div class="post-meta">
                        <span class="meta-stat"><i class="bi bi-heart-fill" style="color:#e85d73"></i> {{ count($post->likes ?? []) }}</span>
                        <span class="meta-stat"><i class="bi bi-chat-fill" style="color:var(--brand-teal)"></i> {{ $post->comments()->count() }}</span>
                        <span class="meta-stat ms-auto">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state card-wellness py-5">
            <span class="empty-icon">📭</span>
            <p>No posts yet.</p>
        </div>
    @endif
</div>
@endsection
