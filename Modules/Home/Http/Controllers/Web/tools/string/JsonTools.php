<?php

namespace Modules\Home\Http\Controllers\Web\tools\string;

use Modules\Home\Http\Controllers\HomeBase;
use Exception;

// json 格式化
class JsonTools extends HomeBase
{
    public function index()
    {
        if ($this->request->isMethod('post')) {
            $codeString = $this->request->input('code');
            if (!$codeString) {
                $this->error('请填写json字符串', 412);
            }
            try {
                $parseStr = json_decode($codeString, true);
                return $this->success(['parse_str' => $parseStr], '转换成功', 200);
            } catch (Exception $e) {
                $this->error($e->getMessage(), 500);
            }
        }
        return view('home::home.tools.string.json', []);
    }

}
