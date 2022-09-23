<?php

namespace App\Http\Resources\Media\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use function asset;

class MediaResource extends JsonResource
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
            'image' => asset('storage/'.$this->image_url),
        ];
    }
}
