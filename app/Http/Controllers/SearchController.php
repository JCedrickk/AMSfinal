<?php
// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return view('search.index', [
                'posts' => collect(),
                'users' => collect(),
                'query' => $query
            ]);
        }
        
        // Search Posts
        $posts = Post::where('status', 'approved')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->with('user')
            ->latest()
            ->get();
        
        // Search Users
        $users = User::where('status', 'approved')
            ->where('role', 'alumni')
            ->where(function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->with('profile')
            ->limit(20)
            ->get();
        
        return view('search.index', compact('posts', 'users', 'query'));
    }
}