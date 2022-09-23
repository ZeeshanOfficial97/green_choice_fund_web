<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\SubCategory\Api\V1\SubCategoryResourceCollection;
use App\Models\SubCategory;
use App\Services\Api\V1\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends ApiController
{
    /**
     * @var wishlistService
     */
    private $wishlistService;

    /**
     * @param wishlistService $wishlistService
     */
    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function saveSubCategoryWishList(SubCategory $subCategory)
    {
        try {

            if ($wishlist = $this->wishlistService->saveSubCategoryWishList($subCategory)) {
                return $this->successResponse("Added to wishlist successfully", $wishlist);
            } else {
                $this->errorResponse("An issue occured", null, "Error", 500, 500);
            }
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function deleteSubCategoryWishList(SubCategory $subCategory)
    {
        try {

            if ($wishlist = $this->wishlistService->saveSubCategoryWishList($subCategory)) {
                return $this->successResponse("Added to wishlist successfully", $wishlist);
            } else {
                $this->errorResponse("An issue occured", null, "Error", 500, 500);
            }
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function getSubCategoriesWishlist(Request $request)
    {
        $data = new SubCategoryResourceCollection($this->wishlistService->getSubCategoriesWishlist($request));
        return $this->successResponse("Wishlist", $data);
    }

}
