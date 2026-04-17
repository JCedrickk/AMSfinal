<?php
// app/Models/AlumniProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    protected $primaryKey = 'profile_id';
    protected $table = 'alumni_profiles';
    
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'course',
        'year_graduated',
        'date_of_birth',
        'profile_picture',
        'bio',
        'contact_number',
        'edit_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}