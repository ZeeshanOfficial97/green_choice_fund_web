<?php

namespace App\Http\Resources\Infographic\Web;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Route;

class InfographicResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data['list'] = InfographicResource::collection($this->collection);
        if ($this->total() != null) {
            $data['pagination'] = [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ];
        }

        return $data;
    }
}