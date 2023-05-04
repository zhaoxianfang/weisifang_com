<?php

namespace Modules\Docs\Exceptions;

use App\Exceptions\Handler as AppHandler;
use Throwable;
use function response;

class Handler extends AppHandler
{
    public function render($request, Throwable $exception)
    {
        $info    = strtolower(trim($exception->getMessage()));
        $isGuest = auth('web')->guest(); // 游客

        if (stripos($info, 'no query results for model') !== false) {
            // 没有此模型数据，可能是文档不存在或者没有权限
            $info = '此文档不存在或已经被删除了！';
        }
        if ($request->ajax()) {
            return response()->json([
                'code'    => $isGuest ? 401 : 500,
                'message' => $isGuest ? '请先登录后再试试!' : $info,
            ]);
        }
        $infoDesc = '1、该内容不存在或已经删除;<br/>2、您需要登录后才能查看;&nbsp;&nbsp;&nbsp;&nbsp;<br/>3、您没有此内容的相关权限';
        $url      = $isGuest ? url('/docs/auth/login') : '';
        $btnText  = $isGuest ? '立即登录' : '';
        return response()->view('docs::tips/info', [
            'info'        => $info,
            'desc'        => $infoDesc,
            'url'         => $url,
            'btn_text'    => $btnText,
            'module_name' => 'docs',
        ], 200)->header('Content-Type', 'text/html');
    }

    public function report(Throwable $e)
    {

    }
}
