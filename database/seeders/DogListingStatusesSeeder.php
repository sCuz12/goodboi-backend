<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\DogListingStatusesEnum as DogStatusesEnum;
use Illuminate\Support\Facades\DB as DB;

class DogListingStatusesSeeder extends Seeder
{
    /**
     * Fills all the available dog listings statuses in dog_statuses table
     *
     * @return void
     */
    public function run()
    {

        foreach (DogStatusesEnum::DOG_LISTING_STATUSES as $status) {
            DB::table('dog_statuses')->insert([
                "id" => $status['id'],
                "name" => $status['name'],
            ]);
        }
    }
}
