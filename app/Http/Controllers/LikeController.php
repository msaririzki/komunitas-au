<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $like = $request->user()->likes()->where('post_id', $post->id)->first();
        $liked = false;

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $request->user()->likes()->create([
                'post_id' => $post->id,
            ]);
            $liked = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'count' => $post->likes()->count(),
            ]);
        }

        return back();
    }
}
