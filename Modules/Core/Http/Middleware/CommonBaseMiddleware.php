<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Modules\Logs\Entities\SystemLog;
use function config;

class CommonBaseMiddleware
{
    /**
     * 基础数据展示
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        if ($request->isMethod('get')) {
//            $sys = config('app');
//            // $sys[''] = '';
//            $request->offsetSet('sys', $sys);
//            View::share('sys', $sys);
//        }

        $response = $next($request);

        try {
            $userId = (int)get_user_info('id');
            SystemLog::writeLog('操作请求', [], $userId, [], SystemLog::LEVEL_LOWEST);
        } catch (\Exception $err) {
        }
        return $response;

    }
}
