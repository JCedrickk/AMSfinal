<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    protected $primaryKey = 'profile_id';
    protected $table = 'alumni_profiles';
    
    protected $fillable = [
        'user_id',
        'full_name',
        'course',
        'year_graduated',
        'contact_number',
        'job_title',
        'company',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'linkedin_link',
        'profile_picture',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}