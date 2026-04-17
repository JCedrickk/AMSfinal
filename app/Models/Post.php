<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'status',
        'edit_pending_content',
        'edit_status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function hasPendingEdit()
    {
        return $this->edit_status === 'pending';
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    // Accessor for formatted created date
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('F j, Y g:i A');
    }

    // Accessor for relative time
    public function getRelativeTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor for short date
    public function getShortDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }

    // Accessor for time only
    public function getTimeOnlyAttribute()
    {
        return $this->created_at->format('g:i A');
    }
}