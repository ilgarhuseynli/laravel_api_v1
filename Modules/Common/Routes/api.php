<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\Api\V1\CategoryController;

Route::group(['prefix' => 'v1','as' => 'api.'], function () {

    Route::group(['middleware' => ['auth:sanctum'] ], function () {

//        Route::get('categories/minlist', [CategoryController::class,'minlist']);
        Route::apiResource('categories', CategoryController::class);
    });

});
