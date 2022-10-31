<?php

namespace App\Models;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostDogs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const SORTABLE_FIELDS = [
        'lost_at'
    ];

    public function dogs(): BelongsTo
    {
        return $this->belongsTo(Dogs::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public static function allActiveDogs()
    {
        $lostDogs = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::LOST)
            ->join('lost_dogs', 'dogs.id', '=', 'lost_dogs.dog_id')
            ->orderBy('dogs.created_at', 'desc')
            ->paginate(12);

        return $lostDogs;
    }

    public static function allLostDogsByParams($params)
    {
        $query = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::LOST)
            ->join('lost_dogs', 'dogs.id', '=', 'lost_dogs.dog_id');

        if (isset($params['sortBy'], $params['sortValue'])) {
            if (in_array($params['sortBy'], self::SORTABLE_FIELDS)) {
                $query->orderBy(
                    $params['sortBy'],
                    $params['sortValue']
                );
            }
        }

        return $query->paginate(12);
    }

    /**
     * Returns the active dog with type lost by id
     */
    public static function findLostDogById(string $id): Dogs|null
    {
        $lostDog =  Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::LOST)
            ->where('id', $id)
            ->first();

        return $lostDog;
    }
}
