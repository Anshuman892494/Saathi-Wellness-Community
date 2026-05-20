@extends('layouts.app')
@section('title', __('Dashboard'))
@section('meta_description', __('Your personal health & wellness dashboard'))

@section('content')

    {{-- ── Hero Greeting ─────────────────────────────────────────── --}}
    <div class="page-hero">
        <div class="container">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="avatar-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <h1 class="mb-1" style="font-size:clamp(1.4rem,3vw,2rem)">
                        {{ __('Welcome back, :name!', ['name' => $user->name]) }} <i class="bi bi-hand-thumbs-up-fill text-brand"></i>
                    </h1>
                    <p class="lead mb-0" style="font-size:.95rem">
                        @if($user->bio) {{ $user->bio }} @else {{ __('Ready to share your wellness journey?') }} @endif
                    </p>
                    @if(isset($dailyInsight) && !is_array($dailyInsight))
                        <div class="mt-2 small text-brand fw-600" style="opacity:0.85">
                            <i class="bi bi-lightning-charge-fill"></i> {{ __('Daily Insight:') }} "{{ $dailyInsight }}"
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert" style="border-radius: 12px; background: rgba(45,170,111,0.12); border-color: rgba(45,170,111,0.2); color: var(--brand-green);">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert" style="border-radius: 12px; background: rgba(220,53,69,0.12); border-color: rgba(220,53,69,0.2); color: #dc3545;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- ── Admin Control Center (Users List) ───────────────────── --}}
        @if(Auth::user()->isAdmin())
        <div class="mb-5 p-4 rounded-4" style="background: rgba(220, 53, 69, 0.04); border: 1px dashed rgba(220, 53, 69, 0.25); border-radius: 12px;">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1 fw-700 text-danger d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock-fill"></i> {{ __('Admin Control Center — User Management 👥') }}
                    </h4>
                    <p class="text-muted mb-0 small">{{ __('Moderate community members, check metrics, and manage user accounts directly from your dashboard.') }}</p>
                </div>
                <span class="badge bg-danger text-white px-3 py-1.5" style="font-size:0.75rem; border-radius: 6px;">
                    {{ __('Total Members: :count', ['count' => $adminUsersList->count()]) }}
                </span>
            </div>

            <div class="row g-3">
                @foreach($adminUsersList as $u)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-wellness p-3 h-100 d-flex flex-column justify-content-between border" style="background: var(--bg-card); transition: var(--transition);">
                            <div>
                                <div class="d-flex align-items-center gap-2.5 mb-2">
                                    @if($u->profile_photo)
                                        <img src="{{ $u->profile_photo }}" alt="{{ $u->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--brand-green);">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center text-white fw-700" style="width: 38px; height: 38px; border-radius: 50%; background: var(--gradient-brand); font-size: 0.95rem;">
                                            {{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-700 text-truncate" style="max-width: 140px; font-size: 0.92rem; color: var(--text-primary);">{{ $u->name }}</h6>
                                        <small class="text-muted text-truncate d-block" style="max-width: 140px; font-size: 0.75rem;">{{ $u->email }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        @if($u->isAdmin())
                                            <span class="badge text-danger" style="font-size: 0.6rem; padding: 0.2rem 0.4rem; background: rgba(220,53,69,0.1); border-radius: 4px;">{{ __('Admin') }} 👑</span>
                                        @else
                                            <span class="badge text-success" style="font-size: 0.6rem; padding: 0.2rem 0.4rem; background: rgba(45,170,111,0.1); border-radius: 4px;">{{ __('Member') }} 👥</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-muted small mb-3" style="font-size: 0.8rem; min-height: 32px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $u->bio ?? __('No bio provided.') }}
                                </p>
                            </div>
                            <div class="pt-2" style="border-top: 1px solid var(--border-color);">
                                <div class="d-flex justify-content-around mb-2 text-center" style="font-size: 0.75rem;">
                                    <div>
                                        <span class="fw-700 text-brand">{{ $u->posts_count }}</span> <span class="text-muted">{{ __('posts') }}</span>
                                    </div>
                                    <div>
                                        <span class="fw-700 text-brand">{{ $u->comments_count }}</span> <span class="text-muted">{{ __('Comments') }}</span>
                                    </div>
                                </div>
                                @if($u->isAdmin())
                                    <button class="btn btn-sm btn-outline-secondary w-100" disabled style="font-size: 0.75rem; border-radius: 20px;">
                                        {{ __('Cannot Delete Self') }}
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('admin.users.destroy', $u->_id) }}" onsubmit="return confirm('{{ __('WARNING: Kya aap sach me :name ko delete karna chahte hain? User ka saare posts aur comments bhi delete ho jayenge!', ['name' => $u->name]) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size: 0.75rem; border-radius: 20px;">
                                            <i class="bi bi-trash3 me-1"></i> {{ __('Delete Account') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── AI Smart Feed ("For You") ───────────────────────────── --}}
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <h5 class="mb-0 fw-700">{{ __('Recommended for You') }}</h5>
                <span class="badge bg-brand-soft text-brand rounded-pill px-3 py-1" style="font-size:0.7rem; background:rgba(45,170,111,0.1)">
                    <i class="bi bi-robot me-1"></i>{{ __('AI Personalized') }}
                </span>
            </div>
            <div class="row g-3">
                @foreach($recommendedPosts as $post)
                    <div class="col-md-3">
                        <div class="card-wellness post-card p-3 h-100" style="min-height: 180px;">
                            <span class="category-badge cat-{{ $post->category }}" style="font-size:0.6rem">
                                {{ __(ucfirst($post->category)) }}
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
                        <h6 class="mb-0 fw-700">{{ __('Daily Wellness Mood Journal') }}</h6>
                    </div>
                    <p class="text-muted small mb-3">{{ __('How are you feeling today? Share a few words and let our AI suggest some wellness tips.') }}</p>
                    
                    <div id="mood-form-container">
                        <div class="input-group mb-2">
                            <input type="text" id="mood-input" class="form-control form-control-lg" placeholder="{{ __('e.g., I\'m feeling a bit stressed today...') }}" style="font-size: 0.95rem;">
                            <button class="btn btn-primary px-4" id="analyze-mood-btn">
                                <span id="btn-text">{{ __('Analyze') }}</span>
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
                            <div class="stat-label">{{ __('My Posts') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon" style="background:rgba(255,214,10,0.15)"><i class="bi bi-bookmark-heart text-warning"></i></div>
                            <div class="stat-number">{{ $stats['bookmarks'] }}</div>
                            <div class="stat-label">{{ __('Saved Posts') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Daily Goals Tracker ─────────────────────────────────── --}}
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-700">{{ __('Daily Goals Progress') }}</h5>
                    <span class="badge bg-brand-soft text-brand rounded-pill px-3 py-1" style="font-size:0.7rem; background:rgba(45,170,111,0.1)">
                        <i class="bi bi-activity me-1"></i>{{ __('Live Tracking') }}
                    </span>
                </div>
                <button type="button" class="btn btn-outline-brand btn-sm" data-bs-toggle="modal" data-bs-target="#logStatsModal">
                    <i class="bi bi-pencil-square me-1"></i>{{ __('Update Stats') }}
                </button>
            </div>

            @php
                $waterGoal = 3.0; // Liters
                $stepsGoal = 10000; // Steps
                $meditationGoal = 30; // Minutes
                $sleepGoal = 8.0; // Hours

                $waterPct = min(100, round(($dailyStat->water_liters / $waterGoal) * 100));
                $stepsPct = min(100, round(($dailyStat->steps / $stepsGoal) * 100));
                $meditationPct = min(100, round(($dailyStat->meditation_minutes / $meditationGoal) * 100));
                $sleepPct = min(100, round(($dailyStat->sleep_hours / $sleepGoal) * 100));
            @endphp

            <div class="row g-3">
                {{-- Steps --}}
                <div class="col-md-3">
                    <div class="card-wellness p-3 text-center h-100">
                        <div class="stat-icon mx-auto mb-2" style="background: rgba(23, 163, 184, 0.15); width: 45px; height: 45px;">
                            <i class="bi bi-footprints text-info" style="font-size: 1.25rem;"></i>
                        </div>
                        <h6 class="mb-1 small fw-600 text-muted">{{ __('Steps Walked') }}</h6>
                        <h4 class="mb-2 fw-700 text-info" id="display-steps">{{ number_format($dailyStat->steps) }} <small class="fs-6 fw-normal">/ {{ number_format($stepsGoal) }}</small></h4>
                        <div class="progress mb-1" style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div class="progress-bar bg-info" id="bar-steps" role="progressbar" style="width: {{ $stepsPct }}%" aria-valuenow="{{ $stepsPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;"><span id="pct-steps">{{ $stepsPct }}</span>% completed</small>
                    </div>
                </div>

                {{-- Water --}}
                <div class="col-md-3">
                    <div class="card-wellness p-3 text-center h-100">
                        <div class="stat-icon mx-auto mb-2" style="background: rgba(13, 110, 253, 0.15); width: 45px; height: 45px;">
                            <i class="bi bi-droplet-fill text-primary" style="font-size: 1.25rem;"></i>
                        </div>
                        <h6 class="mb-1 small fw-600 text-muted">{{ __('Water Intake') }}</h6>
                        <h4 class="mb-2 fw-700 text-primary" id="display-water">{{ number_format($dailyStat->water_liters, 1) }} L <small class="fs-6 fw-normal">/ {{ number_format($waterGoal, 1) }} L</small></h4>
                        <div class="progress mb-1" style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div class="progress-bar bg-primary" id="bar-water" role="progressbar" style="width: {{ $waterPct }}%" aria-valuenow="{{ $waterPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;"><span id="pct-water">{{ $waterPct }}</span>% completed</small>
                    </div>
                </div>

                {{-- Meditation --}}
                <div class="col-md-3">
                    <div class="card-wellness p-3 text-center h-100">
                        <div class="stat-icon mx-auto mb-2" style="background: rgba(45, 170, 111, 0.15); width: 45px; height: 45px;">
                            <i class="bi bi-yin-yang text-brand" style="font-size: 1.25rem;"></i>
                        </div>
                        <h6 class="mb-1 small fw-600 text-muted">{{ __('Meditation') }}</h6>
                        <h4 class="mb-2 fw-700 text-brand" id="display-meditation">{{ $dailyStat->meditation_minutes }} m <small class="fs-6 fw-normal">/ {{ $meditationGoal }} m</small></h4>
                        <div class="progress mb-1" style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div class="progress-bar bg-success" id="bar-meditation" role="progressbar" style="width: {{ $meditationPct }}%" aria-valuenow="{{ $meditationPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;"><span id="pct-meditation">{{ $meditationPct }}</span>% completed</small>
                    </div>
                </div>

                {{-- Sleep --}}
                <div class="col-md-3">
                    <div class="card-wellness p-3 text-center h-100">
                        <div class="stat-icon mx-auto mb-2" style="background: rgba(111, 66, 193, 0.15); width: 45px; height: 45px;">
                            <i class="bi bi-moon-stars text-purple" style="font-size: 1.25rem; color: #a180ff !important;"></i>
                        </div>
                        <h6 class="mb-1 small fw-600 text-muted">{{ __('Sleep Duration') }}</h6>
                        <h4 class="mb-2 fw-700 text-purple" id="display-sleep" style="color: #a180ff !important;">{{ number_format($dailyStat->sleep_hours, 1) }} h <small class="fs-6 fw-normal">/ {{ number_format($sleepGoal, 1) }} h</small></h4>
                        <div class="progress mb-1" style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div class="progress-bar" id="bar-sleep" role="progressbar" style="width: {{ $sleepPct }}%; background: #a180ff;" aria-valuenow="{{ $sleepPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;"><span id="pct-sleep">{{ $sleepPct }}</span>% completed</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── Left Column: Community Feed ────────────────────────── --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-700"><i class="bi bi-stars text-brand me-2"></i>{{ __('Latest from the Community') }}</h5>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">{{ __('View All') }}</a>
                </div>

                @if($latestPosts->count())
                    <div class="row g-3">
                        @foreach($latestPosts as $post)
                            <div class="col-md-6">
                                <div class="card-wellness post-card h-100">
                                    <span class="category-badge cat-{{ $post->category }}">
                                        {{ __(ucfirst(str_replace('-', ' ', $post->category ?? 'general'))) }}
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
                        <p>{{ __('No posts yet. Be the first to share!') }}</p>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">{{ __('Create First Post') }}</a>
                    </div>
                @endif
            </div>

            {{-- ── Right Sidebar ─────────────────────────────────── --}}
            <div class="col-lg-4">

                {{-- Quick actions --}}
                <div class="sidebar-card mb-3">
                    <h6>{{ __('Quick Actions') }}</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>{{ __('Write a Post') }}
                        </a>
                        <a href="{{ route('ai.nutrition') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-leaf me-2"></i>{{ __('Nutrition AI') }}
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-gear me-2"></i>{{ __('Edit Profile') }}
                        </a>
                        <a href="{{ route('bookmarks.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-bookmark-heart me-2"></i>{{ __('Saved Posts') }}
                        </a>
                    </div>
                </div>

                {{-- Trending --}}
                <div class="sidebar-card mb-3">
                    <h6><i class="bi bi-fire text-danger me-2"></i>{{ __('Trending Posts') }}</h6>
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
                        <h6>{{ __('My Recent Posts') }}</h6>
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

    {{-- ── Modal: Update Daily Stats ────────────────────────────── --}}
    <div class="modal fade" id="logStatsModal" tabindex="-1" aria-labelledby="logStatsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; backdrop-filter: blur(15px);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-700 text-white" id="logStatsModalLabel">
                        <i class="bi bi-activity text-brand me-2"></i>{{ __('Update Daily Stats') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="update-stats-form" method="POST" action="{{ route('daily-stats.update') }}">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="row g-3">
                            {{-- Steps Input --}}
                            <div class="col-6">
                                <label for="input-steps" class="form-label small text-muted">{{ __('Steps Walked') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-info"><i class="bi bi-footprints"></i></span>
                                    <input type="number" name="steps" id="input-steps" class="form-control bg-dark border-secondary text-white" value="{{ $dailyStat->steps }}" min="0" max="100000" placeholder="e.g., 5000">
                                </div>
                            </div>
                            {{-- Water Input --}}
                            <div class="col-6">
                                <label for="input-water" class="form-label small text-muted">{{ __('Water Intake') }} (L)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-droplet-fill"></i></span>
                                    <input type="number" name="water_liters" id="input-water" step="0.1" class="form-control bg-dark border-secondary text-white" value="{{ $dailyStat->water_liters }}" min="0" max="10" placeholder="e.g., 2.5">
                                </div>
                            </div>
                            {{-- Meditation Input --}}
                            <div class="col-6">
                                <label for="input-meditation" class="form-label small text-muted">{{ __('Meditation') }} ({{ __('Minutes') }})</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-success"><i class="bi bi-yin-yang"></i></span>
                                    <input type="number" name="meditation_minutes" id="input-meditation" class="form-control bg-dark border-secondary text-white" value="{{ $dailyStat->meditation_minutes }}" min="0" max="1440" placeholder="e.g., 15">
                                </div>
                            </div>
                            {{-- Sleep Input --}}
                            <div class="col-6">
                                <label for="input-sleep" class="form-label small text-muted">{{ __('Sleep Duration') }} ({{ __('Hours') }})</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-purple" style="color: #a180ff;"><i class="bi bi-moon-stars"></i></span>
                                    <input type="number" name="sleep_hours" id="input-sleep" step="0.5" class="form-control bg-dark border-secondary text-white" value="{{ $dailyStat->sleep_hours }}" min="0" max="24" placeholder="e.g., 7.5">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-brand btn-sm px-4" id="submit-stats-btn">
                            <span id="submit-btn-text">{{ __('Save Changes') }}</span>
                            <span id="submit-btn-loader" class="loading-spinner d-none"></span>
                        </button>
                    </div>
                </form>
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

    // Daily Stats Update via AJAX
    const statsForm = document.getElementById('update-stats-form');
    const submitStatsBtn = document.getElementById('submit-stats-btn');
    const submitBtnText = document.getElementById('submit-btn-text');
    const submitBtnLoader = document.getElementById('submit-btn-loader');
    
    if (statsForm) {
        statsForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            submitStatsBtn.disabled = true;
            submitBtnText.classList.add('d-none');
            submitBtnLoader.classList.remove('d-none');
            
            const formData = new FormData(statsForm);
            
            try {
                const response = await fetch(statsForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success && data.stat) {
                    const stat = data.stat;
                    const waterGoal = 3.0;
                    const stepsGoal = 10000;
                    const meditationGoal = 30;
                    const sleepGoal = 8.0;
                    
                    const waterPct = Math.min(100, Math.round((stat.water_liters / waterGoal) * 100));
                    const stepsPct = Math.min(100, Math.round((stat.steps / stepsGoal) * 100));
                    const meditationPct = Math.min(100, Math.round((stat.meditation_minutes / meditationGoal) * 100));
                    const sleepPct = Math.min(100, Math.round((stat.sleep_hours / sleepGoal) * 100));
                    
                    // Update numbers
                    document.getElementById('display-steps').innerHTML = Number(stat.steps).toLocaleString() + ' <small class="fs-6 fw-normal">/ ' + stepsGoal.toLocaleString() + '</small>';
                    document.getElementById('display-water').innerHTML = Number(stat.water_liters).toFixed(1) + ' L <small class="fs-6 fw-normal">/ ' + waterGoal.toFixed(1) + ' L</small>';
                    document.getElementById('display-meditation').innerHTML = stat.meditation_minutes + ' m <small class="fs-6 fw-normal">/ ' + meditationGoal + ' m</small>';
                    document.getElementById('display-sleep').innerHTML = Number(stat.sleep_hours).toFixed(1) + ' h <small class="fs-6 fw-normal">/ ' + sleepGoal.toFixed(1) + ' h</small>';
                    
                    // Update percentages text
                    document.getElementById('pct-steps').textContent = stepsPct;
                    document.getElementById('pct-water').textContent = waterPct;
                    document.getElementById('pct-meditation').textContent = meditationPct;
                    document.getElementById('pct-sleep').textContent = sleepPct;
                    
                    // Update progress bars
                    document.getElementById('bar-steps').style.width = stepsPct + '%';
                    document.getElementById('bar-steps').setAttribute('aria-valuenow', stepsPct);
                    
                    document.getElementById('bar-water').style.width = waterPct + '%';
                    document.getElementById('bar-water').setAttribute('aria-valuenow', waterPct);
                    
                    document.getElementById('bar-meditation').style.width = meditationPct + '%';
                    document.getElementById('bar-meditation').setAttribute('aria-valuenow', meditationPct);
                    
                    document.getElementById('bar-sleep').style.width = sleepPct + '%';
                    document.getElementById('bar-sleep').setAttribute('aria-valuenow', sleepPct);
                    
                    // Sync values inside inputs
                    document.getElementById('input-steps').value = stat.steps;
                    document.getElementById('input-water').value = stat.water_liters;
                    document.getElementById('input-meditation').value = stat.meditation_minutes;
                    document.getElementById('input-sleep').value = stat.sleep_hours;
                    
                    // Dismiss modal
                    const modalEl = document.getElementById('logStatsModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                }
            } catch (error) {
                console.error('Update Stats Error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                submitStatsBtn.disabled = false;
                submitBtnText.classList.remove('d-none');
                submitBtnLoader.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush