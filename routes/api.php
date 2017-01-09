<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// 封装方法
 function user_ins(){
    return new \App\User;
 }

/* 注册API */
Route::post('/register', function () {
    return user_ins()->register();
})->middleware('api');

/* 登录API */
Route::post('/login', function () {
    return user_ins()->login();
})->middleware('api');

/* 登出API */
Route::post('/logout', function () {
    return user_ins()->logout();
})->middleware('api');
