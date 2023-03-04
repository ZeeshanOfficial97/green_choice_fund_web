<?php

namespace App\Http\Resources\Investment\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Models\UserInvestment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class InvestmentDetailResource extends JsonResource
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
                'investment_num' => $this->investment_num,
                'invested_amount' => '$' . floatVal($this->invested_amount),
                'stripe_charge_id' => $this->stripe_charge_id,
                'investment_status' => UserInvestment::ORDER_STATUS[$this->investment_status],
                'created_at' =>  Carbon::parse($this->created_at)->format('m-d-Y'),
                'user' => $this->investmentUser,
                'name' => $this->name,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'contact_no' => $this->contact_no,
                'address' => $this->address,
                'dob' => Carbon::parse($this->dob)->format('m-d-Y'),
                'investment_solutions' => InvestmentSolutionResource::collection($this->userInvestmentSolution),
                'channel' => $this->channel
            ];
    }
}
