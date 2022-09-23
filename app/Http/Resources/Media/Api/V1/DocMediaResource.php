<?php

namespace App\Http\Resources\Media\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use function asset;

class DocMediaResource extends JsonResource
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
            'doc' => asset('storage/'.$this->doc_url),
        ];
    }
}
