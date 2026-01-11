<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $request->user()->comments()->create([
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        if ($request->wantsJson()) {
            return view('components.comment-item', ['comment' => $comment])->render();
        }

        return back();
    }
}
