<?php
// app/Http/Controllers/CommentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);
        
        $post = Post::findOrFail($postId);
        
        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);
        
        // Create notification for post owner
        if ($post->user_id != Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'comment',
                'message' => Auth::user()->full_name . ' commented on your post: ' . $post->title,
                'data' => json_encode(['post_id' => $postId, 'comment_id' => $comment->comment_id]),
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Comment added',
            'comment' => $comment,
            'user_name' => Auth::user()->full_name,
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $comment->delete();
        
        return back()->with('success', 'Comment deleted successfully');
    }
}