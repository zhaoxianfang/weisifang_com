<?php

namespace Modules\Docs\Http\Controllers\Web;

use Modules\Docs\Http\Controllers\DocsBaseController;
use Modules\System\Http\Requests\WebLoginAuthRequest;

class DocsAuthController extends DocsBaseController
{

    /**
     * 登录页面
     */
    public function login()
    {
        if (!auth('web')->guest()) {
            return to_route('docs.home');
        }
        return view('docs::web/login');
    }

    public function loginHandle(WebLoginAuthRequest $request)
    {
        $credentials = $request->only(['mobile', 'password']);;
        $remember = true; // 是否记住密码
        if (!auth('web')->attempt($credentials, $remember)) {
            return response()->json(['error' => '账号或者密码错误'], 401);
        }

        $jump = url()->previous(); // 成功后跳转到上一个页面地址
        return $this->success([], '登录成功', 200, $jump);
    }

    /**
     * 注册
     */
    public function register()
    {
        return view('docs::web/register');
    }

    public function qqlogin()
    {
        // 判断来源url
        list($local, $referer) = source_local_website();
        $url = url('docs/auth/callback?source_url=' . urlencode(($local && $referer) ? $referer : route('docs.home')));
        return to_route('callback.tencent.login', ['callback_url' => urlencode($url)], 302);
    }

    public function weibologin()
    {
        $url = url('docs/auth/callback');
        return to_route('callback.weibo.login', ['callback_url' => urlencode($url)], 302);
    }

    // 用户退出
    public function logout()
    {
        auth('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        if (request()->ajax()) {
            return $this->json([
                'code'    => 200,
                'message' => '退出成功',
            ]);
        }
        return to_route('docs.home', [], 302);
    }

    // 登录回调
    public function callback()
    {
        $user = collect(request()->post())->except(['sys'])->toArray();

        $remember = false; // 是否记住密码
        if (!auth('web')->loginUsingId($user['id'], $remember)) {
            return response()->json(['error' => '账号或者密码错误'], 401);
        }
        $jump_url = request()->input('source_url', '');
        $to       = $jump_url ? urldecode($jump_url) : route('docs.home');

        return redirect($to);
        // return to_route('docs.home', [], 302);

    }
}
