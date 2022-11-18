<?php

namespace App\Repositories\Interfaces;

interface ShelterRepositoryInterface
{
    public static function getShelterById(int $id);
    public function getSheltersByParams(array $params);
    public function getVerifiedShelters();
}
