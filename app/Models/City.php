<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    // Each city has many dog listings
    public function dogs()
    {
        return $this->hasMany(Dogs::class);
    }

    /**
     * Each City has many shelters    
     *
     * @return HasMany
     */
    public function shelter(): HasMany
    {
        return $this->hasMany(Shelter::class);
    }

    /**
     * Each City has many locations (ex:kaimakli,aglatzia)    
     *
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Retrieves the all the cities of the specific country
     *
     * @param  mixed $countryId
     * @return Collection
     */
    public static function getCityByCountryId($countryId)
    {
        $cities = City::where('country_id', $countryId)
            ->get();
        return $cities;
    }
}
