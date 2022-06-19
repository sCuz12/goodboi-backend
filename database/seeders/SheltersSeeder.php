<?php

namespace Database\Seeders;

use App\Models\Shelter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SheltersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shelter::factory()->count(20)->create();
    }
}
