<?php

namespace Modules\Callback\Http\Controllers\Web\Weibo;

use Modules\Callback\Http\Controllers\Web\CallbackController;
use Modules\Users\Services\UserServices;
use zxf\login\WeiboOauth;

/**
 * 新浪微博登录
 */
class Sina extends CallbackController
{
    /**
     * qq登录
     *
     * 可以在url 中传入 参数 callback_url 用来做通知回调 ； 例如
     * xxx.com/callback/weibo/login?callback_url=http%3A%2F%2Fwww.a.com%2Fa%2Fb%2Fc%3Fd%3D123 callback_url 参数说明 传入前需要做
     * urlencode($callback_url) 操作 callback_url 回调地址要求允许跨域或者 csrf
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login()
    {
        $jump_url = request()->get('callback_url', '');
        $jumpUrl  = $jump_url ? urldecode($jump_url) : '';

        $weibo = new WeiboOauth(config('oauth.sina'));
        // $url = $qq->authorization(); // 不传值方式
        $url = $weibo->authorization($jumpUrl); // 传入的数据 $jumpUrl 将会在 qq_callback 回调中返回得到
        // 重定向到外部地址
        return redirect()->away($url);

    }

    public function notify()
    {
        $auth        = new WeiboOauth(config('oauth.sina'));
        $userInfo    = $auth->getUserInfo('');
        $callbackUrl = $auth->getStateParam();

        // 记录用户信息
        $loginUserInfo = UserServices::instance()->fastLogin('sina', $userInfo);

        if ($callbackUrl) {
            return buildRequestFormAndSend($callbackUrl, $loginUserInfo);
        } else {
            dump($userInfo);
        }
    }
}
