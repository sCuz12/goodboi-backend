<?php

namespace Tests\Feature;

use App\Models\Shelter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShelterListingTest extends TestCase
{
    /**
     * checks the retrivak of all shelters
     *
     * @return void
     */
    public function test_get_all_shelters()
    {
        $response = $this->get('/api/get_shelters');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'shelter_name',
                            'address',
                            'phone',
                            'description',
                            'city',
                            'cover_image',
                            'email',

                        ]
                    ]
                ]
            );
        $response->assertStatus(200);
    }

    /**
     * test the retrival of single shelter
     *
     * @return void
     */
    public function test_get_single_shelter()
    {
        $shelter = Shelter::inRandomOrder()->first();

        $response = $this->get('/api/shelters/' . $shelter->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [

                        'id',
                        'shelter_name',
                        'address',
                        'phone',
                        'description',
                        'city',
                        'cover_image',
                        'email',
                        "instagram",
                        "facebook",
                        "city_id",
                    ]
                ]
            );
        $response->assertStatus(200);
    }
}
