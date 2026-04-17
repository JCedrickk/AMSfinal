<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'course',
        'year_graduated',
        'birthday',
        'contact_number',
        'job_title',
        'address',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'github',
        'show_contact_number',
        'show_birthday',
        'show_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return null;
    }

    public function getFacebookUrlAttribute()
    {
        if ($this->facebook && !str_starts_with($this->facebook, 'http')) {
            return 'https://facebook.com/' . $this->facebook;
        }
        return $this->facebook;
    }

    public function getTwitterUrlAttribute()
    {
        if ($this->twitter && !str_starts_with($this->twitter, 'http')) {
            return 'https://twitter.com/' . $this->twitter;
        }
        return $this->twitter;
    }

    public function getInstagramUrlAttribute()
    {
        if ($this->instagram && !str_starts_with($this->instagram, 'http')) {
            return 'https://instagram.com/' . $this->instagram;
        }
        return $this->instagram;
    }

    public function getLinkedinUrlAttribute()
    {
        if ($this->linkedin && !str_starts_with($this->linkedin, 'http')) {
            return 'https://linkedin.com/in/' . $this->linkedin;
        }
        return $this->linkedin;
    }

    public function getGithubUrlAttribute()
    {
        if ($this->github && !str_starts_with($this->github, 'http')) {
            return 'https://github.com/' . $this->github;
        }
        return $this->github;
    }
}