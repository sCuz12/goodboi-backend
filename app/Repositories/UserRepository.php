<?php

namespace App\Repositories;

use App\Enums\UserType;
use App\Models\User;
use App\Repositories\Interfaces\ShelterRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public static function  getActiveEmailableUsers(): Collection
    {
        $users = User::where('user_type', UserType::USER)
            ->join('user_marketing_settings', 'user_marketing_settings.user_id', '=', 'users.id')
            ->where('user_marketing_settings.allow_emails', 1)
            ->get();

        return $users;
    }
}
