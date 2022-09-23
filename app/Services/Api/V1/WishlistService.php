<?php

namespace App\Services\Api\V1;

use App\Models\ProductVariant;
use App\Models\SubCategory;
use App\Models\Wishlist;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class WishlistService extends BaseService
{
    public function saveSubCategoryWishList($subCategory)
    {
        $wishlist = null;
        if ($userId = Auth::user()->id) {
            $wishlist = Wishlist::updateOrCreate(
                ['sub_category_id' => $subCategory->id, 'user_id' => $userId],
                [
                    'sub_category_id' => $subCategory->id,
                    'user_id' => $userId
                ]
            );
        }

        return $wishlist;
    }

    public function getSubCategoriesWishlist($request, $api = false)
    {
        $userId = Auth::user()->id;
        $columnArray = ['id'];
        $ascArray = ['desc', 'asc'];

        $query = SubCategory::query();

        if ($api) {
            $query->whereHas('subCategoryWishlist', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

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
