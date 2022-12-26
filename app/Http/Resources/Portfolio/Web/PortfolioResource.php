<?php

namespace App\Http\Resources\Portfolio\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PortfolioResource extends JsonResource
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
            'user' => $this->user,
            'solution' => $this->solution,
            'category' => $this->solution->category,
        ];
    }
}
