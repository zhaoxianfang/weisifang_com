<?php

namespace Modules\System\Http\Controllers\Web;

use Illuminate\Support\Facades\View;
use Modules\System\Http\Controllers\BaseController;

class WebBaseController extends BaseController
{

    /**
     * 手动抛出异常
     *
     * @param string $message
     * @param int    $code
     *
     * @throws \Exception
     */
    public function error(string $message = '出错啦！', int $code = 500)
    {
        throw new \Exception($message, $code);
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return bool|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
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

    public function showTipsBtnPage($tips = '出错啦！', $url = 'javascript:;')
    {
        return response()->view(get_module_name() . '::other/info', ['info' => $tips, 'type' => 'warning', 'url' => $url], 200)->header('Content-Type', 'text/html');
    }

    /**
     * 中转 提示页面
     *
     * @param string $buttonUrl     点击按钮跳转地址
     * @param string $buttonText    按钮提示信息
     * @param array  $form_elements 提示页面表单input数据 格式 ['name'=>'姓名','键值'=>'中文提示']
     * @param string $message       表单上方的提示文字
     * @param string $buttonStyle   primary|info|default|success|warning|danger
     */
    public function transferPage(string $buttonUrl = '/', $buttonText = '回首页', $form_elements = [], $message = '', $buttonStyle = 'primary')
    {
        return die(view('system::other/transfer', [
            'button_text'   => $buttonText,
            'type'          => $buttonStyle,
            'message'       => $message,
            'button_url'    => $buttonUrl,
            'form_elements' => $form_elements,
        ]));//->header('Content-Type', 'text/html'));
    }


    /**
     * 通知结果页面
     *
     * @param string $message 提示文字
     * @param string $type    类型 success|error|info|warning
     */
    public function tipsPage(string $message = '操作成功', string $type = 'success')
    {
        return die(view('system::other/tips', [
            'type'    => $type ?? 'success',
            'message' => $message,
        ]));
    }
}
