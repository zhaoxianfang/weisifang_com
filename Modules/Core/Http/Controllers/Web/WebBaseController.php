<?php

namespace Modules\Core\Http\Controllers\Web;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\BaseController;

class WebBaseController extends BaseController
{

    /**
     * 手动抛出异常
     *
     * @param string $message
     * @param int    $code
     * @param string $url
     * @param int    $wait
     */
    public function error(string $message = '出错啦！', int $code = 500, string $url = '', int $wait = 3)
    {
        // throw new Exception($message, $code);
        // return response()->json(compact('code', 'message', 'url', 'wait'), 200)->send();
        // return die(response()->json(compact('code', 'message', 'url', 'wait'), 200)->send());
        return response()->json(compact('code', 'message', 'url', 'wait'), 200);
    }

    /**
     * json 返回数据
     *
     * @param array  $data
     * @param string $message
     * @param int    $code
     * @param string $url
     * @param int    $wait
     *
     * @return JsonResponse
     */
    public function success(array $data = [], string $message = '成功', int $code = 200, string $url = '', int $wait = 3)
    {
        return response()->json(compact('code', 'message', 'url', 'wait', 'data'), $code);
        // return response()->json(compact('code', 'message', 'url', 'wait', 'data'), $code)->send();
    }

    /**
     * 多次把数据传递到视图中
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function view($key, $value)
    {
        View::share($key, $value);
    }

    /**
     * 检查用户登录状态，游客需要提示先登录
     *
     * @return bool|JsonResponse|Response
     */
    public function guestToAuth()
    {
        if (empty($this->user)) {
            $module   = underline_convert(get_module_name());// 使用小写下划线模块名称
            $authPath = url('/' . $module . '/auth/login');
            if (request()->ajax()) {
                return $this->error('请先登录后再进行此操作！', 401, $authPath);
            } else {
                $viewPath = $module . '::tips/info';
                if (!View::exists($viewPath)) {
                    return response()->view("errors::401", [
                        'message' => '请先登录后再进行此操作！',
                    ])->send();
                }
                return response()->view($viewPath, [
                    'info'        => '请先登录后再进行此操作！',
                    'desc'        => '提示：该操作需要登录后才能进行,请按照提示先进行登录',
                    'url'         => $authPath,
                    'btn_text'    => '立即登录',
                    'module_name' => $module,
                ], 200)->header('Content-Type', 'text/html')->send();
            }
        }
        return false;
    }
}
