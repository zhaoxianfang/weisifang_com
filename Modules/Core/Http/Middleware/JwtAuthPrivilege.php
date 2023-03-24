<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use function auth;

class JwtAuthPrivilege
{
    /**
     * 验证jwt 驱动的api 接口是否登录
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('api')->guest()) {
            $routeName = $request->route()->getName();
            $routeName = str_replace('api.', '', $routeName);
            if (!in_array($routeName, config('white_route.api'))) {
                throw new \Exception('请先登录', 401);
            }
        }
        return $next($request);
    }
}
