<?php

namespace App\Http\Resources\AppUser\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AppUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'contact_no' => $this->contact_no,
            'profile_photo_url' => $this->profile_photo_url,
            'user_type' => $this->user_type_id ? User::USER_TYPE[$this->user_type_id] : null,
            'stripe_user_id' => $this->stripe_user_id,
            'status' => $this->status ? 'active': 'inactive',
        ];
    }
}
