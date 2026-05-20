@extends('layouts.app')
@section('title', __('Saved Posts'))

@section('content')
<div class="page-hero" style="padding:2.5rem 0 2rem">
    <div class="container">
        <h1 class="mb-1" style="font-size:1.8rem"><i class="bi bi-bookmark-heart-fill me-2"></i>{{ __('Saved Posts') }}</h1>
        <p class="lead" style="font-size:.95rem">{{ __('Your bookmarked wellness content') }}</p>
    </div>
</div>

<div class="container py-4">
    @if($posts->count())
        <div class="row g-3">
            @foreach($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card-wellness post-card h-100">
                    <span class="category-badge cat-{{ $post->category ?? 'general' }}">
                        {{ __(ucfirst(str_replace('-', ' ', $post->category ?? 'general'))) }}
                    </span>
                    <a href="{{ route('posts.show', $post->_id) }}" class="post-title text-decoration-none d-block">
                        {{ $post->title }}
                    </a>
                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                    <div class="post-meta">
                        <span>
                            <a href="{{ route('profile.show', $post->user_id) }}"
                               class="d-inline-flex align-items-center gap-2 text-decoration-none"
                               style="color:var(--brand-green);font-size:.8rem;font-weight:600;">
                                @if($post->user && $post->user->profile_photo)
                                    <img src="{{ $post->user->profile_photo }}" alt="{{ $post->user->name }}" 
                                         style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--border-color);">
                                @else
                                    <div class="d-flex align-items-center justify-content-center text-white" 
                                         style="width: 26px; height: 26px; border-radius: 50%; background: var(--gradient-brand); font-size: 0.75rem; font-weight: 700;">
                                        {{ strtoupper(substr($post->user->name ?? '?', 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ $post->user->name ?? 'Unknown' }}</span>
                                @if($post->user && $post->user->isAdmin())
                                    <span class="badge bg-danger text-white ms-1" style="font-size: 0.55rem; padding: 0.1rem 0.25rem; border-radius: 3px;">Admin 👑</span>
                                @endif
                            </a>
                        </span>
                        <span class="meta-stat"><i class="bi bi-heart-fill" style="color:#e85d73"></i> {{ count($post->likes ?? []) }}</span>

                        {{-- Un-bookmark --}}
                        <form method="POST" action="{{ route('posts.bookmark', $post->_id) }}" class="ms-auto">
                            @csrf
                            <button type="submit" class="btn p-0 border-0 text-muted" style="font-size:.8rem;background:transparent" title="{{ __('Remove bookmark') }}">
                                <i class="bi bi-bookmark-x-fill" style="color:#ffd60a"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state card-wellness py-5">
            <span class="empty-icon text-brand"><i class="bi bi-bookmark-x-fill"></i></span>
            <h5>{{ __('No saved posts yet') }}</h5>
            <p class="text-muted">{{ __('Bookmark posts while reading to save them here.') }}</p>
            <a href="{{ route('posts.index') }}" class="btn btn-primary mt-2">{{ __('Explore Community') }}</a>
        </div>
    @endif
</div>
@endsection
