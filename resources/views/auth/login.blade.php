@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <span class="auth-logo mb-3 text-brand"><i class="bi bi-flower1"></i></span>
            <h2 class="text-center mb-1">Welcome Back</h2>
            <p class="text-muted text-center mb-4" style="font-size:.875rem">
                Continue your wellness journey
            </p>

            @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3"
                    style="background:rgba(220,53,69,.1);border:1px solid rgba(220,53,69,.3);color:#f87171;font-size:.85rem">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="Enter email address" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" for="remember" style="font-size:.875rem">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    Sign In <i class="bi bi-box-arrow-in-right ms-1"></i>
                </button>
            </form>

            <p class="text-center mt-3 mb-0" style="font-size:.875rem;color:var(--text-muted)">
                New here?
                <a href="{{ route('register') }}" class="fw-600" style="color:var(--brand-green)">Create a free account</a>
            </p>
        </div>
    </div>
@endsection