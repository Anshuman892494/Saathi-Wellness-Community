@extends('layouts.app')
@section('title', 'Manage Users — Admin Panel')

@section('content')
<div class="page-hero" style="background: var(--gradient-hero); padding: 3rem 0 2rem; position: relative; overflow: hidden; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <h1 class="mb-1 d-flex align-items-center gap-3" style="font-family: var(--font-display); font-size: 2.2rem;">
            <i class="bi bi-people-fill text-brand" style="font-size: 2.4rem;"></i>
            <span>User Management 👥</span>
        </h1>
        <p class="lead text-muted" style="font-size: 1rem; margin-bottom: 0;">Administrative control center: view stats, moderate community members, and manage roles.</p>
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

    <div class="row g-4">
        @foreach($users as $user)
            <div class="col-md-6 col-lg-4">
                <div class="card-wellness p-4 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden" 
                     style="border: 1px solid var(--border-color); border-radius: var(--radius-card); background: var(--gradient-card); transition: var(--transition);">
                    
                    {{-- Card Content --}}
                    <div>
                        {{-- User Header Info --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($user->profile_photo)
                                <img src="{{ $user->profile_photo }}" alt="{{ $user->name }}" 
                                     style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid var(--brand-green);">
                            @else
                                <div class="d-flex align-items-center justify-content-center text-white fw-800" 
                                     style="width: 52px; height: 52px; border-radius: 50%; background: var(--gradient-brand); font-size: 1.4rem;">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <h5 class="mb-0 fw-700 d-flex align-items-center gap-2" style="font-size: 1.1rem; color: var(--text-primary);">
                                    {{ $user->name }}
                                </h5>
                                <small class="text-muted d-block" style="font-size: 0.8rem; word-break: break-all;">
                                    {{ $user->email }}
                                </small>
                            </div>
                        </div>

                        {{-- Role Badge --}}
                        <div class="mb-3">
                            @if($user->isAdmin())
                                <span class="badge bg-danger text-white px-2 py-1" style="font-size: 0.7rem; border-radius: 4px; letter-spacing: 0.5px;">SYSTEM ADMIN 👑</span>
                            @else
                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 0.7rem; border-radius: 4px; letter-spacing: 0.5px; background-color: var(--brand-green) !important;">COMMUNITY MEMBER 👥</span>
                            @endif
                        </div>

                        {{-- Bio --}}
                        <p class="text-muted mb-4" style="font-size: 0.88rem; min-height: 48px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $user->bio ?? 'No bio provided.' }}
                        </p>
                    </div>

                    {{-- Card Footer & Actions --}}
                    <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                        {{-- Stats Row --}}
                        <div class="d-flex justify-content-between mb-3 text-center">
                            <div class="flex-grow-1 p-2" style="border-right: 1px solid var(--border-color);">
                                <span class="d-block fw-800 text-brand" style="font-size: 1.2rem;">{{ $user->posts_count }}</span>
                                <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Posts</small>
                            </div>
                            <div class="flex-grow-1 p-2">
                                <span class="d-block fw-800 text-brand" style="font-size: 1.2rem;">{{ $user->comments_count }}</span>
                                <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Comments</small>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-grid mt-2">
                            @if($user->isAdmin())
                                <button class="btn btn-outline-secondary btn-sm" disabled style="border-radius: var(--radius-pill); font-size: 0.85rem; font-weight: 600; cursor: not-allowed; opacity: 0.6;">
                                    <i class="bi bi-shield-lock me-1"></i> Cannot Modify Self
                                </button>
                            @else
                                <form method="POST" action="{{ route('admin.users.destroy', $user->_id) }}" 
                                      onsubmit="return confirm('WARNING: Kya aap sach me {{ $user->name }} aur unka sabhi data permanently delete karna chahte hain? Ye wapas nahi laya ja sakega! 🗑️')">
                                    @csrf
                                    @method('DELETE')
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius: var(--radius-pill); font-size: 0.85rem; font-weight: 600; transition: var(--transition);">
                                            <i class="bi bi-trash3 me-1"></i> Delete User Account
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
