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
    ];

    const COVER_IMAGES_PATH = "images/cover_images/profiles/";

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
        return asset(self::COVER_IMAGES_PATH . $this->user->cover_photo);
    }

    public static function getVerifiedShelters()
    {
        $sheltersFounded = Shelter::where('is_profile_complete', 1)->paginate(10);
        return $sheltersFounded;
    }

    /**
     * Get Shelters by params (city_id)
     *
     * @param  array $params
     * @return LengthAwarePaginator 
     */
    public static function getSheltersByParams($params)
    {
        $query = Shelter::where('is_profile_complete', 1);

        if (isset($params['city'])) {
            $query->whereIn('city_id', $params['city']);
        }

        return $query->paginate(10);
    }

    /**
     * Static function that retrieves shelter by uid 
     *
     * @param  int $id
     * @return Collection
     */
    public static function findById(int $id)
    {
        if (!$id) {
            return false;
        }

        return Shelter::where('id', $id)->first();
    }
}
