<?php

namespace Modules\Users\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Web\WebBaseController;
use Modules\Users\Services\UserAuthServices;

class UserController extends WebBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('users::index');
    }

    public function login(Request $request)
    {
        // 默认模式 手机号+密码 session 登录
        // return $this->json(UserAuthServices::instance()->login($request->mobile, $request->password));
        // 设置 手机号+密码 session 登录
        // return $this->json(UserAuthServices::instance()->auth('web')->byToken(false)->login($request->mobile, $request->password));
        // 设置 手机号+密码 api 登录
         return $this->json(UserAuthServices::instance()->auth('api')->byToken()->use('mobile')->login($request->mobile, $request->password));
        // 设置 邮箱号+密码 api 登录
        // return $this->json(UserAuthServices::instance()->auth('api')->byToken()->use('email')->login($request->email, $request->password));
        // 设置 id 方式进行 api 登录
        // return $this->json(UserAuthServices::instance()->auth('api')->byToken()->use('id')->login($request->id));
    }

    /**
     * 用户注册
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->json(UserAuthServices::instance()->register($request->post()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('users::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function show($id)
    {
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function edit($id)
    {
        return view('users::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
