<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostDogs extends Model
{
    use HasFactory;

    public function dogs(): BelongsTo
    {
        return $this->belongsTo(Dogs::class);
    }
}
