<?php

namespace Modules\Callback\Http\Controllers\Web\Test;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Callback\Http\Controllers\Web\CallbackController;
use Modules\System\Entities\Test as TestModel;

use Overtrue\LaravelWeChat\EasyWeChat;

class Test extends CallbackController
{

    public function __construct()
    {
    }

    /**
     * 测试回调
     */
    public function index()
    {
        if (empty($_POST)) {
            $content = file_get_contents('php://input');
            $post    = (array)json_decode($content, true);
        } else {
            $post = $_POST;
        }
        $data = array_merge(request()->all(), $post);
        $test = new TestModel([
            'title'   => 'test callback',
            'content' => json_encode($data),
        ]);
        $test->save();

        dd('test end!');

    }
}
