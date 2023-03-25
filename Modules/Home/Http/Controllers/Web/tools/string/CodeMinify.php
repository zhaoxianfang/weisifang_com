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
            // 判断是js 还是css,通过定义变量的方式来区分
            if (
                stripos($codeString, " var ") !== false
                || stripos($codeString, " let ") !== false
                || stripos($codeString, " const ") !== false
                || stripos($codeString, "=") !== false
            ) {
                // 压缩js
                $minifier = new min\JS($codeString);
            } else {
                // 压缩css
                $minifier = new min\CSS($codeString);
            }

            $minifiedCode = $minifier->minify();

            $oldLen      = mb_strlen($codeString);
            $newLen      = mb_strlen($minifiedCode);
            $minifyRatio = bcmul(bcdiv($newLen, $oldLen, 4), 100, 2) . '%';

            return $this->success(['min_str' => $minifiedCode, 'old_len' => $oldLen, 'new_len' => $newLen, 'minify_ratio' => $minifyRatio], '转换成功', 200);
        }
        return view('home::home.tools.string.code_minify', []);
    }

}
