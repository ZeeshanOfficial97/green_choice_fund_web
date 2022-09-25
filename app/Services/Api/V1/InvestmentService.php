<?php

namespace App\Services\Api\V1;

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
    use PlaidClient, StripeClient;
    public function getUserInvestmentsList($request, $api = false)
    {
        $columnArray = ['investment_num', 'name', 'email', 'invested_amount', 'investment_status'];

        $ascArray = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = UserInvestment::query();

        if ($request->search) {
            $query->where('investment_num', 'like', '%' . $request->search . '%');
        }

        if ($api) {
            $query = $query->where('status',  1);
        } else {
            if (in_array($request->status, [0, 1])) {
                $query->where('status', $request->status);
            }
        }

        if ($request->column && $request->dir && in_array($request->column, $columnArray) && in_array($request->dir, $ascArray)) {
            $query = $query->orderBy($request->column,  $request->dir);
        } else {
            $query = $query->orderBy('id',  'asc');
        }

        $pageSize = 10;
        if ($request->length) {
            $pageSize = $request->length;
        }

        $data = $query->paginate($pageSize);
        return $data;
    }

    public function getUserInvestment($request, $api = false)
    {

        $data = false;
        $query = UserInvestment::query();

        if ($request->has('id')) {
            $query->where('id', $request->get('id'));
        } else if ($request->has('investment_num')) {
            $query->where('id', $request->get('id'));
        }

        if ($api) {
            $data = $query->where('status',  1)->get();
        } else {
            if (in_array($request->status, [0, 1])) {
                $data = $query->where('status', $request->status)->get();
            }
        }

        return $data;
    }

    public function saveUserInvestment($request)
    {

        $stripeCharge = null;
        $user = Auth::user();

        $name = $request->get('name');
        $email = $request->get('email');
        $country_code = $request->get('country_code');
        $contact_no = $request->get('contact_no');
        $dob = $request->get('dob');
        $address = $request->get('address');
        $investment_amount = $request->get('investment_amount');
        $solution_ids = $request->get('solution_ids');


        $investmentData = [
            'investment_num' => time(),
            'name' => $name,
            'email' => $email,
            'country_code' => $country_code,
            'contact_no' => $contact_no,
            'dob'  => $dob,
            'address' => $address,
            'invested_amount' => $investment_amount,
            'user_id' => $user->id
        ];

        $investment = UserInvestment::create($investmentData);

        if (isset($solution_ids)) {
            foreach ($solution_ids as $id) {
                $investmentSolutions[] = array('investment_id' => $investment->id, 'solution_id' => $id, 'user_id' => $user->id);
            }

            if (isset($investmentSolutions)) {
                UserInvestmentSolution::insert($investmentSolutions);
            }
        }

        return $investment;
    }

    public function updateStripeChargeId($investment, $stripe_charge_id)
    {
        $investment->update(['stripe_charge_id' => $stripe_charge_id]);
        return $investment;
    }

    public function investmentInstanceSave($investment)
    {
        $investment->save();
        return $investment;
    }

    public function clearCart()
    {
        Cart::where('user_id',Auth::user()->id)->forceDelete();
        return true;
    }

    public function saveStripeRefund($stripeCharge, $stripeRefund, $user)
    {
        return StripeChargeAndRefund::create([
            'stripe_charge_id' => $stripeCharge->id,
            'stripe_refund_id' => $stripeRefund->id,
            'user_id' => $user->id
        ]);
    }
}
