<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniIdRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_date',
        'status',
        'alumni_id_number',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}