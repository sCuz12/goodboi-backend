<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogListingImages extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'dog_id'
    ];

    public function dogs()
    {
        return $this->belongsTo(Dogs::class, 'dog_id');
    }
}
