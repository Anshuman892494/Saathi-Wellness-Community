@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="page-hero" style="padding:2.5rem 0 2rem">
    <div class="container">
        <h1 class="mb-1" style="font-size:1.8rem">⚙️ Edit Profile</h1>
        <p class="lead" style="font-size:.95rem">Update your information and personal bio</p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card-wellness p-4">

                @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="background:rgba(220,53,69,.1);border:1px solid rgba(220,53,69,.3);color:#f87171;font-size:.85rem">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" novalidate>
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <div class="form-text text-muted">Email address cannot be changed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio <span class="text-muted fw-400">(optional)</span></label>
                        <textarea name="bio" class="form-control" rows="3"
                                  placeholder="Tell the community about your wellness journey…" maxlength="300">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <hr style="border-color:var(--border-color)">
                    <p class="text-muted small">Leave password fields blank to keep your current password.</p>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 6 characters">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('profile.show', $user->_id) }}" class="btn btn-outline-primary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
