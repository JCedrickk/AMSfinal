<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}