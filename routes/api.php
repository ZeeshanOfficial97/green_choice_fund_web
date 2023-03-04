<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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


//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Storage link:
Route::get('/storage', function () {
    $exitCode = Artisan::call('storage:link');
    return '<h1>Storage linked</h1>';
});

//Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Config cached</h1>';
});

//Clear Config cache:
Route::get('/config-clear', function () {
    $exitCode = Artisan::call('config:clear');
    return '<h1>Config cached cleared</h1>';
});

//Clear Cache facade value:
Route::get('/cache-clear', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});


//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

Route::get('/down', function () {
    Artisan::call('down');
    return 'Application is now in maintenance mode.';
});

Route::get('/up', function () {
    Artisan::call('up');
    return 'Application is now live.';
});

Route::prefix('general')->group(function () {
    Route::post('/upload-video-second', [GeneralController::class, 'uploadVideoSecond']);
    Route::post('/upload-video', [GeneralController::class, 'uploadVideo']);
    Route::get('/serve-video/{filename}', [GeneralController::class, 'serveVideo']);
    Route::get('/get-video-url/{filename}', [GeneralController::class, 'getVideoUrl']);
});