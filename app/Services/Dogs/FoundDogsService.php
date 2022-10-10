<?php

namespace App\Services\Dogs;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Models\Dogs;

class FoundDogsService
{


    public function getAllActiveFoundDogs()
    {
        $activeLostDogs = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::FOUND)
            ->paginate(12);

        return $activeLostDogs;
    }
}
