<?php

use Illuminate\Http\Request;
use App\User;

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
})->middleware('api','cors');

/* 登录API */
Route::post('/login', function () {
    return user_ins()->login();
})->middleware('api','cors');

/* 登出API */
Route::post('/logout', function () {
    return user_ins()->logout();
})->middleware('api','cors');

/* 用户查询API */
Route::get('/user-list', function () {
    $user = User::whereUserRole(User::ROLE_USER)->get();
    if($user->toArray())
        return response([ 'status' => '1', 'data' => $user->toArray() ]);
    return response([ 'status' => '0', 'msg' => '暂无数据' ]);
})->middleware('api.admin.login','api','cors');