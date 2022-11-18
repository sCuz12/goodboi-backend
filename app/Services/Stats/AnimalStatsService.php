<?php

namespace App\Services\Stats;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Repositories\DogRepository;
use Error;
use Exception;

class AnimalStatsService
{
    protected DogRepository $dogRepository;

    public function __construct()
    {
        $this->dogRepository = (new DogRepository());
    }

    public function getGeneralStats()
    {
        $data = [];

        $totalActiveAdoptionDogs = $this->dogRepository->getTotalDogsCount(ListingTypesEnum::ADOPT, DogListingStatusesEnum::ACTIVE);
        $totalAdoptedAnimals     = $this->dogRepository->getTotalDogsCount(ListingTypesEnum::ADOPT, DogListingStatusesEnum::ADOPTED);
        $totalLostedAnimals      = $this->dogRepository->getTotalDogsCount(ListingTypesEnum::LOST, DogListingStatusesEnum::ACTIVE);
        $totalFoundedAnimals     = $this->dogRepository->getTotalDogsCount(ListingTypesEnum::FOUND, DogListingStatusesEnum::ACTIVE);

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
