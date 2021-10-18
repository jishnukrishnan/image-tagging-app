<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImageTagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user/register', [AuthenticationController::class, 'register'])->middleware("guest:sanctum");
Route::post('/user/tokens', [AuthenticationController::class, 'tokens'])->middleware("guest:sanctum");
Route::get('/user/images', [ImageController::class, 'getUserImages'])->middleware('auth:sanctum');

Route::apiResource('images', ImageController::class);
Route::apiResource('images.tags', ImageTagController::class);

