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
                    @if(isset($dailyInsight) && !is_array($dailyInsight))
                        <div class="mt-2 small text-brand fw-600" style="opacity:0.85">
                            <i class="bi bi-lightning-charge-fill"></i> Daily Insight: "{{ $dailyInsight }}"
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">

        {{-- ── AI Smart Feed ("For You") ───────────────────────────── --}}
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <h5 class="mb-0 fw-700">Recommended for You</h5>
                <span class="badge bg-brand-soft text-brand rounded-pill px-3 py-1" style="font-size:0.7rem; background:rgba(45,170,111,0.1)">
                    <i class="bi bi-robot me-1"></i>AI Personalized
                </span>
            </div>
            <div class="row g-3">
                @foreach($recommendedPosts as $post)
                    <div class="col-md-3">
                        <div class="card-wellness post-card p-3 h-100" style="min-height: 180px;">
                            <span class="category-badge cat-{{ $post->category }}" style="font-size:0.6rem">
                                {{ ucfirst($post->category) }}
                            </span>
                            <a href="{{ route('posts.show', $post->_id) }}" class="post-title text-decoration-none d-block mb-2" style="font-size:0.9rem">
                                {{ Str::limit($post->title, 40) }}
                            </a>
                            <div class="post-meta mt-auto pt-2" style="font-size:0.7rem">
                                <span><i class="bi bi-heart-fill text-danger"></i> {{ count($post->likes ?? []) }}</span>
                                <span class="ms-auto">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── AI Mood Tracker & Stats ────────────────────────────── --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="card-wellness p-4 h-100 ai-glow">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="stat-icon m-0" style="background:rgba(45,170,111,0.1); width:40px; height:40px;">
                            <i class="bi bi-journal-check text-brand"></i>
                        </div>
                        <h6 class="mb-0 fw-700">Daily Wellness Mood Journal</h6>
                    </div>
                    <p class="text-muted small mb-3">How are you feeling today? Share a few words and let our AI suggest some wellness tips.</p>
                    
                    <div id="mood-form-container">
                        <div class="input-group mb-2">
                            <input type="text" id="mood-input" class="form-control form-control-lg" placeholder="e.g., I'm feeling a bit stressed today..." style="font-size: 0.95rem;">
                            <button class="btn btn-primary px-4" id="analyze-mood-btn">
                                <span id="btn-text">Analyze</span>
                                <span id="btn-loader" class="loading-spinner d-none"></span>
                            </button>
                        </div>
                    </div>

                    <div id="mood-suggestion" class="ai-suggestion-box d-none mt-3">
                        <div class="suggestion-content" style="font-size: 0.9rem; line-height: 1.6;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3 h-100">
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon" style="background:rgba(45,170,111,0.15)"><i class="bi bi-file-earmark-text text-brand"></i></div>
                            <div class="stat-number">{{ $stats['my_posts'] }}</div>
                            <div class="stat-label">My Posts</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon" style="background:rgba(255,214,10,0.15)"><i class="bi bi-bookmark-heart text-warning"></i></div>
                            <div class="stat-number">{{ $stats['bookmarks'] }}</div>
                            <div class="stat-label">Saved Posts</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── Left Column: Community Feed ────────────────────────── --}}
            <div class="col-lg-8">
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
                        <a href="{{ route('ai.nutrition') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-apple me-2"></i>Nutrition AI
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const moodInput = document.getElementById('mood-input');
    const analyzeBtn = document.getElementById('analyze-mood-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');
    const suggestionBox = document.getElementById('mood-suggestion');
    const suggestionContent = suggestionBox.querySelector('.suggestion-content');

    if (analyzeBtn) {
        analyzeBtn.addEventListener('click', async function() {
            const mood = moodInput.value.trim();
            if (!mood) return;

            // UI Loading State
            analyzeBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');
            suggestionBox.classList.add('d-none');

            try {
                const response = await fetch('{{ route("ai.analyze-mood") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mood: mood })
                });

                const data = await response.json();
                
                if (data.suggestion) {
                    suggestionContent.innerHTML = data.suggestion.replace(/\n/g, '<br>');
                    suggestionBox.classList.remove('d-none');
                } else if (data.error) {
                    alert(data.error);
                }
            } catch (error) {
                console.error('Mood Analysis Error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                analyzeBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnLoader.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush