<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalHealthBook extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'animal_health_book';


    protected $fillable = [
        'dog_id',
        'condition'
    ];

    /**
     * Get each animal owns its health book
     */
    public function user()
    {
        return $this->belongsTo(Dogs::class);
    }

    /**
     * The Vaccinations belongs to each health book of a dog.
     */
    public function vaccinations()
    {
        return $this->belongsToMany(AvailableVaccinations::class, 'animal_vaccination', 'animal_id', 'vaccination_id');
    }
}
