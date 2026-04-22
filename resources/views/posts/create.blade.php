@extends('layouts.app')
@section('title', 'Create a Post')

@section('content')
<div class="page-hero" style="padding:2.5rem 0 2rem">
    <div class="container">
        <h1 class="mb-1" style="font-size:1.8rem"><i class="bi bi-pencil-square me-2"></i>Share Your Story</h1>
        <p class="lead" style="font-size:.95rem">Your story could inspire someone's wellness journey</p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-wellness p-4">

                @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="background:rgba(220,53,69,.1);border:1px solid rgba(220,53,69,.3);color:#f87171;font-size:.85rem">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Post Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Give your post a meaningful title…" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $cat)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tags <span class="text-muted fw-400">(comma-separated)</span></label>
                            <input type="text" name="tags" class="form-control"
                                   value="{{ old('tags') }}" placeholder="yoga, stress-relief, sleep">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Add an Image <span class="text-muted fw-400">(optional)</span></label>
                        <div class="image-upload-wrapper" style="border: 2px dashed var(--border-color); border-radius: 8px; padding: 20px; text-align: center; background: var(--card-bg); transition: all 0.3s ease;">
                            <img id="postImagePreview" style="max-width: 100%; max-height: 300px; border-radius: 6px; display: none; margin: 0 auto 15px;">
                            <div id="uploadPlaceholder">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem; color: var(--brand-green);"></i>
                                <p class="mb-2 mt-2">Drag and drop an image, or click to browse</p>
                                <p class="small text-muted mb-0">Max size: 5MB (JPEG, PNG, WEBP)</p>
                            </div>
                            <input type="file" name="image" id="postImage" class="form-control" accept="image/*" style="margin-top: {{ old('image') ? '0' : '15px' }};">
                            @error('image')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                  rows="10" placeholder="Share your experience, tips, or story…" required>{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send-fill me-2"></i>Publish Post
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('postImage');
    const preview = document.getElementById('postImagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    });
});
</script>
@endsection
