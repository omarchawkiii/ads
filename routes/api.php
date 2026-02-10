<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\IngersterController;
use App\Http\Controllers\LightHistoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleContoller;
use App\Http\Controllers\ScreenServerDetailController;
use App\Http\Controllers\ScreenSnmpProjectorDetailController;
use App\Http\Controllers\ScreenSnmpSoundDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
    Route::post('/refresh_locations', [App\Http\Controllers\Api\LocationCotroller::class, 'refresh_locations']);
    Route::get('/get_campaign', [App\Http\Controllers\Api\CompaignController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

