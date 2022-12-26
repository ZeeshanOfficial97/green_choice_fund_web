<?php

namespace App\Services\Web;

use App\Models\ProductVariant;
use App\Models\Solution;
use App\Models\SubCategory;
use App\Models\Wishlist;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class PortfolioService extends BaseService
{

    public function getPortfoliosList($request)
    {
        $columnArray = ['id'];
        $ascArray = ['desc', 'asc'];

        $query = Wishlist::query()->with('solution');

        // if ($request->q) {
        //     $search = $request->q;
        //     $query->with(['user' => function ($q) use ($search) {
        //         $q->where('name', $search);
        //     }]);
        //     $query->with(['solution' => function ($q) use ($search) {
        //         $q->where('name', $search);
        //     }]);
        //     $query->with(['solution' => function ($q) use ($search) {
        //         $q->whereHas('category', function ($q1) use ($search) {
        //             $q1->where('name', $search);
        //         });
        //     }]);
        // }

        if ($request->solution_id) {
            $query = $query->where('solution_id', $request->solution_id);
        }

        if ($request->user_id) {
            $query = $query->where('user_id', $request->user_id);
        }

        if ($request->category_id) {
            $categoryId = $request->category_id;
            $query->with(['solution' => function ($q) use ($categoryId) {
                $q->whereHas('category', function ($q1) use ($categoryId) {
                    $q1->where('id', $categoryId);
                });
            }]);
            // $query->whereHas('solution', function ($q) use ($categoryId) {
            //     $q->where('category_id', $categoryId);
            // });
        }

        $query = $query->where('status', 1);

        if ($request->column && $request->dir && in_array($request->column, $columnArray) && in_array($request->dir, $ascArray)) {
            $query = $query->orderBy($request->column, $request->dir);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $pageSize = 10;
        if ($request->length) {
            $pageSize = $request->length;
        }
        if ($request->csv) {
            $data = $query->get();
        } else {
            $data = $query->paginate($pageSize);
        }
        return $data;
    }
}
