<?php

namespace Modules\Home\Http\Controllers\Web\tools\images;

use Modules\Home\Http\Controllers\HomeBase;
use Illuminate\Http\Request;
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

            return $this->success(['base64_str' => $result], '转换成功', 200);
        }


        return view('home::home.tools.images.compressor', []);
    }
}
