<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Dogs;

class DogsViewsLog extends Model
{
    private const TABLE_NAME = "dog_listing_views_logs";

    use HasFactory;

    /**
     * insertViewLog
     *
     * @param  Dogs $dog
     * @param String $clientip
     * @return bool
     */
    public static function insertViewLog(Dogs $dog, String $clientIp): bool
    {
        //ensure is ip + valid 
        if (!filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $clientIp = 0;
        }

        return (bool)DB::table(SELF::TABLE_NAME)->insert([
            'dog_id'        => $dog->id,
            'user_id'       => $dog->shelter->user_id,
            'agent'         => \Request::header('User-Agent'),
            'ip'            => $clientIp,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }


    /**
     * check if specific ip user have seen the post before
     *
     * @param  int $dog_id
     * @param  int $ip
     * @return void
     */
    public static function isUserAlreadySeen(int $dog_id, $ip)
    {
        $result = DB::table(self::TABLE_NAME)->where('ip', $ip)
            ->where('dog_id', $dog_id)
            ->exists();

        return $result;
    }
}
