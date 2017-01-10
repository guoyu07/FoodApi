<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Request;
use Cache;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_MANAGE = 30;
    const ROLE_USER = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'user_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_name','user_password', 'remember_token','user_role','user_status','deleted_at','updated_at','user_openid'
    ];


    // 注册API
    public function register(){
       // 调用封装方法
        $has_username_password = $this->has_username_and_password();
        if (!$has_username_password)
            return ['status' => 0, 'msg' => '用户名和密码不可为空'];
        $username = $has_username_password[0];
        $password = $has_username_password[1];

        /* 3.检查用户名是否存在 */
        $user_exists = $this->where('user_name',$username)->exists();

        if($user_exists)
            return ['status' => 0, 'msg' => '用户名已经存在'];

        /* 4.加密密码 */
        $hashed_password = bcrypt($password);

        /* 5.存入数据库 */
        $user = $this;
        $user->user_name = $username;
        $user->user_password = $hashed_password;
        if($user->save())
            return ['status' => 1, 'id' => $user->id,'msg' => '注册成功'];
        else
            return ['status' => 0,'msg' => '注册失败'];

    }

    // 登录API
    public function login(){
        // 调用封装方法

        /* 1.检查用户名和密码是否存在 */
        $has_username_password = $this->has_username_and_password();
        if (!$has_username_password)
            return ['status' => 0, 'msg' => '用户名和密码不可为空'];
        $username = $has_username_password[0];
        $password = $has_username_password[1];

        /* 2.检查用户名在数据库是否存在 */
        $user = $this->where('user_name',$username)->first();
        if(!$user)
            return ['status' => 0, 'msg' => '用户不存在'];

        /* 3.检查密码是否正确 */
        $hashed_password = $user->user_password;
        if(!\Hash::check($password,$hashed_password))
            return ['status' => 0, 'msg' => '密码有误'];

        /* 4.将用户信息写了session 或者写入缓存 */
        session()->put('username',$user->user_name);
        session()->put('user_id',$user->user_id);

        $accessToken = [ 'accessToken' => str_random(60),'username' => $user->user_name,'user_id' => $user->user_id ];
        Cache::add('access_token',$accessToken,60);

        return ['status' => 1, 'id' => $user->user_id, 'accessToken' => Cache::get('access_token')['accessToken'] ,'msg' => '登录成功!'];
    }

    /* 检查用户是否登录 */
    public function is_logged_in(){
        /* 判断SESSION中用户ID是否存在，user_id返回用户ID，否则返回false */
        return session('user_id') ? : false;
    }

    // 登出API
    public function logout(){
        /* 删除username */
        session()->forget('username');
        /* 删除user_id */
        session()->forget('user_id');

        /* 删除缓存 */
        Cache::pull('access_token');

        return ['status' => 1,'msg' => '退出成功!'];
    }

    //封装方法
    public function has_username_and_password(){
        /* 1. 定义接收用户名 */
        $username = Request::get('user_name');
        $password = Request::get('user_password');

        /* 2. 检查用户名和密码是否为空 */
        if($username && $password)
            return [$username,$password];
        return false;
    }
}
