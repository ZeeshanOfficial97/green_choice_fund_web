<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ForgotPasswordController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\WishlistController;
use App\Http\Controllers\Api\V1\GeneralController;
use App\Http\Controllers\Api\V1\PlaidController;
use App\Http\Controllers\Api\V1\SolutionController;
use App\Http\Controllers\Api\V1\SubCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('general')->group(function () {
    Route::get('/splash-metadata', [GeneralController::class, 'getSplashMetadata']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);
});


Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'getCategoryList']);
    Route::get('/{category}', [CategoryController::class, 'getCategory']);
});


Route::prefix('sub-category')->group(function () {
    Route::get('/', [SubCategoryController::class, 'getSubCategoryList']);
    Route::get('/{subCategory}', [SubCategoryController::class, 'getSubCategory']);
});


Route::prefix('solution')->group(function () {
    Route::get('/', [SolutionController::class, 'getSolutionList']);
    Route::get('/{solution}', [SolutionController::class, 'getSolution']);
});


Route::group(['middleware' => ['jwt.verify']], function () {

    Route::prefix('auth')->group(function () {
        Route::get('/user/details', [AuthController::class, 'userDetail']);
        Route::post('/user/delete', [AuthController::class, 'userDelete']);
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        Route::post('/user/update', [AuthController::class, 'updateUser']);
        Route::post('/user/notification', [AuthController::class, 'userNotificationEnabled']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('category')->group(function () {
        Route::post('/', [CategoryController::class, 'saveCategory']);
    });

    Route::prefix('sub_category')->group(function () {
        Route::post('/', [SubCategoryController::class, 'saveSubCategory']);
    });


    Route::prefix('solution')->group(function () {
        Route::post('/', [SolutionController::class, 'saveSolution']);
    });

    Route::prefix('wishlist')->group(function () {
        Route::post('/{subCategory}', [WishListController::class, 'saveSubCategoryWishList']);
        Route::post('/{subCategory}/delete', [CartController::class, 'deleteSubCategoryWishList']);
        Route::get('/', [WishlistController::class, 'getSubCategoriesWishlist']);
    });

    Route::prefix('cart')->group(function () {
        Route::post('/', [CartController::class, 'addToCart']);
        Route::post('/delete', [CartController::class, 'deleteFromCart']);
        Route::get('/', [CartController::class, 'getCartList']);
        Route::get('/count', [CartController::class, 'cartItemsCount']);
    });

    Route::prefix('institution_inquiry')->group(function () {
        Route::post('/', [GeneralController::class, 'saveInstitutionInquiry']);
    });

    Route::prefix('general')->group(function () {
        Route::get('/eula', [GeneralController::class, 'getEULA']);
    });

    Route::prefix('plaid')->group(function () {
        Route::post('/create_link_token', [PlaidController::class, 'createLinkToken']);
        Route::post('/set_access_token', [PlaidController::class, 'setAccessToken']);
        Route::post('/account_details', [PlaidController::class, 'getAccountsDetails']);
        Route::post('/transfer_funds', [PlaidController::class, 'transferFunds']);

    });

});
