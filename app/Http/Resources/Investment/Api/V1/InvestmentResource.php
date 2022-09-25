<?php

namespace App\Http\Resources\Category\Api\V1;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
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
                'investment_num' => $this->investment_num,
                'name' => $this->name,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'contact_no' => $this->contact_no,
                'address' => $this->address,
                'dob' => $this->dob,
                'invested_amount' => $this->invested_amount,
                'user_id' => $this->user_id,
                'stripe_charge_id' => $this->stripe_charge_id,
                'investment_status' => $this->investment_status,
                'user' => $this->investmentUser,
                'investment_solutions' => InvestmentSolutionResource::collection($this->userInvestmentSolution)
            ];
    }
}
