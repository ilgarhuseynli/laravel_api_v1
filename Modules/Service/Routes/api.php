<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\Api\V1\ServicesController;

Route::group(['prefix' => 'v1','as' => 'api.'], function () {

    Route::group(['middleware' => ['auth:sanctum','auth.gates'] ], function () {
        Route::apiResource('services', ServicesController::class);
    });

});
