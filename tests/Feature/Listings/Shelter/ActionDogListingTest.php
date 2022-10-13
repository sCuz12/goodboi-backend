<?php

namespace Tests\Feature;

use App\Models\Dogs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;

class ActionDogListing extends TestCase
{
    /**
     * Creates a dog listing for adopt
     *
     * @return void
     */
    public function test_create_dog_listing_adopt_success()
    {

        $user = User::where('user_type', '=', 'shelter')->first();
        $token = $user->createToken('shelter', ['shelter'])->accessToken;

        $path = public_path('images/cover_images/profiles/user_default.png');

        $payload = [
            'user_id' => $user->id,
            'shelter_id'  => $user->shelter->id,
            'name'  => "Laravel test",
            'description' => "Laravel test listing to ensure everything is fine",
            'title' => "laravel test",
            'cover_photo' => UploadedFile::fake()->image('logo.gif', 60, 30),
            'images' => [UploadedFile::fake()->image('logo.gif', 60, 30)],
            'dob' => "2022-05-18",
            'city_id' => '1',
            'vaccination[0]' => '1',
            'size'  => 'm',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
            ->json('post', 'api/shelter/animals/create', $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    public function test_edit_dog_listing_adopt_success()
    {
        $user = User::where('user_type', '=', 'shelter')->first();
        $token = $user->createToken('shelter', ['shelter'])->accessToken;

        $listing = Dogs::where('shelter_id', $user->shelter->id)
            ->where('title', "=", "laravel test")->first();

        $payload = [
            'user_id' => $user->id,
            'shelter_id'  => $user->shelter->id,
            'name'  => "Laravel test Updated",
            'description' => "Laravel Update listing to ensure everything is fine",
            'city_id' => '2',
            'size'  => 'l',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
            ->json('post', 'api/shelter/animals/' . $listing->id . '/edit', $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * Deletes dog adopt listing of shelter  (gets the first shelter )
     *
     * @return void
     */
    public function test_delete_dog_listing_adopt_success()
    {
        $user = User::where('user_type', '=', 'shelter')->first();
        $token = $user->createToken('shelter', ['shelter'])->accessToken;

        $listing = Dogs::where('shelter_id', $user->shelter->id)
            ->where('title', "=", "laravel test")->first();

        $deleteUrl = 'api/shelter/animals/' . $listing->id . '/delete';

        $this->withHeaders(['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
            ->put($deleteUrl)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }
}
