<!DOCTYPE html>
<html>
<head>
    <title>Create New Post</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📝 Create New Post</h4>
                </div>

                <div class="card-body">

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Create Post Form --}}
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control"
                                value="{{ old('title') }}"
                                placeholder="Enter post title"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea
                                name="content"
                                id="content"
                                class="form-control"
                                rows="5"
                                placeholder="Write your post content here"
                                required>{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image (optional)</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <small class="text-muted">Accepted formats: jpg, jpeg, png (max: 2MB)</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">✅ Submit</button>
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">⬅ Back</a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
