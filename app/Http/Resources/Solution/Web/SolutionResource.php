<?php

namespace App\Http\Resources\Solution\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class SolutionResource extends JsonResource
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
            'category_name' => $this->category->name,
            'description' => $this->description,
            'published' => $this->published ? 'published' : 'unpublished',
            'status' => $this->status ? 'active' : 'inactive',
        ];
    }
}
