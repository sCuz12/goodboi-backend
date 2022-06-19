<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableVaccinations extends Model
{
    use HasFactory;

    /**
     * The animal health book that belongs to vaccinations
     */
    public function animalHealthBook()
    {
        return $this->belongsToMany(AnimalHealthBook::class, 'animal_vaccination', 'animal_id', 'vaccination_id');
    }

    public function getName()
    {
        return $this->name;
    }
}
