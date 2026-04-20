<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'sort_order',
        'is_active'
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'course_id');
    }
}