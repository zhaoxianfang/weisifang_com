<?php

namespace Modules\Docs\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Docs\Entities\DocsApp;
use Modules\Docs\Http\Controllers\DocsBaseController;

class DocsController extends DocsBaseController
{
    /**
     * 文档中心主页
     *
     * @return Renderable
     */
    public function index()
    {
        // 从 Session 获取数据 ...
        $value = session('key');

        // 设置默认值...
        $value = session('key', 'default');

        // 在Session 里存储一段数据 ...
        session(['key' => 'value']);
        dump($value);
        $t = session('key');
        dump($t);
        dump(config('session'));

        dump('docs home');
        dump(auth('web')->guest());
        dd(auth('web')->user());
        return view('docs::index');
    }
}
