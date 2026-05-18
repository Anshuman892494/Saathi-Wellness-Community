@extends('layouts.app')
@section('title', $profileUser->name . "'s Profile")

@section('content')

<div class="container mt-3">
    <div class="profile-header-container mb-4">
        <div class="cover-photo" style="height: 300px; background-color: var(--card-bg); background-image: url('{{ $profileUser->cover_photo ?? '' }}'); background-size: cover; background-position: center; border-radius: 12px 12px 0 0; position: relative; border: 1px solid var(--border-color); border-bottom: none;">
            @if(!$profileUser->cover_photo)
                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                    <i class="bi bi-image me-2"></i> No cover photo
                </div>
            @endif
            <div class="profile-avatar-container" style="position: absolute; bottom: -45px; left: 30px; z-index: 10;">
                <div class="profile-avatar" style="width: 140px; height: 140px; border-radius: 50%; border: 4px solid var(--bg-body); background-color: var(--card-bg); overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    @if($profileUser->profile_photo)
                        <img src="{{ $profileUser->profile_photo }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 3rem; color: var(--text-muted);">{{ strtoupper(substr($profileUser->name, 0, 1)) }}</span>
                    @endif
                </div>
            </div>
            @auth
            @if((string) Auth::user()->_id === (string) $profileUser->_id)
            <div style="position: absolute; bottom: 15px; right: 15px;">
                <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm shadow-sm" style="background: rgba(255,255,255,0.9); color: #333; font-weight: 600;">
                    <i class="bi bi-camera-fill me-1"></i> Edit Profile
                </a>
            </div>
            @endif
            @endauth
        </div>
        <div class="profile-info-section" style="padding: 60px 30px 30px 30px; background: var(--card-bg); border-radius: 0 0 12px 12px; border: 1px solid var(--border-color); border-top: none; position: relative;">
            <h1 class="mb-1 fw-700 d-flex align-items-center gap-2" style="font-size: 2rem;">
                {{ $profileUser->name }}
                @if($profileUser->isAdmin())
                    <span class="badge bg-danger text-white d-inline-flex align-items-center" style="font-size: 0.8rem; padding: 0.2rem 0.5rem; border-radius: 4px;">Admin 👑</span>
                @endif
            </h1>
            @if($profileUser->bio)
                <p class="text-muted mb-3" style="max-width: 600px; font-size: 1.05rem;">{{ $profileUser->bio }}</p>
            @endif
            <div class="d-flex gap-4 mt-4">
                <div>
                    <span class="fw-700 text-brand" style="font-size: 1.3rem;">{{ $posts->count() }}</span>
                    <small class="text-muted text-uppercase d-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">Posts</small>
                </div>
                <div>
                    <span class="fw-700 text-danger" style="font-size: 1.3rem;">{{ $posts->sum(fn($p) => count($p->likes ?? [])) }}</span>
                    <small class="text-muted text-uppercase d-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Likes</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <h5 class="fw-700 mb-3"><i class="bi bi-file-text-fill text-brand me-2"></i>Posts by {{ $profileUser->name }}</h5>

    @if($posts->count())
        <div class="row g-3">
            @foreach($posts as $post)
            <div class="col-md-6 col-lg-4">
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
                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                    <div class="post-meta">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
