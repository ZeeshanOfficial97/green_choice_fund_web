<?php

namespace App\Services\Web;

use App\Models\Cart;
use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\StripeChargeAndRefund;
use App\Models\UserInvestment;
use App\Models\UserInvestmentSolution;
use App\Services\BaseService;
use App\Traits\PlaidClient;
use App\Traits\StripeClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestmentService extends BaseService
{
    public function getInvestmentsList($request)
    {
        $columnArray = ['investment_num', 'invested_amount'];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = UserInvestment::query();

        if ($request->q) {
            $query->where('investment_num', 'like', '%' . $request->search . '%');
            // $query->where('email', 'like', '%' . $request->search . '%');
            $query->where('invested_amount', 'like', '%' . $request->search . '%');
            $query->where('stripe_charge_id', 'like', '%' . $request->search . '%');
        }

        if ($request->status != '' && $request->status != null) {
            $query->where('investment_status', $request->status);
        }


        if ($request->sortBy && $request->dir && in_array($request->sortBy, $columnArray) && in_array($request->dir, $sortOptions)) {
            $query = $query->orderBy($request->sortBy,  $request->dir);
        } else {
            $query = $query->orderBy('id',  'desc');
        }

        $pageSize = 10;
        if ($request->length) {
            $pageSize = $request->length;
        }

        $data = $query->paginate($pageSize);
        return $data;
    }

}
