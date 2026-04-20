<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlumniIDController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\AccountDeletionController;

// Welcome page - Make this the landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Feed
    Route::get('/feed', [PostController::class, 'feed'])->name('feed');
    
    // Posts
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    
    // Profile - Own profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Profile - View other user's profile
    Route::get('/profile/user/{user}', [ProfileController::class, 'showOther'])->name('profile.show.other');
    
    // Change Password
    Route::get('/change-password', [ChangePasswordController::class, 'showChangeForm'])->name('change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    // Account Deletion
    Route::get('/delete-account', [AccountDeletionController::class, 'showDeletionForm'])->name('account.delete');
    Route::post('/delete-account', [AccountDeletionController::class, 'verifyAndDelete'])->name('account.delete.process');
    
    // Alumni ID
    Route::get('/alumni-id/request', [AlumniIDController::class, 'showRequestForm'])->name('alumni-id.request');
    Route::post('/alumni-id/request', [AlumniIDController::class, 'submitRequest'])->name('alumni-id.submit');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Directory & Search
    Route::get('/directory', [ProfileController::class, 'directory'])->name('directory');
    Route::get('/search', [ProfileController::class, 'search'])->name('search');
    
    // API Routes
    Route::get('/api/notifications/unread-count', function() {
        return response()->json([
            'count' => auth()->user()->notifications()->where('is_read', false)->count()
        ]);
    })->middleware('auth');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users/pending', [AdminController::class, 'pendingUsers'])->name('users.pending');
        Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
        Route::get('/users', [AdminController::class, 'allUsers'])->name('users.index');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/{user}/remove-admin', [AdminController::class, 'removeAdmin'])->name('users.remove-admin');
        Route::get('/admins', [AdminController::class, 'adminList'])->name('users.admins');
        Route::get('/users/archived', [AdminController::class, 'archivedUsers'])->name('users.archived');
        Route::post('/users/{id}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');
        
        // Post Management
        Route::get('/posts/pending', [AdminController::class, 'pendingPosts'])->name('posts.pending');
        Route::post('/posts/{post}/approve', [AdminController::class, 'approvePost'])->name('posts.approve');
        Route::post('/posts/{post}/reject', [AdminController::class, 'rejectPost'])->name('posts.reject');
        
        // Edit Management
        Route::get('/posts/pending-edits', [AdminController::class, 'pendingEdits'])->name('posts.pending-edits');
        Route::post('/posts/{post}/approve-edit', [AdminController::class, 'approveEdit'])->name('posts.approve-edit');
        Route::post('/posts/{post}/reject-edit', [AdminController::class, 'rejectEdit'])->name('posts.reject-edit');
        
        // ID Request Management
        Route::get('/id-requests/pending', [AlumniIDController::class, 'pendingRequests'])->name('id-requests.pending');
        Route::post('/id-requests/{idRequest}/approve', [AlumniIDController::class, 'approveRequest'])->name('id-requests.approve');
        Route::post('/id-requests/{idRequest}/reject', [AlumniIDController::class, 'rejectRequest'])->name('id-requests.reject');
        Route::get('/id-requests', [AlumniIDController::class, 'allRequests'])->name('id-requests.index');
        
        // Course Management
        Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
        Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{course}/edit', [AdminController::class, 'editCourse'])->name('courses.edit');
        Route::put('/courses/{course}', [AdminController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminController::class, 'deleteCourse'])->name('courses.delete');
    });
});