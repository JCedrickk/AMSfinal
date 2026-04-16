<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey = 'post_id';
    protected $table = 'posts';
    
    protected $fillable = [
        'user_id',
        'content',
        'status',
        'image_path',
        'location',
        'type',
        'is_pinned',
        'pinned_at',
        'event_date',
        'company',
        'salary',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'pinned_at' => 'datetime',
        'event_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id')
                    ->withTimestamps();
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendees', 'post_id', 'user_id')
                    ->withTimestamps();
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'job_applications', 'post_id', 'user_id')
                    ->withTimestamps();
    }

    public function likeCount()
    {
        return $this->likes()->count();
    }

    public function commentCount()
    {
        return $this->comments()->count();
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
    
    public function isEvent()
    {
        return $this->type === 'event';
    }
    
    public function isJob()
    {
        return $this->type === 'job';
    }
}