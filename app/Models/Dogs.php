<?php

namespace App\Models;

use App\Enums\DogListingStatusesEnum as ListingStatuses;
use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

        return $this->belongsTo(Shelter::class, 'shelter_id');
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

    public function isAdoptionListingType(): bool
    {
        return $this->listing_type === ListingTypesEnum::ADOPT;
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
     * Returns the active dog with type lost by id
     */
    public static function findActiveListingById(string $id): Dogs|null
    {
        $lostDog =  Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('id', $id)
            ->first();

        return $lostDog;
    }

    public static function adoptedListingsCountByUser(Shelter $shelter)
    {
        $adoptedListingsCount = Dogs::where('status_id', ListingStatuses::ADOPTED)
            ->where('listing_type', ListingTypesEnum::ADOPT)
            ->where('shelter_id', $shelter->id)
            ->get()
            ->count();
        return $adoptedListingsCount;
    }
}
