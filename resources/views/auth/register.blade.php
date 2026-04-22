@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <span class="auth-logo mb-3 text-brand"><i class="bi bi-flower1"></i></span>
            <h2 class="text-center mb-1">Join Saathi</h2>
            <p class="text-muted text-center mb-4" style="font-size:.875rem">
                Be part of a supportive wellness community
            </p>

            @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3"
                    style="background:rgba(220,53,69,.1);border:1px solid rgba(220,53,69,.3);color:#f87171;font-size:.85rem">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Jane Doe" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="jane@example.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 6 characters" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    Create My Account <i class="bi bi-person-plus-fill ms-1"></i>
                </button>
            </form>

            <p class="text-center mt-3 mb-0" style="font-size:.875rem;color:var(--text-muted)">
                Already have an account?
                <a href="{{ route('login') }}" class="fw-600" style="color:var(--brand-green)">Sign In</a>
            </p>
        </div>
    </div>
@endsection