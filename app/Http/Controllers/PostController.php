<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Show all posts
    public function index()
    {
        $posts = Post::latest()->get();
        return view('index', compact('posts'));
    }

    // Show the Google Form-like user form
    public function create()
    {
        return view('form'); // Make sure your Blade file is form.blade.php in resources/views
    }

    // Store the user form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'password'    => 'required|string|min:6',
            'description' => 'nullable|string|max:1000',
            'gender'      => 'required|in:Male,Female',
            'skills'      => 'nullable|array',
            'skills.*'    => 'string|max:30',
            'dob'         => 'nullable|date',
            'age'         => 'nullable|integer|min:0|max:150',
            'experience'  => 'nullable|integer|min:0|max:100',
            'email'       => 'required|email|max:255',
        ]);

        // Process or log the form data (or save to a DB if needed)
        logger()->info('User form submitted', $validated);

        return redirect()->route('form.create')->with('success', 'Form submitted successfully!');
    }

    // Show the form to edit a post
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('edit', compact('post'));
    }

    // Update the post with optional new image
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|string|min:3',
            'content' => 'required|string|min:5',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $post = Post::findOrFail($id);
        $data = $request->only(['title', 'content']);

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('posts', $filename, 'public');
            $data['image'] = $path;
        }

        $post->update($data);

        return redirect('/posts')->with('success', 'Post updated!');
    }

    // Delete the post and its image if exists
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect('/posts')->with('success', 'Post deleted!');
    }
}
