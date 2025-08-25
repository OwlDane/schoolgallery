<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_name',
        'school_logo',
        'address',
        'phone',
        'email',
        'description',
        'vision',
        'mission',
        'operational_hours',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'map_embed',
    ];

    public static function getProfile()
    {
        return self::first() ?? new self();
    }
}