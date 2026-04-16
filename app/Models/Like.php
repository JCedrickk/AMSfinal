<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $primaryKey = 'like_id';
    protected $table = 'likes';
    
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}