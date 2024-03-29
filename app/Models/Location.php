<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function lostDogs()
    {
        return $this->hasOne(LostDogs::class);
    }

    public function foundDogs()
    {
        return $this->hasOne(FoundDogs::class);
    }
    /**
     * Retrieves the all the cities of the specific country
     *
     * @param  mixed $countryId
     * @return Collection
     */
    public static function getLocationsByCity(string $cityId)
    {
        $locations = Location::where('city_id', $cityId)
            ->orderBy('name')
            ->get();
        return $locations;
    }
}
