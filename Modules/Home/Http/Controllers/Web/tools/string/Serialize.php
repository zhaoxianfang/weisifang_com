<?php

namespace Modules\Home\Http\Controllers\Web\tools\string;

use Modules\Home\Http\Controllers\HomeBase;

// 系列化和反系列化
class Serialize extends HomeBase
{
    public function index()
    {
        if ($this->request->isMethod('post')) {
            $codeString = $this->request->input('code');
            if (!$codeString) {
                return $this->error('请填入需要系列化的代码', 412);
            }
            try {
                $fn     = 'unserialize';
                $result = unserialize($codeString);
            } catch (\Exception $exception) {
                $fn     = 'serialize';
                $result = serialize($codeString);
            }
            return $this->success(['result' => $result, 'fn' => $fn], '转换成功', 200);
        }
        return view('home::home.tools.string.serialize', []);
    }

}
