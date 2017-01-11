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
/*
 |--------------------------------------------------------------------------
 | 无需登录接口
 |--------------------------------------------------------------------------
 */





/*
 |--------------------------------------------------------------------------
 | 需登录后的接口
 |--------------------------------------------------------------------------
 */

// 封装用户对象实例化
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


// 封装类别对象实例化
function sort_ins(){
    return new \App\Sort();
}

/* 类别添加API */
Route::post('/sort-insert', function () {
   return sort_ins()->sortInsert();
})->middleware('api.admin.login','api','cors');

/* 类别列表API */
Route::get('/sort-list', function () {
    return sort_ins()->sortList();
})->middleware('api.admin.login','api','cors');

/* 类别更新API */
Route::post('/sort-update', function () {
    return sort_ins()->sortUpdate();
})->middleware('api.admin.login','api','cors');

/* 类别删除 API */
Route::get('/sort-delete/{sort_id}', function ($sort_id) {
    return sort_ins()->sortDelete($sort_id);
})->middleware('api.admin.login','api','cors');


// 封装菜单对象实例化
function menu_ins(){
    return new \App\Menu();
}

/* 菜单添加API */
Route::post('/menu-insert', function () {
    return menu_ins()->menuInsert();
})->middleware('api.admin.login','api','cors');

/* 菜单列表API */
Route::get('/menu-list', function () {
    return menu_ins()->menuList();
})->middleware('api.admin.login','api','cors');