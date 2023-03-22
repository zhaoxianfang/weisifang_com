<?php

namespace Modules\Home\Http\Controllers\Web\tools\images;

use Modules\Home\Http\Controllers\HomeBase;
use Illuminate\Http\Request;
use zxf\qrcode\QrCode as QrCodeTool;
use zxf\qrcode\BarCode;

// 条形码 || 二维码
class Qrcode extends HomeBase
{
    public function index()
    {

        if ($this->request->isMethod('post')) {

            $input = $this->request->input();

            $createType = $input['create_type'] ?: 'QrCode'; // 类型

            $text     = $input['text'] ?: $this->request->root(); // 文字
            $fontSize = $input['font_size'] ?: 16;                // 文字字体大小
            // $label = $input['label'] ?: env('APP_NAME'); // label
            $label = $input['label'] ?: '';                       // 显示在二维码下方的文字

            if ($createType == 'QrCode') {
                $size    = $input['size'] ?: 200;       // 设置二维码大小
                $padding = $input['padding'] ?: 10;     // 边距
                $level   = $input['level'] ?: 'medium'; // 容错级别


                $qrCode = new QrCodeTool();
                $qrCode
                    ->setText($text) // 生成二维码的内容
                    ->setSize((int)$size) // 设置二维码大小
                    ->setPadding((int)$padding) // 设置边距
                    ->setErrorCorrection($level) // 设置二维码纠错级别。 分为 high(30%)、quartile(25%)、medium(15%)、low(7%) 几种
                    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0)) // 设置颜色
                    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0)) // 设置背景色
                                                                                              // ->setLabel($label) // 设置图片下面的文字
                    ->setLabelFontSize((int)$fontSize)                                        // 设置文字字体大小
                    ->setImageType(QrCodeTool::IMAGE_TYPE_PNG); // 设置图片类型 ,默认为 png

                !empty($label) && $qrCode->setLabel($label); // 设置图片下面的文字

                // ->draw(); // 把图片直接绘画到浏览器
                // echo '<img src="data:' . $qrCode->getContentType() . ';base64,' . $qrCode->generate() . '" />';

                $data64 = 'data:' . $qrCode->getContentType() . ';base64,' . $qrCode->generate();
            } else {
                $codeType  = $input['code_type'] ?: 'CINcode128'; // 条形码类型
                $thickness = $input['thickness'] ?: 25;           // 设置厚度或高度
                $scale     = $input['scale'] ?: 2;                // 设置分辨率

                $barcode = new BarCode();                // 实例化
                $barcode->setText($text);                // 设置条形码内容
                $barcode->setFontSize((int)$fontSize);   //  设置字体大小
                $barcode->setBackgroundColor('#ffffff'); //  设置背景色
                // $barcode->setType(BarCode::Isbn); // 设置条形码类型,支持Code128、Code11、Code39、Code39Extended、Ean128、Gs1128、I25、Isbn、Msi、Postnet、S25、Upca、Upce 类型的条形码
                $barcode->setType($codeType);

                $barcode->setScale((int)$scale);              // 设置分辨率
                $barcode->setThickness((int)$thickness);      // 设置厚度或高度
                !empty($label) && $barcode->setLabel($label); // 设置图片下面的文字

                // $barcode->draw(); // 把图片直接绘画到浏览器

                $code = $barcode->generate();
                // echo '<img src="data:image/png;base64,' . $code . '" />';

                $data64 = 'data:image/png;base64,' . $code;
            }

            return $this->success(['base64_str' => $data64], '转换成功', 200);

        }


        // echo '<hr>';
        // echo '<p>Example - Isbn</p>';
        // $barcode = new BarCode();
        // $barcode->setText("0012345678901");
        // $barcode->setType(BarCode::Isbn);
        // $code = $barcode->generate();
        // ->draw();

        // die;

        return view('home::home.tools.images.qrcode', []);
    }
}
