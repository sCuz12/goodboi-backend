<?php

namespace App\Models;

use App\Enums\DogListingStatusesEnum as ListingStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Dogs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'dob',
        'user_id',
        'shelter_id',
        'cover_image',
        'title',
        'slug',
        'breed_id',
        'status_id',
        'city_id',
        'size'
    ];

    const COVER_IMAGES_PATH = "images/cover_images/listings/";

    //One to many with Breed
    public function breed()
    {
        return $this->belongsTo('App\Breed');
    }

    //relationship with images
    public function dog_images()
    {
        return $this->hasMany(DogListingImages::class, 'dog_id');
    }

    /**
     * each dog listings belongs to one city  
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    /**
     * each dog listing belongs to one country    
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function healthBook()
    {
        return $this->hasOne(AnimalHealthBook::class, 'dog_id');
    }
    /** 
     * Get the favourites listing of user on pivot table favourites
     *
     * @return BelongsToMany
     */
    public function favourites()
    {
        return $this->belongsToMany(Dogs::class, 'favourites', 'dog_id', 'user_id')->withTimestamps();
    }

    /**
     * Retrieves the cover image of dog listing and append the path
     *
     * @return String
     */
    public function getCoverImagePath()
    {
        return asset(self::COVER_IMAGES_PATH . $this->cover_image);
    }

    /**
     * Get all active dog listings
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public static function getAllActiveDogs(): LengthAwarePaginator
    {
        $activeDogs = Dogs::where('status_id', ListingStatuses::ACTIVE)->paginate(12);
        return $activeDogs;
    }

    /**
     * Get listings based on the setted params
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public static function getListingsByParams($params): LengthAwarePaginator
    {
        $query = Dogs::where('status_id', ListingStatuses::ACTIVE);

        if (isset($params['size'])) {
            $query->where('size', $params['size']);
        }

        if (isset($params['shelter_id'])) {
            $query->where('shelter_id', $params['shelter_id']);
        }

        if (isset($params['city'])) {
            $query->whereIn('city_id', $params['city']);
        }

        return $query->paginate(10);
    }

    /**
     * Search dog listing by id
     *
     * @param  int $id
     * @return LengthAwarePaginator
     */
    public static function findById($id)
    {
        return Dogs::where('id', $id)->first();
    }

    /**
     * Get the count all active dog listings of specific shelter
     *
     * @param  int $shelter_id
     * @param  bool $active
     * @return int 
     */
    public static function getListingsCountByShelter(int $shelter_id, bool $active = false)
    {
        $query = Dogs::where('shelter_id', $shelter_id);
        if ($active) {
            $query->where('status_id', ListingStatuses::ACTIVE);
        }
        $listingsCount = $query->get()->count();
        return (int) $listingsCount;
    }

    /**
     * Check wether specific user has favourite specific dog on pibvot table favourites
     *
     * @param  int $user_id
     * @return bool 
     */
    public function isFavoritedByExist($user_id): bool
    {
        return DB::table('favourites')->where('dog_id', $this->id)->where('user_id', $user_id)->exists();
    }
}
