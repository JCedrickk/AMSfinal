<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Import Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumniDirectoryController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\UnifiedPostController; 
use App\Http\Controllers\Profile\AlumniProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Custom)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    Route::get('/pending-approval', function () {
        return view('auth.pending-approval');
    })->name('pending-approval');

    /*
    |--------------------------------------------------------------------------
    | Approved Users Only Gate
    |--------------------------------------------------------------------------
    */
    Route::middleware(['approved'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/notifications/mark-all-read', function () {
            Auth::user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.markAllRead');

        // Unified Posting System
        Route::resource('posts', UnifiedPostController::class);
        
        // Toggle pin for posts (admin only)
        Route::patch('/posts/{post}/toggle-pin', [UnifiedPostController::class, 'togglePin'])->name('posts.toggle-pin');

        // User Profile Management
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'show')->name('profile.show');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });

        Route::get('/profile/alumni', [AlumniProfileController::class, 'edit'])->name('profile.alumni.edit');
        Route::patch('/profile/alumni', [AlumniProfileController::class, 'update'])->name('profile.alumni.update');

        // Community & Interactions
        Route::get('/directory', [AlumniDirectoryController::class, 'index'])->name('directory.index');
        Route::get('/mentors', [MentorshipController::class, 'index'])->name('student.mentors');
        
        Route::post('/like/{type}/{id}', [InteractionController::class, 'toggleLike'])->name('like.toggle');
        Route::post('/comment/{type}/{id}', [InteractionController::class, 'storeComment'])->name('comment.store');

        Route::get('/events/{id}/register', [EventRegistrationController::class, 'showRegisterForm'])->name('events.register');
        Route::post('/events/{id}/register', [EventRegistrationController::class, 'register'])->name('events.submit');

        Route::get('/jobs/{id}/apply', [JobApplicationController::class, 'showApplyForm'])->name('jobs.apply');
        Route::post('/jobs/{id}/apply', [JobApplicationController::class, 'submitApplication'])->name('jobs.submit');

        Route::get('/search', [SearchController::class, 'search'])->name('search.global');

        // Search Users for Directory
        Route::get('/search-users', function(Request $request) {
            $query = $request->get('q');
            
            if (strlen($query) < 2) {
                return response()->json(['users' => []]);
            }
            
            $users = App\Models\User::where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$query}%")
                ->where('user_id', '!=', Auth::id())
                ->limit(10)
                ->get(['user_id', 'first_name', 'last_name', 'name', 'role', 'year_graduated']);
            
            return response()->json(['users' => $users]);
        })->name('search.users');

        /*
        |--------------------------------------------------------------------------
        | Admin Portal (Management)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
            
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            
            // User Management
            Route::controller(AdminUserController::class)->group(function () {
                Route::get('/users', 'index')->name('users.index');
                Route::patch('/users/{user_id}/role', 'updateRole')->name('users.update-role');
                Route::post('/users/{user_id}/approve', 'approve')->name('users.approve');
                Route::delete('/users/{user_id}/decline', 'decline')->name('users.decline');
            });

            // Content Management Aliases
            Route::controller(UnifiedPostController::class)->group(function () {
                // Event Aliases
                Route::get('/events', 'index')->name('events.index');
                Route::get('/events/create', 'create')->name('events.create');
                Route::patch('/posts/{post}/approve-event', 'approve')->name('events.approve');
                Route::delete('/posts/{post}/decline-event', 'destroy')->name('events.decline');

                // Job Aliases
                Route::get('/jobs', 'index')->name('jobs.index');
                Route::get('/jobs/create', 'create')->name('jobs.create');
                Route::patch('/posts/{post}/approve-job', 'approve')->name('jobs.approve');
                Route::delete('/posts/{post}/decline-job', 'destroy')->name('jobs.decline');

                // Management
                Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
            });
        });
    });
});