<?php

namespace App\Models;

use App\Enums\DogListingStatusesEnum as ListingStatuses;
use App\Enums\ListingTypesEnum;
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
        'size',
        'gender',
        'color',
        'listing_type',
    ];

    const SORTABLE_FIELDS = [
        'name',
        'total_views',
        'title',
        'created_at'
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
     * Get the Lost_Dog associated with the dog.
     * 
     * Syntax: return $this->hasOne(LostDogs::class, 'foreign_key', 'local_key');
     *
     * Example: return $this->hasOne(LostDogs::class, 'user_id', 'id');        
     */
    public function lostDog()
    {
        return $this->hasOne(LostDogs::class, 'dog_id', 'id');
    }

    public function foundDog()
    {
        return $this->hasOne(FoundDogs::class, 'dog_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Returns true if specific listing is type of lost 
     *
     */
    public function isLostListingType(): bool
    {
        return $this->listing_type === ListingTypesEnum::LOST;
    }

    /**
     * Returns true if specific listing is type of found 
     *
     */
    public function isFoundListingType(): bool
    {
        return $this->listing_type === ListingTypesEnum::FOUND;
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
     * Get all active dog listings for ADOPTIONS
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public static function getAllActiveAdoptionDogs(): LengthAwarePaginator
    {

        $activeDogs = Dogs::where('status_id', ListingStatuses::ACTIVE)
            ->where('listing_type', ListingTypesEnum::ADOPT)
            ->paginate(12);
        return $activeDogs;
    }

    /**
     * Get listings fors adoptions based on the setted params
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public static function getListingsByParams($params): LengthAwarePaginator
    {
        $query = Dogs::where('status_id', ListingStatuses::ACTIVE)
            ->where('listing_type', ListingTypesEnum::ADOPT);

        //only dogs for adoptions
        $query->where('listing_type', ListingTypesEnum::ADOPT);

        if (isset($params['size'])) {
            $query->where('size', $params['size']);
        }

        if (isset($params['shelter_id'])) {
            $query->where('shelter_id', $params['shelter_id']);
        }

        if (isset($params['city'])) {
            $query->whereIn('city_id', $params['city']);
        }

        if (isset($params['sortField'], $params['sortValue'])) {
            // check if the sort field is allowed
            if (in_array($params['sortField'], self::SORTABLE_FIELDS)) {
                $query->orderBy(
                    $params['sortField'],
                    $params['sortValue']
                );
            }
        }

        if (isset($params['gender'])) {
            $query->where('gender', $params['gender']);
        }

        return $query->paginate(10);
    }

    /**
     * Search dog listing by id
     *
     * @param  int $id
     * @return Dogs
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

    /**
     * Increase the total_views counter of dog
     *
     * @param  mixed $dog
     * @return void
     */
    public static function incrementViews(Dogs $dog)
    {

        return Dogs::where('id', $dog->id)->increment('total_views');
    }

    /**
     * Get the number of favourites for the dog
     *
     * @return int
     */
    public function getCountOfFavourites()
    {
        return DB::table('favourites')->where('dog_id', $this->id)->count();
    }

    /**
     * Gets the total number of listings favourites of a single shelter 
     *
     * @param  Shelter $shelter
     * @return int
     */
    public static function totalFavouritesByShelter(Shelter $shelter): int
    {
        $listings = $shelter->dogs()->get();
        $total    = 0;

        foreach ($listings as $listing) {
            $total += $listing->getCountOfFavourites();
        }

        return $total;
    }

    /**
     * Gets the total number of listings views of a single shelter 
     *
     * @param  Shelter $shelter
     * @return int
     */
    public static function totalViewsByShelter(Shelter $shelter): int
    {

        $listings   = $shelter->dogs()->get();
        $totalViews = $listings->map(function ($item, $key) use (&$total) {

            $total = $item->total_views;
            return $total;
        });

        return array_sum($totalViews->all());
    }


    /**
     * Retrieves the number of active lost by user

     */
    public static function activeLostDogCountByUser(User $user): int
    {
        $activeDogsCount = Dogs::where('status_id', ListingStatuses::ACTIVE)
            ->where('listing_type', ListingTypesEnum::LOST)
            ->where('user_id', $user->id)
            ->get()
            ->count();
        return $activeDogsCount;
    }

    public static function getActiveListingsByUser(User $user)
    {
        $activeLostDogs = Dogs::where('status_id', ListingStatuses::ACTIVE)
            ->where('listing_type', ListingTypesEnum::LOST)
            ->orWhere('listing_type', ListingTypesEnum::FOUND)
            ->where('user_id', $user->id)
            ->get();

        return $activeLostDogs;
    }
}
