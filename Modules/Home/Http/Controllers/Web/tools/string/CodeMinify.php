<?php

namespace Modules\Home\Http\Controllers\Web\tools\string;

use Modules\Home\Http\Controllers\HomeBase;
use zxf\min;

// 代码压缩
class CodeMinify extends HomeBase
{
    public function index()
    {
        if ($this->request->isMethod('post')) {
            $codeString = $this->request->input('code');
            if (empty($codeString)) {
                $this->error('请填入需要压缩的代码', 412);
            }
            // 压缩css , 也可以压缩js
            $minifier     = new min\CSS($codeString);
            $minifiedCode = $minifier->minify();

            $oldLen      = mb_strlen($codeString);
            $newLen      = mb_strlen($minifiedCode);
            $minifyRatio = bcmul(bcdiv($newLen, $oldLen, 4), 100, 2) . '%';

            return $this->success(['min_str' => $minifiedCode, 'old_len' => $oldLen, 'new_len' => $newLen, 'minify_ratio' => $minifyRatio], '转换成功', 200);
        }
        return view('home::home.tools.string.code_minify', []);
    }

}
