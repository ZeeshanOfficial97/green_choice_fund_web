<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Web\AppUserController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\EulaController;
use App\Http\Controllers\Web\FaqController;
use App\Http\Controllers\Web\InfographicController;
use App\Http\Controllers\Web\InquiryController;
use App\Http\Controllers\Web\InvestmentController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\PortfolioController;
use App\Http\Controllers\Web\SolutionController;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::prefix('faqs')->group(function () {
    Route::get('/user', [FaqController::class, 'getFaqsUserList']);
});
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::prefix('app-users')->group(function () {
        Route::get('/', [AppUserController::class, 'getAppUsersList']);
        Route::get('/{user}', [AppUserController::class, 'userDetail']);
    });

    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'saveCategory']);
        Route::post('/update', [CategoryController::class, 'updateCategory']);
        Route::post('/delete', [CategoryController::class, 'deleteCategory']);
        Route::get('/', [CategoryController::class, 'getCategoriesList']);
        Route::get('/ddl', [CategoryController::class, 'getCategoriesDDLList']);
        Route::get('/{category}', [CategoryController::class, 'getCategory']);
    });

    Route::prefix('solutions')->group(function () {
        Route::post('/', [SolutionController::class, 'saveSolution']);
        Route::post('/update', [SolutionController::class, 'updateSolution']);
        Route::post('/delete', [SolutionController::class, 'deleteSolution']);
        Route::get('/', [SolutionController::class, 'getSolutionsList']);
        Route::get('/{solution}', [SolutionController::class, 'getSolution']);
    });

    Route::prefix('inquiries')->group(function () {
        Route::get('/', [InquiryController::class, 'getInquiriesList']);
        Route::get('/reasons', [InquiryController::class, 'getInquiryReasonsList']);
        Route::get('/{institutionInquiry}', [InquiryController::class, 'getInquiry']);
    });

    Route::prefix('investments')->group(function () {
        Route::post('/status/update', [InvestmentController::class, 'updateInvestmentStatus']);
        Route::get('/', [InvestmentController::class, 'getInvestmentsList']);
        Route::get('/{userInvestment}', [InvestmentController::class, 'getInvestment']);
    });

    Route::prefix('notifications')->group(function () {
        Route::post('/broadcast', [NotificationController::class, 'broadcastNotification']);
    });

    Route::prefix('infographics')->group(function () {
        Route::post('/', [InfographicController::class, 'saveInfographic']);
        Route::get('/', [InfographicController::class, 'getInfographicsList']);
    });

    Route::prefix('eulas')->group(function () {
        Route::post('/', [EulaController::class, 'saveEula']);
        Route::get('/', [EulaController::class, 'getEulasList']);
    });

    Route::prefix('portfolios')->group(function () {
        Route::get('/filters', [PortfolioController::class, 'getPortfolioFilters']);
        Route::get('/', [PortfolioController::class, 'getPortfoliosList']);
    });

    Route::prefix('faqs')->group(function () {
        Route::post('/', [FaqController::class, 'saveFaq']);
        Route::post('/update', [FaqController::class, 'updateFaq']);
        Route::post('/delete', [FaqController::class, 'deleteFaq']);
        Route::get('/', [FaqController::class, 'getFaqsList']);
        Route::get('/{faq}', [FaqController::class, 'getFaq']);
    });
});
Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
