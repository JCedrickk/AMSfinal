<?php
// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type', 'all');
        $year = $request->get('year');
        
        $users = collect();
        $posts = collect();
        
        if ($query) {
            // Search users
            if ($type == 'all' || $type == 'users') {
                $users = User::where('role', 'user')
                    ->where('status', 'approved')
                    ->where(function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhereHas('alumniProfile', function($q2) use ($query) {
                              $q2->where('full_name', 'like', "%{$query}%")
                                 ->orWhere('course', 'like', "%{$query}%")
                                 ->orWhere('job_title', 'like', "%{$query}%");
                          });
                    });
                
                if ($year) {
                    $users->whereHas('alumniProfile', function($q) use ($year) {
                        $q->where('year_graduated', $year);
                    });
                }
                
                $users = $users->paginate(10, ['*'], 'user_page');
            }
            
            // Search posts
            if ($type == 'all' || $type == 'posts') {
                $posts = Post::where('status', 'approved')
                    ->where('content', 'like', "%{$query}%")
                    ->with(['user', 'likes', 'comments'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10, ['*'], 'post_page');
            }
        }
        
        // Get graduation years for filter
        $graduationYears = AlumniProfile::distinct()
            ->orderBy('year_graduated', 'desc')
            ->pluck('year_graduated');
        
        return view('search.index', compact('users', 'posts', 'graduationYears'));
    }
}