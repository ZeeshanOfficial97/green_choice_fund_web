<?php

namespace App\Http\Resources\Media\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return MediaResource::collection($this->collection);
    }
}
