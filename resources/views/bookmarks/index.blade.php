@extends('layouts.app')
@section('title', 'Saved Posts')

@section('content')
<div class="page-hero" style="padding:2.5rem 0 2rem">
    <div class="container">
        <h1 class="mb-1" style="font-size:1.8rem"><i class="bi bi-bookmark-heart-fill me-2"></i>Saved Posts</h1>
        <p class="lead" style="font-size:.95rem">Your bookmarked wellness content</p>
    </div>
</div>

<div class="container py-4">
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
                        <span style="color:var(--brand-green);font-size:.8rem;font-weight:600">
                            {{ $post->user->name ?? 'Unknown' }}
                        </span>
                        <span class="meta-stat"><i class="bi bi-heart-fill" style="color:#e85d73"></i> {{ count($post->likes ?? []) }}</span>

                        {{-- Un-bookmark --}}
                        <form method="POST" action="{{ route('posts.bookmark', $post->_id) }}" class="ms-auto">
                            @csrf
                            <button type="submit" class="btn p-0 border-0 text-muted" style="font-size:.8rem;background:transparent" title="Remove bookmark">
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
            <h5>No saved posts yet</h5>
            <p class="text-muted">Bookmark posts while reading to save them here.</p>
            <a href="{{ route('posts.index') }}" class="btn btn-primary mt-2">Explore Community</a>
        </div>
    @endif
</div>
@endsection
