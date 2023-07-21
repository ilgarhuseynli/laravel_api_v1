<?php

use App\Http\Controllers\Api\V1\Admin\MediaController;
use App\Http\Controllers\Api\V1\Admin\MultilistController;
use App\Http\Controllers\Api\V1\Admin\ParametersController;
use App\Http\Controllers\Api\V1\Admin\PermissionsController;
use App\Http\Controllers\Api\V1\Admin\SettingsController;
use App\Http\Controllers\Api\V1\Admin\UsersController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Frontend\ServicesApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


    Route::get('/services/info/{slug}', [ServicesApiController::class, 'info'])->name('service.info');
    Route::get('/services/list', [ServicesApiController::class, 'list'])->name('service.list');


    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/authsettings', [AuthController::class, 'settings']);

        Route::post('media/store', [MediaController::class,'store']);

        Route::get('multilist',  [MultilistController::class,'index']);

//        Route::delete('users/destroy', [UsersController::class,'massDestroy'])->name('users.massDestroy');
        Route::get('users/minlist', [UsersController::class,'minlist'])->name('users.minlist');
        Route::put('users/password/edit', [UsersController::class,'updatePassword']);
        Route::post('users/avatarupload', [UsersController::class,'avatarupload']);
        Route::delete('users/avatardelete', [UsersController::class,'avatardelete']);
        Route::apiResource('users', UsersController::class);


        Route::get('parameters',  [ParametersController::class,'index'])->name('parameters.list');;

        Route::get('permissions',  [PermissionsController::class,'index']);
        Route::put('permissions',  [PermissionsController::class,'update']);

        Route::get('settings',  [SettingsController::class,'index']);
        Route::put('settings',  [SettingsController::class,'update']);
        Route::post('settings/fileupload', [SettingsController::class,'fileupload']);
        Route::delete('settings/filedelete', [SettingsController::class,'filedelete']);
    });

});

