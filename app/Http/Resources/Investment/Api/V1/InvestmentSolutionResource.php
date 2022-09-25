<?php

namespace App\Http\Resources\Category\Api\V1;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Http\Resources\Solution\Api\V1\SolutionResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentSolutionResource extends JsonResource
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
        return
        [
            'solutions' => new SolutionResourceCollection($this->investmentSolution)
        ];
    }
}
