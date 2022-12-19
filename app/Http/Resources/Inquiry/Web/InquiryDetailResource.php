<?php

namespace App\Http\Resources\Inquiry\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class InquiryDetailResource extends JsonResource
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
            'phone' => $this->country_code . ' ' . $this->contact_no,
            'address' => $this->address,
            'description' => $this->description,
            'company_url' => $this->company_url,
            'date' => Carbon::parse($this->created_at)->format('m-d-Y'),
            'contact_reason' => $this->contactReason,
            'user' => $this->user,
            'status' => $this->status ? 'active' : 'inactive',
        ];
    }
}
