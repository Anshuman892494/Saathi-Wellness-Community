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

                <form method="POST" action="{{ route('posts.update', $post->_id) }}" novalidate>
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
@endsection
