<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DogListingTest extends TestCase
{
    /**
     * Checks the response all dogs for adoption  (checks status + json structure)
     *
     * @return void
     */
    public function test_get_all_dogs_for_adoption_and_check_structure()
    {
        $response = $this->json('get', 'api/animals/dogs');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'name',
                            'description',
                            'cover_image',
                            'age',
                            'city',
                            'size',
                            'total_views',
                            'gender',
                            'listing_type'
                        ]
                    ]
                ]
            );
    }

    /**
     * Checks the response all LOST dogs (checks status + json structure)
     *
     * @return void
     */
    public function test_get_all_lost_dogs_structure_check()
    {
        $response = $this->json('get', 'api/animals/lost-dogs/all');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'dog_id',
                            'title',
                            'name',
                            'description',
                            'cover_image',
                            'listing_images',
                            'size',
                            'gender',
                            'listing_type',
                            'lost_date',
                            'lost_city',
                            'lost_at',
                            'owner'
                        ]
                    ]
                ]
            );
    }

    /**
     * Checks the response all FOUND dogs (checks status + json structure)
     *
     * @return void
     */
    public function test_get_all_found_dogs_structure_check()
    {
        $response = $this->json('get', 'api/animals/found-dogs/all');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'dog_id',
                            'title',
                            'name',
                            'description',
                            'cover_image',
                            'listing_images',
                            'size',
                            'gender',
                            'listing_type',
                            'found_date',
                            'found_city',
                            'found_at',
                            'founder'
                        ]
                    ]
                ]
            );
    }
}
