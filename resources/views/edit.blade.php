<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">✏️ Edit Post</h4>
                </div>

                <div class="card-body">

                    {{-- Show validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Edit form --}}
                    <form action="/posts/{{ $post->id }}/update" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control"
                                value="{{ old('title', $post->title) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea
                                name="content"
                                id="content"
                                class="form-control"
                                rows="5"
                                required>{{ old('content', $post->content) }}</textarea>
                        </div>

                        {{-- Show current image --}}
                        @if ($post->image)
                            <div class="mb-3">
                                <label class="form-label">Current Image</label><br>
                                <img src="{{ asset('storage/' . $post->image) }}" width="250" class="rounded shadow-sm mb-2">
                            </div>
                        @endif

                        {{-- Upload new image --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload New Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <small class="text-muted">Leave blank to keep existing image.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">💾 Update Post</button>
                            <a href="/posts" class="btn btn-secondary">⬅ Back</a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
