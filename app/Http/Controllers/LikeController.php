<?php
// app/Http/Controllers/LikeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($postId)
    {
        $post = Post::findOrFail($postId);
        $userId = Auth::id();
        
        $existingLike = Like::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();
        
        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            $liked = true;
            
            // Create notification for post owner
            if ($post->user_id != $userId) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'like',
                    'message' => Auth::user()->full_name . ' liked your post: ' . $post->title,
                    'data' => json_encode(['post_id' => $postId]),
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }
}