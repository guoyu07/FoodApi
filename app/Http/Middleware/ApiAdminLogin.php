<?php

namespace App\Http\Middleware;

use Closure;
use Cache;

class ApiAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cache::get('access_token')) {
            if (Cache::get('access_token')['accessToken'] != $request->get('accessToken')){
                return  response(['status' => 0,'msg' => '用户访问该资源,assessToken错误或过期']);
            }
            return $next($request);
        }else{
            return  response(['status' => 0,'msg' => '您无权访问该资源，assessToken错误或过期，请登陆！']);
        }
    }
}
