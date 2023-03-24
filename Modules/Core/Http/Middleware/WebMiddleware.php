<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use function auth;
use function config;
use function get_module_name;
use function redirect;

class WebMiddleware
{
    /**
     * 通用 web 模块 session 中间件
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 模块名[小写]
        $module_name = get_module_name();
        // 检测是否在模块下的白名单 uri,未定义白名单模块 不走 下面的验证逻辑
        $module_white_route_uri = config('white_route.' . $module_name);
        if (!empty($module_white_route_uri) && auth('web')->guest()) {
            $inWhite = false; // uri是否在白名单内
            if (in_array('*', $module_white_route_uri)) {
                $inWhite = true;
            } else {
                foreach ($module_white_route_uri as $uri) {
                    $home_module = config('white_route.home_module', 'home');
                    $module_name = $module_name == $home_module ? '' : $module_name;
                    $uri         = trim($module_name . '/' . trim($uri, '/'), '/');
                    if ($request->fullUrlIs($uri) || $request->is($uri)) {
                        $inWhite = true;
                        break;
                    }
                }
            }

            if (!$inWhite) {
                return redirect('/' . $module_name . '/auth/login');
            }
        }
        View::share('module_name', $module_name);

        return $next($request);
    }
}
