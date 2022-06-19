<?php

namespace Database\Seeders;

use App\Enums\DogBreeds;
use DB;
use Illuminate\Database\Seeder;

class DogBreedSeeder extends Seeder
{


    const DOG_BREEDS = DogBreeds::DOG_BREEDS;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::DOG_BREEDS as $breed) {
            DB::table('breeds')->insert([
                'name' => $breed['name'],
            ]);
        }
    }
}
