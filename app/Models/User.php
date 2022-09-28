<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\OauthAccessToken;
use App\Notifications\ResetPasswordNotification;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarder = ['id'];

    const PROFILE_IMAGE_PATH = "images/cover_images/profiles/";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'cover_photo',
        'provider',
        'provider_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Fetch all the favourites of user
     *
     * @return JSON
     */
    public function favourites()
    {
        return $this->belongsToMany(Dogs::class, 'favourites', 'user_id', 'dog_id')->withTimeStamps();
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }


    public function isNormalUser()
    {
        return $this->user_type === UserType::USER;
    }

    public function isShelter()
    {
        return $this->user_type === UserType::SHELTER;
    }

    public function shelter()
    {
        return $this->hasOne(Shelter::class);
    }

    public function canEditListing(Dogs $DogListing)
    {
        return $this->id === $DogListing->user_id;
    }

    public function AauthAcessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = env('CLIENT_URL') . '/reset-password?token=' . $token . '&email=' . $this->email;
        $this->notify(new ResetPasswordNotification($url));
    }
    /**
     * Retrieves the profile image path
     *
     * @return String
     */
    public function getProfileImagePath()
    {
        return asset(self::PROFILE_IMAGE_PATH . $this->cover_photo);
    }
}
