<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Solution\Api\V1\SolutionResourceCollection;
use App\Http\Resources\SubCategory\Api\V1\SubCategoryResourceCollection;
use App\Models\Solution;
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

    public function saveSolutionWishlist(Solution $solution)
    {
        try {

            if ($wishlist = $this->wishlistService->saveSolutionWishlist($solution)) {
                return $this->successResponse("Added to portfolio successfully!", $wishlist);
            } else {
                $this->errorResponse("An error occured", null, "Error", 500, 500);
            }
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function deleteSolutionWishlist(Request $request)
    {
        try {
            $data = $this->wishlistService->deleteSolutionWishlist($request->all());
            return $this->successResponse("Removed from portfolio successfully!", $data);
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function getSolutionsWishlist(Request $request)
    {
        $data = new SolutionResourceCollection($this->wishlistService->getSolutionsWishlist($request));
        return $this->successResponse("Wishlist", $data);
    }
}
