<?php
// app/Http/Controllers/CommentController.php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
    }
    
    public function destroy(Comment $comment)
    {
        // Check if user owns the comment or is admin
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }
        
        $comment->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}