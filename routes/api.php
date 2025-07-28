<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceProviderController;
use App\Http\Controllers\Api\ServiceTypeController;
use App\Http\Controllers\Api\ServiceProviderProductController;
use App\Http\Controllers\Api\ServiceCategoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes RESTful complètes pour chaque ressource, avec ces endpoints
Route::apiResource('service-providers', ServiceProviderController::class);
Route::apiResource('service-types', ServiceTypeController::class);
Route::apiResource('service-provider-products', ServiceProviderProductController::class);
Route::apiResource('service-categories', ServiceCategoryController::class);
