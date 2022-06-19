<?php

namespace Database\Seeders;

use App\Models\Dogs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dogs::factory()->count(20)->create();
    }
}
