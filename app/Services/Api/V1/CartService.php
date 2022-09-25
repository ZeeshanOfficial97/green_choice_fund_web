<?php

namespace App\Services\Api\V1;

use App\Http\Resources\Solution\Api\V1\SolutionDetailResource;
use App\Http\Resources\Solution\Api\V1\SolutionResource;
use App\Http\Resources\Solution\Api\V1\SolutionResourceCollection;
use App\Models\Cart;
use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\Solution;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService extends BaseService
{
    public function getCartList($request)
    {
        $solutionIds = Cart::where('user_id', '=', Auth::user()->id)->pluck('solution_id');
        $data = Solution::whereIn('id', $solutionIds)->get();
        $result['count'] = count($data);
        $result['list'] = SolutionDetailResource::collection($data);
        return $result;
    }

    public function addToCart($data)
    {
        $item = Cart::updateOrCreate(['solution_id' => $data['solution_id'], 'user_id' => Auth::user()->id], ['solution_id' => $data['solution_id'], 'user_id' => Auth::user()->id]);
        $count = Cart::where(['solution_id' => $data['solution_id'], 'user_id' => Auth::user()->id])->count();
        $result = ['item' => $item, 'count' => $count, ];
        return $result;
    }

    public function deleteFromCart($data)
    {
        $result = ['deleted' => false, 'count' => 0 ];
        if ($item = Cart::where(['solution_id' => $data['solution_id'], 'user_id' => Auth::user()->id])->first()) {
            $item->delete();
            $result["deleted"] = true;
        }
        $count = Cart::where(['solution_id' => $data['solution_id'], 'user_id' => Auth::user()->id])->count();
        $result["count"] = $count;
        return $result;
    }

    public function cartItemsCount($data)
    {
        $count = Cart::where(['user_id' => Auth::user()->id])->count();
        $result = ['count' => $count, ];
        return $result;
    }

    public function clearCartForUser() {
        Cart::where(['user_id' => Auth::user()->id])->delete();
        return true;
    }
}
