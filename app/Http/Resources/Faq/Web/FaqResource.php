<?php

namespace App\Http\Resources\Faq\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'question' => $this->question,
            'answer' => $this->answer,
            'status' => $this->status ? 'active' : 'inactive',
        ];
    }
}
