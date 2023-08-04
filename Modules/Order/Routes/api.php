<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\V1\OrderController;

Route::group(['prefix' => 'v1','as' => 'api.'], function () {
    Route::group(['middleware' => ['auth:sanctum'] ], function () {
        Route::apiResource('orders', OrderController::class);
    });
});
