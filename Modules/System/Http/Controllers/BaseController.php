<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{

    /**
     * 基础请求实例对象。
     */
    protected $request;
    /**
     * 登录用户信息
     */
    protected $user = null;

    /**
     * 创建一个控制器实例。
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user    = auth('web')->check() ? auth('web')->user() : (auth('api')->check() ? auth('api')->user() : null);
    }

    public function json($data = [], $status = 200)
    {
        return response()->json($data, $status)->send();
    }

    public function api_json($data = [], $code = 200, $message = '成功', $status = 200)
    {
        return response()->json(compact('code', 'message', 'data'), $status)->send();
    }
}
