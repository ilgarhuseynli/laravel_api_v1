<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\V1\ProductController;

Route::group(['prefix' => 'v1','as' => 'api.'], function () {

    Route::group(['middleware' => ['auth:sanctum'] ], function () {

//        Route::get('products/minlist', [ProductsController::class,'minlist']);
        Route::post('products/fileupload', [ProductController::class,'fileupload']);
        Route::delete('products/filedelete', [ProductController::class,'filedelete']);
        Route::apiResource('products', ProductController::class);
    });

});
