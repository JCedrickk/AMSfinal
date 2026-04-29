<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load([
            'profile',
            'posts' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
        ]);

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user()->load('profile');
        $courses = Course::orderBy('sort_order')->get();
        return view('profile.edit', compact('user', 'courses'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'birthday' => 'nullable|date',
            'contact_number' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'show_contact_number' => 'nullable|boolean',
            'show_birthday' => 'nullable|boolean',
            'show_address' => 'nullable|boolean',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255'
        ]);

        $profile = Auth::user()->profile;
        $course = Course::find($request->course_id);
        
        // Handle profile picture upload...
        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $profile->profile_picture = $path;
        }
        
        $profile->birthday = $request->birthday;
        $profile->contact_number = $request->contact_number;
        $profile->job_title = $request->job_title;
        $profile->address = $request->address;
        $profile->show_contact_number = $request->has('show_contact_number');
        $profile->show_birthday = $request->has('show_birthday');
        $profile->show_address = $request->has('show_address');
        $profile->facebook = $request->facebook;
        $profile->twitter = $request->twitter;
        $profile->instagram = $request->instagram;
        $profile->linkedin = $request->linkedin;
        $profile->github = $request->github;
        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function directory()
    {
        $alumni = User::with('profile.courseData')
            ->where('role', 'user')
            ->where('status', 'approved')
            ->paginate(20);
        
        $courses = Course::orderBy('sort_order')->get();
        $years = Profile::distinct()->orderBy('year_graduated', 'desc')->pluck('year_graduated');
        
        return view('directory', compact('alumni', 'courses', 'years'));
    }

    public function search(Request $request)
    {
        if (!$request->filled('search') && !$request->filled('course_id') && !$request->filled('year')) {
            $alumni = collect([]);
            $courses = Course::orderBy('sort_order')->get();
            $years = Profile::distinct()->orderBy('year_graduated', 'desc')->pluck('year_graduated');
            return view('directory', compact('alumni', 'courses', 'years'));
        }

        $query = User::with('profile.courseData')
            ->where('role', 'user')
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhereHas('profile', function($q2) use ($search) {
                    $q2->whereHas('courseData', function($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
                });
            });
        }

        if ($request->filled('course_id')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        if ($request->filled('year')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('year_graduated', $request->year);
            });
        }

        $alumni = $query->paginate(20);
        $courses = Course::orderBy('sort_order')->get();
        $years = Profile::distinct()->orderBy('year_graduated', 'desc')->pluck('year_graduated');

        return view('directory', compact('alumni', 'courses', 'years'));
    }

    public function showOther(User $user)
    {
        // Load the user with their profile and posts sorted by recent first
        $user->load([
            'profile',
            'posts' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'posts.comments.user',
            'posts.likes'
        ]);
        
        return view('profile.show-other', compact('user'));
    }
}