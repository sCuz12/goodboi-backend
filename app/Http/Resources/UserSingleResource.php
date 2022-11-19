<?php

namespace App\Http\Resources;

use App\Enums\UserType;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSingleResource extends JsonResource
{
    private $cdn;

    public function __construct($resource, $cdn = false)
    {
        // Ensure we call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->cdn      = $cdn; // $apple param passed
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $data =
            [
                'id'           => $this->id,
                'first_name'   => $this->first_name,
                'last_name'    => $this->last_name,
                'email'        => $this->email,
                'cover_photo'  => $this->cdn ? $this->cover_photo : $this->getProfileImagePath(),
                'user_type'    => $this->user_type,
                'phone'        => $this->userProfile->phone ?? "",
                'allow_emails' => $this->marketingSettings->allow_emails ?? 0,
            ];
        if ($this->user_type === UserType::SHELTER) {
            $data['shelter'] = $this->shelter;
        }

        return $data;
    }
}
