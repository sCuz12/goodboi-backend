<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface DogListingRepositoryInterface
{
    public function getAllDogs(string $type);
    public function getDogsByParams(array $params);
    public function getDogById(int $id);
    public function activeDogsByUser(User $user);
}
