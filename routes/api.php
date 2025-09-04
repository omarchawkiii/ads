<?php

use App\Http\Controllers\IngersterController;
use App\Http\Controllers\LightHistoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleContoller;
use App\Http\Controllers\ScreenServerDetailController;
use App\Http\Controllers\ScreenSnmpProjectorDetailController;
use App\Http\Controllers\ScreenSnmpSoundDetailController;
use App\Http\Controllers\UserController;
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


Route::post('/delete-user', [UserController::class, 'deleteUser_api']);
Route::post('/set_schedules', [ScheduleContoller::class, 'set_schedules_api']);
Route::post('/set_light_history', [LightHistoryController::class, 'set_light_history_api']);
Route::post('/set_screen_snmp_sound_detail', [ScreenSnmpSoundDetailController::class, 'set_screen_snmp_sound_detail_api']);
Route::post('/set_screen_snmp_projector_detail', [ScreenSnmpProjectorDetailController::class, 'set_screen_snmp_projector_detail_api']);
Route::post('/set_playback', [LightHistoryController::class, 'set_playback']);
Route::post('/set_screen_server_detail', [ScreenServerDetailController::class, 'set_screen_snmp_projector_detail_api']);
Route::post('/ingest_dcp_from_noc', [IngersterController::class, 'ingest_dcp_from_noc']);
Route::post('/refresh_noc_list_content', [LocationController::class, 'refresh_noc_list_content']);
