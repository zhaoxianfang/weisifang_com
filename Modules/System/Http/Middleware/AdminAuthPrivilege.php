<?php

namespace Modules\System\Http\Middleware;

use Closure;

class AdminAuthPrivilege
{
    /**
     * 验证sessoin 驱动的admin 接口
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('admin')->guest()) {
            $routeName = $request->route()->getName();
            $routeName = str_replace('admin.', '', $routeName);
            if (!in_array($routeName, config('white_route.admin'))) {
                return redirect()->route('admin.auth.login');
            }
        }

        return $next($request);
    }

    /**
     * 在响应发送到浏览器后处理任务。
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Http\Response $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        // ...
    }

}
