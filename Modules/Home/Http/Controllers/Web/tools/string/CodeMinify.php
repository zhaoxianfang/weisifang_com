<?php

namespace Modules\Home\Http\Controllers\Web\tools\string;

use Modules\Home\Http\Controllers\HomeBase;
use zxf\min\JsMin as CodeMin;
use zxf\min;

// 代码压缩
class CodeMinify extends HomeBase
{
    public function index()
    {
        if ($this->request->isMethod('post')) {
            $codeString = $this->request->input('code');
            if (!$codeString) {
                return $this->error('请填入需要压缩的代码', 412);
            }
            // 压缩css , 也可以压缩js
            $minifier = new min\CSS($codeString);
            // $minifier     = new min\JS($codeString);
            $minifiedCode = $minifier->minify();
            // $minifiedCode = CodeMin::minify($codeString);
            return $this->success(['min_str' => $minifiedCode], '转换成功', 200);
        }
        return view('home::home.tools.string.code_minify', []);
    }

}
