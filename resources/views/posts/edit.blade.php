@extends('layouts.app')
@section('title', 'Edit Post')

@section('content')
<div class="page-hero" style="padding:2.5rem 0 2rem">
    <div class="container">
        <h1 class="mb-1" style="font-size:1.8rem"><i class="bi bi-pencil me-2"></i>Edit Post</h1>
        <p class="lead" style="font-size:.95rem">Update your content to keep the community informed</p>
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

                <form method="POST" action="{{ route('posts.update', $post->_id) }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Post Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $post->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $post->category) == $cat ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $cat)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control"
                                   value="{{ old('tags', implode(', ', $post->tags ?? [])) }}" placeholder="yoga, sleep">
                        </div>
                    </div>

                    <!-- Image Section (Optional) -->
                    <div class="mb-4">
                        <label class="form-label">Update Image <span class="text-muted fw-400">(optional)</span></label>
                        
                        <!-- Local File Upload Wrapper -->
                        <div class="image-upload-wrapper mb-3" style="border: 2px dashed var(--border-color); border-radius: 8px; padding: 20px; text-align: center; background: var(--card-bg); transition: all 0.3s ease;">
                            <img id="postImagePreview" src="{{ $post->image ?? '' }}" style="max-width: 100%; max-height: 300px; border-radius: 6px; display: {{ !empty($post->image) ? 'block' : 'none' }}; margin: 0 auto 15px;">
                            <div id="uploadPlaceholder" style="display: {{ !empty($post->image) ? 'none' : 'block' }};">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem; color: var(--brand-green);"></i>
                                <p class="mb-2 mt-2">Drag and drop a new image, or click to browse</p>
                                <p class="small text-muted mb-0">Max size: 5MB (JPEG, PNG, WEBP)</p>
                            </div>
                            <input type="file" name="image" id="postImage" class="form-control mt-3" accept="image/*">
                            @error('image')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>

                        <!-- OR Separator -->
                        <div class="text-center text-muted my-3 position-relative">
                            <hr style="border-top: 1px solid var(--border-color); margin: 15px 0;">
                            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--card-bg); padding: 0 15px; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: var(--brand-green);">OR</span>
                        </div>

                        <!-- External Image URL Field -->
                        <div class="mt-3">
                            <label class="form-label">External Image URL</label>
                            <input type="url" name="image_url" id="postImageUrl" class="form-control @error('image_url') is-invalid @enderror"
                                   value="{{ old('image_url', $post->image ?? '') }}" placeholder="https://example.com/image.jpg">
                            <div class="form-text">Paste a direct image link (e.g. ending in .jpg, .png, .webp).</div>
                            @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                  rows="10" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Post
                        </button>
                        <a href="{{ route('posts.show', $post->_id) }}" class="btn btn-outline-primary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('postImage');
    const urlInput = document.getElementById('postImageUrl');
    const preview = document.getElementById('postImagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    function updatePreview(src) {
        if (src) {
            preview.src = src;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                updatePreview(e.target.result);
                // Clear URL input since file is chosen
                urlInput.value = '';
            }
            reader.readAsDataURL(this.files[0]);
        } else if (!urlInput.value) {
            updatePreview('');
        }
    });

    urlInput.addEventListener('input', function() {
        if (this.value) {
            updatePreview(this.value);
            // Clear file input selection since URL is entered
            input.value = '';
        } else if (!input.files || !input.files[0]) {
            updatePreview('');
        }
    });
});
</script>
@endsection
