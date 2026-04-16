<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
    }

    public function index()
    {
        // Only show approved posts
        $posts = Post::where('status', 'approved')
                     ->with(['user', 'comments.user', 'likes'])
                     ->orderBy('created_at', 'desc')
                     ->get();
        
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:5000',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'status' => 'pending', // Needs admin approval
        ]);

        return redirect()->route('posts.index')->with('success', 
            'Post submitted for admin approval.');
    }

    public function edit(Post $post)
    {
        // Users can only edit their own posts
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:5000',
        ]);

        $post->update([
            'content' => $validated['content'],
            'status' => 'pending', // Needs re-approval after edit
        ]);

        return redirect()->route('posts.index')->with('success', 
            'Post updated and submitted for re-approval.');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function comment(Request $request, Post $post)
    {
        // Only approved users can comment on approved posts
        if (!$post->isApproved()) {
            return redirect()->back()->with('error', 'Cannot comment on unapproved posts.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:1|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        // Notify post owner (if not self)
        if ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'comment',
                'message' => Auth::user()->name . ' commented on your post.',
            ]);
        }

        return redirect()->route('posts.index')->with('success', 'Comment added.');
    }

    public function like(Post $post)
    {
        if (!$post->isApproved()) {
            return redirect()->back()->with('error', 'Cannot like unapproved posts.');
        }

        $existingLike = Like::where('user_id', Auth::id())
                            ->where('post_id', $post->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Post unliked.';
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);

            // Notify post owner (if not self)
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'like',
                    'message' => Auth::user()->name . ' liked your post.',
                ]);
            }
            
            $message = 'Post liked.';
        }

        return redirect()->route('posts.index')->with('success', $message);
    }

    /**
     * Handle like/unlike via AJAX
     */
    public function likeAjax(Post $post)
    {
        if (!$post->isApproved()) {
            return response()->json(['success' => false, 'message' => 'Cannot like unapproved posts.'], 400);
        }

        $existingLike = Like::where('user_id', Auth::id())
                            ->where('post_id', $post->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            $message = 'Post unliked.';
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);

            // Notify post owner (if not self)
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'like',
                    'message' => Auth::user()->name . ' liked your post.',
                ]);
            }
            
            $liked = true;
            $message = 'Post liked.';
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
            'message' => $message
        ]);
    }

    /**
     * Handle comment via AJAX
     */
    public function commentAjax(Request $request, Post $post)
    {
        if (!$post->isApproved()) {
            return response()->json(['success' => false, 'message' => 'Cannot comment on unapproved posts.'], 400);
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:1|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        // Notify post owner (if not self)
        if ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'comment',
                'message' => Auth::user()->name . ' commented on your post: "' . Str::limit($validated['comment'], 50) . '"',
            ]);
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => Auth::user()->name,
                'created_at' => $comment->created_at->diffForHumans()
            ],
            'message' => 'Comment added successfully.'
        ]);
    }

    /**
     * Delete comment via AJAX
     */
    public function deleteComment(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }
}