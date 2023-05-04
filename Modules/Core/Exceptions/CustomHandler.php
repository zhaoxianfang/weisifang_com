<?php

namespace Modules\Core\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Logs\Entities\SystemLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use function config;
use function response;

class CustomHandler extends Handler
{
    private $codeList = [
        0   => '请求出错啦',
        100 => '请求已被接收，请继续发送剩余部分的请求',
        102 => '处理将被继续执行',
        200 => '请求成功',
        201 => '请求已经被实现',
        202 => '暂不处理',
        204 => '处理完成',
        205 => '处理完成',
        206 => '处理完成',
        207 => '之后的消息体将是一个XML消息',
        301 => '被请求的资源已永久移动到新位置',
        302 => '请求的资源现在临时从不同的 URI 响应请求',
        303 => '当前请求的响应已经找到',
        305 => '被请求的资源必须通过指定的代理才能被访问',
        400 => '请求参数有误',
        401 => '当前请求需要验证Authorization',
        403 => '拒绝执行',
        404 => '未找到指定资源',
        405 => '请求方法有误',
        406 => '无法生成响应实体',
        408 => '请求超时',
        410 => '被请求的资源在服务器上已经不再可用',
        411 => '服务器拒绝在没有定义 Content-Length 头的情况下接受请求',
        412 => '数据验证失败',
        413 => 'URI过长',
        415 => '请求格式错误',
        421 => '从当前客户端所在的IP地址到服务器的连接数超过了服务器许可的最大范围',
        422 => '语义错误',
        424 => '之前的某个请求发生的错误，导致当前请求失败',
        426 => '客户端应当切换到TLS/1.0',
        500 => '服务器出错',
        501 => '暂不支持该功能',
        502 => '网关请求出错',
        503 => '服务器临时出错',
        504 => '网关请求出错',
        505 => '服务器不支持，或者拒绝支持在请求中使用的 HTTP 版本',
        506 => '服务器内部配置错误',
        507 => '服务器无法存储完成请求所必须的内容',
        509 => '服务器达到带宽限制',
        510 => '获取资源所需要的策略并没有没满足',
    ];

    /**
     * 渲染异常为 HTTP 响应。
     * 返回 false 来渲染异常的默认 HTTP 响应：
     *
     * @param           $request
     * @param Throwable $exception
     *
     * @return JsonResponse|Response|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            // $msg = 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found';
            if ($request->is('api/*') || !$request->isMethod('get')) {
                return response()->json([
                    'code'    => 404,
                    'message' => '页面不存在',
                ]);
            }
            $errorView = view()->exists("errors::404") ? ('errors.404') : 'errors.404';
            return response()->view($errorView, [
                'message' => '页面不存在',
            ]);
        }
        // 如果模块下定义了自定义的异常接管类 Handler，则交由模块下的异常类自己处理
        $modulesExceptions = 'Modules\\' . ucwords(get_module_name()) . '\Exceptions\Handler';
        if (class_exists($modulesExceptions) && method_exists($modulesExceptions, 'render')) {
            try {
                return call_user_func_array([new $modulesExceptions($this->container), 'render'], [$request, $exception]);
            } catch (\Exception $err) {
                SystemLog::writeErr($err);
            }
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        $defaultCode = 404;// 默认的错误码设置为404 或 500
        $errCode     = $exception->getCode() > 0 ? $exception->getCode() : (method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $defaultCode);

        //如果抛出异常
        $msg = !empty($exception->getMessage()) ? $exception->getMessage() : $this->codeList[$errCode] ?? $this->codeList[$defaultCode];

        //判断路径
        if ($request->is('api/*') || !$request->isMethod('get')) {
            return response()->json([
                'code'    => $errCode,
                'message' => $msg,
            ]);
        } else {
            $errorView = view()->exists("errors::{$errCode}") ? ('errors.' . $errCode) : 'errors.' . $defaultCode;
            return response()->view($errorView, [
                'message' => $msg,
            ]);
            // return response($exception->getMessage() ?: '发生异常啦');
            // return parent::render($request, $exception);

        }
    }

    /**
     * 报告异常。
     * // 判断异常是否需要自定义报告...
     *
     * @return bool|null
     */
    public function report(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return false;
        }

        try {

            SystemLog::writeErr($e);

            // 判断异常是否需要自定义报告...
            if (empty(trim($e->getMessage()))) {
                // 没有报错信息的就直接跳过了
                return true;
            }

            // 在记录完日志后判断： 如果模块下定义了自定义的异常接管类 Handler，则交由模块下的异常类自己处理
            $modulesExceptions = 'Modules\\' . ucwords(get_module_name()) . '\Exceptions\Handler';
            if (class_exists($modulesExceptions) && method_exists($modulesExceptions, 'report')) {
                return call_user_func_array([new $modulesExceptions($this->container), 'report'], [$e]);
            }

        } catch (\Exception $err) {
            // 写入本地文件日志
            Log::error($err->getMessage());
        }

        // 判断异常是否需要自定义报告...
        // return true;
    }
}
