<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourites extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * favouritesCountByUser
     */
    public static function favouritesCountByUser(User $user): int
    {
        $query = Favourites::where('user_id', $user->id);
        return $query->get()->count();
    }
}
