<?php

namespace Modules\Home\Http\Controllers\Web\tools\images;

use Modules\Home\Http\Controllers\HomeBase;

use zxf\tools\TextToImg;
use zxf\tools\TextToPNG;

// 字符串转图片
class StrToImg extends HomeBase
{
    public function index()
    {
        return view('home::home.tools.images.str2img', []);
    }

    // demo : /tools/text2png/ApiDoc2.0上线啦/1000/100/FFFFFF/7B00FF/0/qiuhong.html
    // 字符串生成图片
    public function create($text = '')
    {

        $paramStr = $text ? $text : '文字生成图片ABC/400/300/FFFFFF/0000FF/0/lishu/1';
        $paramStr = rtrim($paramStr, ".html");
        $param    = explode('/', $paramStr);

        // 文字，宽度，高度，颜色，背景色，文字旋转角度
        $text      = isset($param['0']) && !empty($param['0']) ? $param['0'] : 'hello';
        $width     = isset($param['1']) && !empty($param['1']) ? $param['1'] : '500';
        $height    = isset($param['2']) && !empty($param['2']) ? $param['2'] : '350';
        $color     = isset($param['3']) && !empty($param['3']) ? $param['3'] : 'FFFFFF';
        $bgcolor   = isset($param['4']) && !empty($param['4']) ? $param['4'] : '0000FF';
        $rotate    = isset($param['5']) && !empty($param['5']) ? $param['5'] : '0';
        $font      = isset($param['6']) && !empty($param['6']) ? $param['6'] : 'lishu';

        //隶书字体 lishu
        // $text    = strtr($text, '+', ' '); // TextToPNG 中使用
        $color   = strtr($color, '#', '');
        $bgcolor = strtr($bgcolor, '#', '');

        // TextToPNG::instance()->setFontStyle($font)->setText($text)->setSize($width, $height)->setColor($color)->setBackgroundColor($bgcolor)->setTransparent(false)->setRotate($rotate)->allowWrap($allowWrap)->draw();
        // 另一种方式
        TextToImg::instance($width, $height)->setFontStyle($font)->setText($text)->setColor($color)->setBgColor($bgcolor)->setAngle($rotate)->render();
        die;
    }
}
