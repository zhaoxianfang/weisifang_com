<?php

namespace Modules\Test\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Controllers\Web\WebBaseController;

class TestController extends WebBaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // 写入默认日志通道
        // \Illuminate\Support\Facades\Log::error('===== test log =====');
        // 测试预览
        return view('test::index');
    }

    public function lang()
    {
        return view('test::lang');
    }

    public function table()
    {
        return view('test::table');
    }

    public function getTableList()
    {
        $list = [
            [
                'id'=>1,
                'title'=>'title',
                'classify_name'=>'classify_name',
                'author_nickname'=>'author_nickname',
                'create_time'=>'2023-01-01 00:00:00',
                'status'=>'status',
            ]
        ];
        return $this->json(['rows' => $list, 'total' => 1]);
    }
}
