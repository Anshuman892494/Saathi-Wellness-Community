@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="page-hero" style="padding:2.5rem 0 2rem">
    <div class="container">
        <h1 class="mb-1" style="font-size:1.8rem"><i class="bi bi-gear-fill me-2"></i>Edit Profile</h1>
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

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-700">Cover Photo</label>
                        <div class="cover-preview mb-2" style="width: 100%; height: 150px; background-color: var(--card-bg); border: 2px dashed var(--border-color); border-radius: 8px; overflow: hidden; position: relative;">
                            <img id="coverPreview" src="{{ $user->cover_photo ?? '' }}" style="width: 100%; height: 100%; object-fit: cover; display: {{ $user->cover_photo ? 'block' : 'none' }};">
                            <div class="preview-placeholder" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: {{ $user->cover_photo ? 'none' : 'block' }}; text-align: center; color: var(--text-muted);">
                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                <p class="mb-0 small">No cover photo</p>
                            </div>
                        </div>
                        <input type="file" name="cover_photo" id="coverInput" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Recommended size: 1200x400px (Max 5MB)</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-700">Profile Photo</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="profile-preview" style="width: 100px; height: 100px; border-radius: 50%; background-color: var(--card-bg); border: 2px dashed var(--border-color); overflow: hidden; position: relative;">
                                @if($user->profile_photo)
                                    <img id="profilePreview" src="{{ $user->profile_photo }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                @else
                                    <img id="profilePreview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div class="preview-placeholder-profile" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted);">
                                        <div class="avatar-lg" style="margin: 0; width: 100px; height: 100px; line-height: 100px; font-size: 2.5rem; background: none; color: inherit;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="profile_photo" id="profileInput" class="form-control" accept="image/*">
                                <div class="form-text text-muted">Recommended size: 400x400px (Max 2MB)</div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color:var(--border-color)">

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

@push('styles')
<style>
    #coverInput, #profileInput {
        background-color: var(--bg-body);
        color: var(--text-color);
        border-color: var(--border-color);
    }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupPreview(inputId, previewImgId, placeholderClass) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewImgId);
        const placeholder = input.closest('.mb-4').querySelector(placeholderClass);

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    setupPreview('coverInput', 'coverPreview', '.preview-placeholder');
    setupPreview('profileInput', 'profilePreview', '.preview-placeholder-profile');
});
</script>
@endsection
