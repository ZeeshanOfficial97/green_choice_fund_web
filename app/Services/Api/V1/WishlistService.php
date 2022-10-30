<?php

namespace App\Services\Api\V1;

use App\Models\ProductVariant;
use App\Models\Solution;
use App\Models\SubCategory;
use App\Models\Wishlist;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class WishlistService extends BaseService
{
    public function saveSolutionWishlist($solution)
    {
        $wishlist = null;
        if ($userId = Auth::user()->id) {
            $wishlist = Wishlist::updateOrCreate(
                ['solution_id' => $solution->id, 'user_id' => $userId],
                [
                    'solution_id' => $solution->id,
                    'user_id' => $userId
                ]
            );
            $wishlist['status'] = true;
        }

        return $wishlist;
    }

    public function deleteSolutionWishlist($data)
    {
        if (isset($data['solution_id'])) {
            if ($wishlist = Wishlist::where(['solution_id' => $data['solution_id'], 'user_id' => Auth::user()->id])->first()) {
                $wishlist->forceDelete();
                return true;
            }
        }
        return false;
    }

    public function getSolutionsWishlist($request, $api = false)
    {
        $userId = Auth::user()->id;
        $columnArray = ['id'];
        $ascArray = ['desc', 'asc'];

        $query = Solution::query();

        $query->whereHas('solutionWishlist', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });


        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category_id) {
            $query = $query->where('category_id', $request->category_id);
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

        $data = $query->paginate($pageSize);
        return $data;
    }
}
