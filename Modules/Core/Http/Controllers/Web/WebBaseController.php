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
     *
     * @throws Exception
     */
    public function error(string $message = '出错啦！', int $code = 500)
    {
        throw new Exception($message, $code);
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
     * 检查是否登录 web,如果已经登录则返回 true
     *
     * @return bool|JsonResponse|Response
     */
    public function checkLogin()
    {
        $this->user = auth('web')->guest() ? null : auth('web')->user();
        if (!$this->user) {
            if (request()->ajax()) {
                return response()->json(['code' => 401, 'message' => '请先登录后再试', 'url' => url('/' . get_module_name() . '/auth/login')], 401);
            } else {
                return $this->showTipsBtnPage($tips = '请先登录后再试！', url('/' . get_module_name() . '/auth/login'));
            }
        }
        return true;
    }
}
