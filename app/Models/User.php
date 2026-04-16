<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'first_name',
        'last_name',
        'year_graduated',
        'strand',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function profile()
    {
        return $this->hasOne(AlumniProfile::class, 'user_id', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id')
                    ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}