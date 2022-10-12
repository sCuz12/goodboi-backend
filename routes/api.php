<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Dogs\FoundDogsController;
use App\Http\Controllers\DogsController;
use App\Http\Controllers\EmailVerification;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ShelterController as ControllersShelterController;
use App\Http\Controllers\Shelters\DogsController as SheltersDogsController;
use App\Http\Controllers\Shelters\ShelterController;
use App\Http\Controllers\SocialAuthFacebookController;
use App\Http\Controllers\Dogs\LostDogsController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserListingsController;
use App\Http\Controllers\User\UserLostDogController;
use App\Http\Controllers\UserFoundDogController;
use App\Http\Controllers\VaccinesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Only FOR Authenticated users
Route::middleware('auth:api')->group(function () {
    //Route::apiResource('users', UserController::class);
    Route::post('user/update_profile', [UserController::class, 'update']);;
    Route::put('user/password', [UserController::class, 'updatePassword']);
    Route::get('/user/favourites', [UserController::class, 'getFavouritesListing']);
    Route::get('current-user', [UserController::class, 'getLoggedinUser']);

    Route::get('current-shelter', [UserController::class, 'getCurrentShelter']);
    Route::get('is-normal-user', [UserController::class, 'isUserType']);
    Route::get('loggedin-user', [UserController::class, 'getLoggedInData']);
    Route::post('logout', [AuthController::class, 'logout']); // Logouts the user deletes its key 
    Route::post('email/verification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::get('countries', [CountryController::class, 'getCountries']);
    Route::get('cities/{countryId}', [CityController::class, 'getCitiesCountry']);
    Route::get('vaccinations', [VaccinesController::class, 'getAllVaccines']);
    Route::get('is-shelter-user', [UserController::class, 'isShelterType']);

    //Favorite
    Route::post('/favourite/{dog_id}', [FavouriteController::class, 'addToFavourites']);
    Route::post('/unfavourite/{dog_id}', [FavouriteController::class, 'removeFromFavourites']);
});

//Only for shelters 
Route::group([
    'middleware' => ['auth:api', 'scope:shelter'],
    'prefix' => 'shelter',
    'namespace' => 'Shelter'
], function () {
    Route::post('createProfile', [ShelterController::class, 'createProfile']);
    Route::post('profile', [ShelterController::class, 'store']);
    Route::get('profile/stats', [ShelterController::class, 'getStats']);
    //*** Managment Dog Listings ****
    Route::post('animals/create', [SheltersDogsController::class, 'store']); // Shelter create dog listing
    Route::post('animals/{dog}/edit', [SheltersDogsController::class, 'update']); //Shelter update specific dog listing
    Route::get('animals/{dog}/edit', [SheltersDogsController::class, 'showEdit']); //get the info of shelter its own listing
    Route::get('current/listings', [ShelterController::class, 'shelterListings']); //get the loggedin user listings
    Route::put('animals/{dog}/delete', [SheltersDogsController::class, 'destroy']); //Deletes a listing
    Route::post('animals/{dog}/markAsAdopted', [SheltersDogsController::class, 'markAsAdopted']);
});


//Only for USERS 
Route::group([
    'middleware' => ['auth:api', 'scope:user'],
    'prefix' => 'user',
    'namespace' => 'User'
], function () {
    Route::post('lost-dogs/create', [UserLostDogController::class, 'createLostDogListing']);
    Route::get('/current/listings', [UserListingsController::class, 'userListings']);
    Route::get('/profile/stats', [UserController::class, 'getStats']);
    Route::post('lost-dogs/delete/{dog_id}', [UserLostDogController::class, 'destroy']);
    Route::get('mylistings/edit/{dog_id}', [UserListingsController::class, 'showEdit']);
    Route::post('lost-dogs/edit/{dog_id}', [UserLostDogController::class, 'update']);
    Route::post('found-dogs/create', [UserFoundDogController::class, 'create']);
    Route::post('found-dogs/edit/{dog_id}', [UserFoundDogController::class, 'update']);
    Route::post('found-dogs/delete/{dog_id}', [UserFoundDogController::class, 'destroy']);
});

//COMMON for all 
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('auth/facebook', [SocialAuthFacebookController::class, 'redirectToProvider']);
Route::post('auth/callback/facebook', [SocialAuthFacebookController::class, 'handleProviderCallback']);
Route::post('animals/dogs', [DogsController::class, 'index']); // Display all the listings paginated
Route::get('animals/dogs', [DogsController::class, 'index']); // Display all the listings paginated
Route::get('get_shelters', [ShelterController::class, 'index']); // Display all the shelters 
Route::post('get_shelters', [ShelterController::class, 'index']); // Display all the shelters (POST)
Route::get('animals/{id}', [DogsController::class, 'showById']); //display single animal listing by id
Route::get('countries', [CountriesController::class, 'index']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [ForgotPasswordController::class, 'passwordReset']);
Route::get('animals/shelter/{id}', [DogsController::class, 'shelterListings']);
Route::get('cities/', [CityController::class, 'getAllCities']);
Route::get('locations/{city_id}', [LocationController::class, 'getLocationsByCity']);
Route::get('shelters/{id}', [ControllersShelterController::class, 'getSingle']);
Route::get('animals/lost-dogs/all', [LostDogsController::class, 'index']);
Route::get('animals/lost-dogs/{id}', [LostDogsController::class, 'getSingle']);
Route::get('animals/found-dogs/all', [FoundDogsController::class, 'index']);
Route::get('animals/found-dogs/{id}', [FoundDogsController::class, 'getSingle']);
