<?php

namespace App\Services\Stats;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Models\Dogs;

class AnimalStatsService
{

    public function getGeneralStats()
    {
        $data = [];

        $totalActiveAdoptionDogs = Dogs::getTotalDogsCount(ListingTypesEnum::ADOPT, DogListingStatusesEnum::ACTIVE);
        $totalAdoptedAnimals     = Dogs::getTotalDogsCount(ListingTypesEnum::ADOPT, DogListingStatusesEnum::ADOPTED);
        $totalLostedAnimals      = Dogs::getTotalDogsCount(ListingTypesEnum::LOST, DogListingStatusesEnum::ACTIVE);
        $totalFoundedAnimals     = Dogs::getTotalDogsCount(ListingTypesEnum::FOUND, DogListingStatusesEnum::ACTIVE);

        $data[] = [
            'name' => "Dogs for Adoption",
            'count' => $totalActiveAdoptionDogs,
        ];
        $data[] = [
            'name' => "Total Adopted Dogs",
            'count' => $totalAdoptedAnimals,
        ];

        $data[] = [
            'name' => "Lost Dogs",
            'count' => $totalLostedAnimals,
        ];

        $data[] = [
            'name' => "Found Dogs",
            'count' => $totalFoundedAnimals,
        ];

        return $data;
    }
}
