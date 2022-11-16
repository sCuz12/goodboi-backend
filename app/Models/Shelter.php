<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shelter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shelter_name',
        'address',
        'phone',
        'description',
        'user_id',
        'slug',
        'city_id',
        'is_profile_complete',
        'instagram',
        'facebook',
        'facebook_pagename'
    ];

    const COVER_IMAGES_PATH   = "images/cover_images/profiles/";
    const DEFAULT_COVER_PHOTO = "shelter_default.png";

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * each shelter belongs to one city    
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function dogs(): HasMany
    {
        return $this->hasMany(Dogs::class);
    }

    /* Retrieves the cover image of dog listing and append the path
    *
    * @return String
    */
    public function getCoverImagePath()
    {
        $cover_photo = $this->user->cover_photo ?? self::DEFAULT_COVER_PHOTO;
        return asset(self::COVER_IMAGES_PATH . $cover_photo);
    }
}
