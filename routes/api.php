<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});



//Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
//
////Route::group(['middleware' => ['auth:sanctum', 'auth.gates']], function () {
////    Route::get('/auth/user', function (Request $request) { return ['data' => $request->user()];});
////    Route::delete('/logout', [AuthController::class, 'logout']);
////    Route::get('dashboard/data', [DashboardApiController::class, 'index']);
////});
//
//});

