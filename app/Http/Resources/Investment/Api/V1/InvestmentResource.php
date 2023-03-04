<?php

namespace App\Http\Resources\Investment\Api\V1;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Models\UserInvestment;
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
                'id' => $this->id,
                'formatted_investment_num' => '#' . $this->investment_num,
                'name' => $this->name,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'contact_no' => $this->contact_no,
                'address' => $this->address,
                'dob' => $this->dob,
                'formatted_invested_amount' => '$' . floatVal($this->invested_amount),
                'user_id' => $this->user_id,
                'stripe_charge_id' => $this->stripe_charge_id,
                'investment_status' => UserInvestment::ORDER_STATUS[$this->investment_status],
                'back_color' => UserInvestment::ORDER_BG_CLR[$this->investment_status],
                'text_color' => UserInvestment::ORDER_TEXT_CLR[$this->investment_status],
                'created_at' => $this->created_at,
                // 'user' => $this->investmentUser,
                'investment_solutions' => InvestmentSolutionResource::collection($this->userInvestmentSolution),
                'channel' => $this->channel
            ];
    }
}
