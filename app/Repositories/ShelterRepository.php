<?php

namespace App\Repositories;

use App\Models\Shelter;
use App\Repositories\Interfaces\ShelterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShelterRepository implements ShelterRepositoryInterface
{

    /**
     * Retrieves all verified shelters 
     *
     * @return LengthAwarePaginator
     */
    public function getVerifiedShelters(): LengthAwarePaginator
    {
        $sheltersFounded = Shelter::where('is_profile_complete', 1)->paginate(10);
        return $sheltersFounded;
    }

    /**
     * retrieves shelter by id 
     *
     * @return Collection
     */
    public static function getShelterById(int $id)
    {
        if (!$id) {
            return false;
        }

        return Shelter::where('id', $id)->first();
    }

    /**
     * Get Shelters by params (city_id)
     *
     * @param  array $params
     * @return LengthAwarePaginator 
     */
    public function getSheltersByParams(array $params)
    {
        $query = Shelter::where('is_profile_complete', 1);

        if (isset($params['city'])) {
            $query->whereIn('city_id', $params['city']);
        }

        return $query->paginate(10);
    }
}
