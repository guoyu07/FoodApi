<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Request;

class User extends Authenticatable
{
    use Notifiable;

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
        'password', 'remember_token',
    ];

    // 注册API
    public function register(){
        /* 1. 定义接收用户名 */
        $username = Request::get('user_name');
        $password = Request::get('user_password');

        /* 2. 检查用户名和密码是否为空 */
        if (!($username && $password))
            return ['status' => 0, 'msg' => '用户名和密码不可为空'];

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
}
