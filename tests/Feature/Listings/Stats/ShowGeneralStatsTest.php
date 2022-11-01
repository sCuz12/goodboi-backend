<?php

namespace Tests\Feature\Feature\Listings;

namespace Tests\Feature\Listings\Stats;

use Tests\TestCase;

class ShowGeneralStatsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_display_general_listing_stats()
    {
        $response = $this->get('/api/get_stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'count'
                    ],
                    [
                        'name',
                        'count'
                    ], [
                        'name',
                        'count'
                    ], [
                        'name',
                        'count'
                    ],
                    [
                        'name',
                        'count'
                    ]
                ]
            ]);
    }
}
