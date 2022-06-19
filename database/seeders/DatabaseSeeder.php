<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            /* Not needed on production */
            // SheltersSeeder::class,
            // UserSeeder::class,
            // DogsSeeder::class,
            /* Needed one time run on production */
            DogBreedSeeder::class,
            // VaccinationSeeder::class,
            // CountriesSeeder::class,
            // CitiesSeeder::class,
            // DogListingStatusesSeeder::class
        ]);
    }
}
