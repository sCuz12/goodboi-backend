<?php

namespace Tests\Feature\Listings\User;

use App\Models\Dogs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Responsible for all action for LOST DOGS
 */
class ActionLostDogListingTest extends TestCase
{

    private $token;
    private $user;

    const LISTING_TITLE           = "Laravel lost dog test";
    const LISTING_TITLE_TO_UPDATE = "Laravel lost dog test UPDATED";
    const DESCRIPTION             = "Laravel lost dog test to ensure everything is fine";
    const DESCRIPTION_TO_UPDATE   = "Laravel Update listing to ensure everything is fine";

    const API_ENDPOINT_DELETE = "api/user/lost-dogs/delete/";
    const API_ENDPOINT_EDIT   = "api/user/lost-dogs/edit/";
    const API_ENDPOINT_CREATE = "api/user/lost-dogs/create";
    /**
     * Creates a dog listing for adopt
     *
     * @return void
     */
    public function test_create_lostdog_listing_success()
    {

        $this->setUpUserToken();

        $path = public_path('images/cover_images/profiles/user_default.png');


        $payload = [

            'name'  => SELF::LISTING_TITLE,
            'description' => SELF::DESCRIPTION,
            'title' => SELF::LISTING_TITLE,
            'cover_photo' => UploadedFile::fake()->image('logo.gif', 60, 30),
            'images' => [UploadedFile::fake()->image('logo.gif', 60, 30)],
            'city_id' => '1',
            'location_id' => '1',
            'size'  => 'm',
            'gender' => 'm',
            'lost_date' => '2022-05-18',
            'reward' => '200',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/json'])
            ->json('post', SELF::API_ENDPOINT_CREATE, $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    public function test_edit_lostdog_listing_success()
    {
        $this->setUpUserToken();

        $listing = Dogs::where('user_id', $this->user->id)
            ->where('title', "=", self::LISTING_TITLE)->first();

        $payload = [
            'user_id' => $this->user->id,
            'name'  => self::LISTING_TITLE_TO_UPDATE,
            'description' => self::DESCRIPTION_TO_UPDATE,
            'city_id' => '2',
            'size'  => 'l',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/json'])
            ->json('post', SELF::API_ENDPOINT_EDIT . $listing->id, $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * Deletes lost dog listing of shelter
     *
     * @return void
     */
    public function test_delete_dog_listing_adopt_success()
    {
        $this->setUpUserToken();

        $listing = Dogs::where('user_id', $this->user->id)
            ->where('name', "=", self::LISTING_TITLE_TO_UPDATE)->first();

        $deleteUrl = SELF::API_ENDPOINT_DELETE . $listing->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/json'])
            ->json('POST', $deleteUrl)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * Set up user object & token 
     *
     * @return void
     */
    private function setUpUserToken()
    {
        $this->user = User::where('user_type', '=', 'user')->first();
        $this->token = $this->user->createToken('user', ['user'])->accessToken;
    }
}
