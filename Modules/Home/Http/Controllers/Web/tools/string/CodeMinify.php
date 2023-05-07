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
            $codeType   = $this->request->input('code_type', 'auto');
            if (empty($codeString)) {
                return $this->error('请填入需要压缩的代码', 412);
            }
            if ($codeType == 'js') {
                $minifier = new min\JS($codeString);
            } elseif ($codeType == 'css') {
                $minifier = new min\CSS($codeString);
            } else {
                if (
                    preg_match('/function|if|else|for|while/', $codeString)
                    || preg_match('/\/\//', $codeString)
                    || (stripos($codeString, " var ") !== false
                        || stripos($codeString, " let ") !== false
                        || stripos($codeString, " const ") !== false
                    )
                ) {
                    // 包含 JavaScript 的逻辑和功能 || 的注释（// 或 /*） || 使用 var、let、const定义变量
                    $minifier = new min\JS($codeString);
                } else {
                    $minifier = new min\CSS($codeString);
                }
            }

            $minifiedCode = $minifier->minify();

            $oldLen      = mb_strlen($codeString);
            $newLen      = mb_strlen($minifiedCode);
            $minifyRatio = bcmul(bcdiv(bcsub($oldLen, $newLen, 4), $oldLen, 4), 100, 2) . '%';

            return $this->success(['min_str' => $minifiedCode, 'old_len' => byteFormat($oldLen), 'new_len' => byteFormat($newLen), 'minify_ratio' => $minifyRatio], '转换成功', 200);
        }
        return view('home::home.tools.string.code_minify', []);
    }

}
