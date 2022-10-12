<?php

namespace App\Services\Dogs;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Exceptions\IdNotProvidedException;
use App\Exceptions\ListingNotFoundException;
use App\Models\Dogs;
use Illuminate\Pagination\LengthAwarePaginator;

class FoundDogsService
{


    /**
     * Retrieve all active listings with type = found
     *
     * @return Collection
     */
    public function getAllActiveFoundDogs(): LengthAwarePaginator
    {
        $activeFoundDogs = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::FOUND)
            ->paginate(12);

        return $activeFoundDogs;
    }


    /**
     * Retrieve single dog listing
     *
     * @param  string $id
     * @return Dogs
     */
    public function getActiveDogById($id): Dogs
    {

        if (!$id) {
            throw new IdNotProvidedException;
        }

        $dog = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::FOUND)
            ->where('id', $id)
            ->first();

        if (!$dog) {
            throw new ListingNotFoundException;
        }
        return $dog;
    }
}
