<?php

namespace Modules\Home\Http\Controllers\Web\tools\images;

use Modules\Home\Http\Controllers\HomeBase;
use zxf\tools\Compressor as CompressorTool;

// 图片裁剪压缩
class Compressor extends HomeBase
{
    public function index()
    {
        if ($this->request->isMethod('post')) {

            if (!$this->request->hasFile('images_file')) {
                return $this->error('未选择图片', 401);
            }
            $file = $this->request->file('images_file');

            //验证是否上传成功
            if (!$file->isValid()) {
                return $this->error('图片上传失败', 404);
            }
            $oldSize = $file->getSize();

            $originalName = $file->getClientOriginalName();     // 原文件名
            $ext          = $file->getClientOriginalExtension();// 扩展名
            $type         = $file->getClientMimeType();
            $realPath     = $file->getRealPath();// 临时绝对路径

            $input = $this->request->input();
            // $input['proportion'];
            // $input['compress']; // 仅适用于 jpg 图片
            // $input['width'];
            // $input['height'];

            $Compressor = CompressorTool::instance();

            // $result = $Compressor->set('test.jpeg')->proportion(1)->get();
            // $result = $Compressor->set($realPath)->proportion($input['proportion'])->compress($input['compress'])->get(true);
            if (!empty($input['width']) || !empty($input['height'])) {
                $result = $Compressor->set($realPath)->resize($input['width'], $input['height'])->get(true);
            } else {
                $result = $Compressor->set($realPath)->proportion($input['proportion'])->get(true);
            }
            $newSize = $this->get_base64img_size($result);

            return $this->success(['base64_str' => $result, 'old_size' => byteFormat($oldSize), 'minify_size' => byteFormat($newSize), 'minify_ratio' => bcmul(bcdiv(bcsub($oldSize, $newSize, 4), $oldSize, 4), 100, 2) . '%'], '转换成功', 200);
        }


        return view('home::home.tools.images.compressor', []);
    }

    /**
     * # php获取base64格式图片的大小
     *
     * @param string $base64img base64格式的图片
     * @param string $type      默认获取的大小的单位为KB，可以指定单位为 B
     *
     * @return string
     */
    public function get_base64img_size($base64img, $type = 'KB')
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64img, $result)) {
            $base_img = str_replace($result[1], '', $base64img);
            $base_img = str_replace('=', '', $base_img);
            $img_len  = strlen($base_img);
            return intval($img_len - ($img_len / 8) * 2); // 图片大小 ，单位：bit
        }
        return 0;
    }
}
