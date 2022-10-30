<?php

namespace App\Http\Resources\Solution\Api\V1;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class SolutionDetailResource extends JsonResource
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
            'description' => $this->description,
            'category_id' => $this->category_id,
            'added_to_cart' => $this->added_to_cart,
            'solution_media' => new MediaResourceCollection($this->solutionMedia)
        ];
    }
}
