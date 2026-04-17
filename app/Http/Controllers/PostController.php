<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function feed()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('feed', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // Max 5MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post-images', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image' => $imagePath,
            'status' => 'pending'
        ]);

        // Notify admin about new post
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'post_pending',
                'message' => 'New post needs approval from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'is_read' => false
            ]);
        }

        return back()->with('success', 'Post submitted for approval.');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if (Auth::user()->isAdmin()) {
            // Admin can directly update
            $post->update(['content' => $request->content]);
            
            // Handle image update
            if ($request->hasFile('image')) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $imagePath = $request->file('image')->store('post-images', 'public');
                $post->update(['image' => $imagePath]);
            }
            
            return redirect()->route('feed')->with('success', 'Post updated successfully.');
        } else {
            // User edit requires approval - store pending edit
            $pendingData = ['edit_pending_content' => $request->content];
            
            if ($request->hasFile('image')) {
                // Store new image temporarily
                $imagePath = $request->file('image')->store('temp-post-images', 'public');
                $pendingData['edit_pending_image'] = $imagePath;
            }
            
            $post->update($pendingData);
            $post->update(['edit_status' => 'pending']);
            
            // Notify admin about edit request
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'post_edit_pending',
                    'message' => 'Post edit request from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name . ' needs approval',
                    'is_read' => false
                ]);
            }
            
            return redirect()->route('feed')->with('success', 'Edit submitted for approval. The post will be updated once approved.');
        }
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Delete associated image
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return back()->with('success', 'Post deleted successfully.');
    }

    public function like(Post $post)
    {
        $existingLike = Like::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Post unliked.';
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id
            ]);
            
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'like',
                    'message' => Auth::user()->first_name . ' ' . Auth::user()->last_name . ' liked your post.',
                    'is_read' => false
                ]);
            }
            
            $message = 'Post liked.';
        }

        return back()->with('success', $message);
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        if ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'comment',
                'message' => Auth::user()->first_name . ' ' . Auth::user()->last_name . ' commented on your post.',
                'is_read' => false
            ]);
        }

        return back()->with('success', 'Comment added.');
    }
}