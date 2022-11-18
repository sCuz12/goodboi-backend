<?php

namespace App\Repositories;

use App\Enums\DogListingStatusesEnum;
use App\Models\Dogs;
use App\Repositories\Interfaces\DogListingRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\ListingTypesEnum;
use App\Models\Shelter;
use App\Models\User;
use Carbon\Carbon;

class DogRepository implements DogListingRepositoryInterface
{
    /**
     * Get all active dog listings for ADOPTIONS
     * @return LengthAwarePaginator
     */
    public function getAllDogs(string $type = ListingTypesEnum::ADOPT): LengthAwarePaginator
    {
        //TODO: check if is correct listing type 
        $activeDogs = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return $activeDogs;
    }

    /**
     * Get listings fors adoptions based on the setted params
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function getDogsByParams(array $params, string $type = ListingTypesEnum::ADOPT): LengthAwarePaginator
    {
        //only dogs for adoptions
        $query = Dogs::where('listing_type', $type);

        if (isset($params['size'])) {
            $query->where('size', $params['size']);
        }

        if (isset($params['shelter_id'])) {
            $query->where('shelter_id', $params['shelter_id']);
        }

        if (isset($params['city'])) {

            $query->whereIn('city_id', $params['city']);
        }

        if (isset($params['status'])) {
            $query->where('status_id', $params['status']);
        } else {
            //default active
            $query->where('status_id', DogListingStatusesEnum::ACTIVE);
        }

        if (isset($params['sortField'], $params['sortValue'])) {
            // check if the sort field is allowed
            if (in_array($params['sortField'], Dogs::SORTABLE_FIELDS)) {
                $query->orderBy(
                    $params['sortField'],
                    $params['sortValue']
                );
            }
        }

        if (isset($params['gender'])) {
            $query->where('gender', $params['gender']);
        }

        if (isset($params['minAge'])) {
            $from   = Carbon::now()->subYears($params['maxAge'])->format('Y-m-d');
            $to     = Carbon::now()->subYears($params['minAge'])->format('Y-m-d');

            $query->whereBetween('dob', [$from, $to]);
        }

        if (isset($params['orderBy'])) {
            $query->orderBy('created_at', 'DESC');
        }

        return $query->paginate(12);
    }

    /**
     * Search dog listing by id
     * 
     * @return Dogs
     */
    public function getDogById(int $id): Dogs
    {
        return Dogs::where('id', $id)->first();
    }

    public function activeDogsByUser(User $user)
    {
        $activeLostDogs = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('user_id', $user->id)
            ->get();

        return $activeLostDogs;
    }

    /**
     * Get the count all active dog listings of specific shelter
     *
     * @param  int $shelter_id
     * @param  bool $active
     * @return int 
     */
    public function totalListingsByShelter(int $shelter_id, bool $active = false)
    {
        $query = Dogs::where('shelter_id', $shelter_id);
        if ($active) {
            $query->where('status_id', DogListingStatusesEnum::ACTIVE);
        }

        $listingsCount = $query->get()->count();

        return (int) $listingsCount;
    }

    /**
     * Gets the total number of listings favourites of a single shelter 
     *
     * @param  Shelter $shelter
     * @return int
     */
    public function totalFavouritesByShelter(Shelter $shelter): int
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
    public  function totalViewsByShelter(Shelter $shelter): int
    {

        $listings   = $shelter->dogs()->get();
        $totalViews = $listings->map(function ($item, $key) use (&$total) {

            $total = $item->total_views;
            return $total;
        });

        return array_sum($totalViews->all());
    }

    public function totalAdoptedByShelter(Shelter $shelter)
    {
        $adoptedListingsCount = Dogs::where('status_id', DogListingStatusesEnum::ADOPTED)
            ->where('listing_type', ListingTypesEnum::ADOPT)
            ->where('shelter_id', $shelter->id)
            ->get()
            ->count();
        return $adoptedListingsCount;
    }

    /**
     * total count of adopted listings by shelter
     *
     * @param  Shelter $shelter
     * @return void
     */
    public function totalAdoptedListingsByShelter(Shelter $shelter)
    {
        $adoptedListingsCount = Dogs::where('status_id', DogListingStatusesEnum::ADOPTED)
            ->where('listing_type', ListingTypesEnum::ADOPT)
            ->where('shelter_id', $shelter->id)
            ->get()
            ->count();
        return $adoptedListingsCount;
    }


    /**
     * Retrieves the number of listed dogs of User 
     * based on the listing type passed 
     *
     */
    public function totalDogsListedByUser(User $user, string $listing_type = ListingTypesEnum::LOST): int
    {
        $activeDogsCount = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', $listing_type)
            ->where('user_id', $user->id)
            ->get()
            ->count();
        return $activeDogsCount;
    }


    /**
     * Returns the total number of listings that are adopted (all shelters)
     */
    public function getTotalDogsCount(string $listing_type = ListingTypesEnum::FOUND, string $listing_status): int
    {
        $result =  Dogs::where('status_id', $listing_status)
            ->where('listing_type', $listing_type)
            ->count();

        return $result;
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


    /**
     * Increase the total_views counter of dog
     * @return bool
     */
    public static function incrementViewsOfListing(Dogs $dog): bool
    {
        return Dogs::where('id', $dog->id)->increment('total_views');
    }
}
