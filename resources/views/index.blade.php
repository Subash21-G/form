<!DOCTYPE html>
<html>
<head>
    <title>All Posts</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📰 All Posts</h2>
        <a href="{{ route('posts.create') }}" class="btn btn-success">➕ Create New Post</a>

    </div>

    {{-- Show success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Display all posts --}}
    @forelse ($posts as $post)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                {{-- ✅ Show image if available --}}
                @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid mb-3 rounded" width="300">
                @endif

                <h4 class="card-title">{{ $post->title }}</h4>
                <p class="card-text">{{ $post->content }}</p>

                <div class="mt-3">
                    <a href="/posts/{{ $post->id }}/edit" class="btn btn-sm btn-outline-primary">✏️ Edit</a>

                    {{-- ✅ Make sure method DELETE works --}}
                    <form action="/posts/{{ $post->id }}/delete" method="GET" class="d-inline">
                        {{-- If you're using GET instead of DELETE --}}
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                            ❌ Delete
                        </button>
                    </form>

                    {{-- ✅ If you're using method="POST" with @method('DELETE'), then use this instead:
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger"
            onclick="return confirm('Are you sure you want to delete this post?')">
        ❌ Delete
    </button>
</form>

                    --}}
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning text-center">
            No posts found.
        </div>
    @endforelse
</div>

</body>
</html>
