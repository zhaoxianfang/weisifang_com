<?php

namespace Modules\Callback\Http\Controllers\Web\Tencent;

use Modules\Callback\Http\Controllers\Web\CallbackController;
use Modules\Users\Services\UserAuthServices;
use zxf\login\QqOauth;

/**
 * QQ 互联登录
 */
class Connect extends CallbackController
{
    /**
     * qq登录
     *
     * 可以在url 中传入 参数 callback_url 用来做通知回调 ； 例如
     * xxx.com/callback/tencent/login?callback_url=http%3A%2F%2Fwww.a.com%2Fa%2Fb%2Fc%3Fd%3D123 callback_url 参数说明
     * 传入前需要做 urlencode($callback_url) 操作 callback_url 回调地址要求允许跨域或者 csrf
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login()
    {
        $jump_url = request()->get('callback_url', '');
        $jumpUrl  = $jump_url ? urldecode($jump_url) : '';

        $qq = new QqOauth(config('tools.qq.web'));

        // $url = $qq->authorization(); // 不传值方式
        $url = $qq->authorization($jumpUrl); // 传入的数据 $jumpUrl 将会在 qq_callback 回调中返回得到

        // 重定向到外部地址
        return redirect()->away($url);
    }

    /**
     * 回调&通知
     *
     * @return string|void
     * @throws \Exception
     */
    public function notify()
    {
        $auth        = new QqOauth(config('tools.qq.web'));
        $userInfo    = $auth->getUserInfo('');
        $callbackUrl = $auth->getStateParam();
        // 记录用户信息
        $loginUserInfo = UserAuthServices::instance()->fastLogin('qq', $userInfo);
        if ($callbackUrl) {
            return buildRequestFormAndSend($callbackUrl, $loginUserInfo);
        } else {
            dd($loginUserInfo);
        }
    }
}
