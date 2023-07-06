<?php

use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return view('home');
})->name('home');


//Route::get('/{any}',function(){
//    return view('app');
//})->where('any', '.*');
