<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'comments.replies.user', 'likes'])
                     ->latest()
                     ->paginate(10);

        // Fetch recent comments on Auth user's posts (Notifications)
        $recent_comments = \App\Models\Comment::whereHas('post', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id()) // Don't show own comments
            ->with(['user', 'post'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('posts', 'recent_comments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $post = $request->user()->posts()->create([
            'content' => $request->content,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                $post->images()->create([
                    'image_path' => '/storage/' . $path
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('components.post-card', ['post' => $post])->render()
            ]);
        }

        return back()->with('status', 'Post created successfully!');
    }
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'exists:post_images,id'
        ]);

        // 1. Update Content
        $post->update(['content' => $request->content]);

        // 2. Remove Deleted Images
        if ($request->has('remove_images')) {
            $imagesToDelete = $post->images()->whereIn('id', $request->remove_images)->get();
            foreach ($imagesToDelete as $img) {
                // Delete file from storage
                $path = str_replace('/storage/', '', $img->image_path);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
                $img->delete();
            }
        }

        // 3. Add New Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                $post->images()->create([
                    'image_path' => '/storage/' . $path
                ]);
            }
        }

        // Return updated component HTML for frontend replacement
        return response()->json([
            'html' => view('components.post-card', ['post' => $post->fresh()])->render(),
            'message' => 'Postingan berhasil diperbarui!'
        ]);
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
             abort(403, 'Unauthorized action.');
        }

        // Delete all images from storage
        foreach ($post->images as $img) {
            $path = str_replace('/storage/', '', $img->image_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        }

        $post->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Postingan dihapus!']);
        }

        return back()->with('success', 'Postingan dihapus!');
    }
}
