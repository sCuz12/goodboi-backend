<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoundDogs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dogs(): BelongsTo
    {
        return $this->belongsTo(Dogs::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
