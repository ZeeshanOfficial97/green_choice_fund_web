<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Solution\Api\V1\SolutionResource;
use App\Http\Resources\Solution\Api\V1\SolutionResourceCollection;
use App\Http\Resources\SubCategory\Api\V1\SubCategoryResourceCollection;
use App\Services\Api\V1\CartService;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    /**
     * @var cartService
     */
    private $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCartList(Request $request)
    {
        try {
            $data = $this->cartService->getCartList($request);
            return $this->successResponse("Cart details", $data, 'Cart Details');
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $data = $this->cartService->addToCart($request->all());
            return $this->successResponse("Added to cart successfully!", $data);
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function deleteFromCart(Request $request)
    {
        try {
            $data = $this->cartService->deleteFromCart($request->all());
            return $this->successResponse("Deleted from cart successfully!", $data);
        } catch (\Throwable $th) {
            $this->exceptionResponse($th);
        }
    }

    public function cartItemsCount(Request $request)
    {
        $data = $this->cartService->cartItemsCount($request->all());
        return $this->successResponse("Cart items count", $data, "Items count");
    }
}
