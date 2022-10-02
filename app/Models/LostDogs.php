<?php

namespace App\Models;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostDogs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dogs(): BelongsTo
    {
        return $this->belongsTo(Dogs::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public static function allActiveDogs()
    {
        $lostDogs = Dogs::where('status_id', DogListingStatusesEnum::ACTIVE)
            ->where('listing_type', ListingTypesEnum::LOST)
            ->paginate(12);

        return $lostDogs;
    }
}
