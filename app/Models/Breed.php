<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;


    // Each category has many jobs relationship
    public function dogs()
    {
        return $this->hasMany(Dogs::class);
    }
}
