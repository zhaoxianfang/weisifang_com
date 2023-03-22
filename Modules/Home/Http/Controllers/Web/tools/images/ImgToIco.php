<?php

namespace Modules\Home\Http\Controllers\Web\tools\images;

use Illuminate\Http\Request;
use Modules\Home\Http\Controllers\HomeBase;


class ImgToIco extends HomeBase
{
    public function index(Request $request)
    {
        if ($this->request->isMethod('post')) {

            if ($request->hasFile('images')) {
                $request->validate([
                    'images' => 'file|max:10240|mimes:jpeg,png,jpg',
                ], [
                    'images.max'   => '上传图片大小不能超过10M',
                    'images.mimes' => '不支持的图片格式',
                ]);

                $file = $request->file('images');


                $base64 = \zxf\tools\ImgToIco::instance()->set($file->getRealPath(), (int)$request->size)->generate();
                return $this->success(['base64_str' => $base64], '转换成功', 200);
                die;
//                \zxf\tools\ImgToIco::instance()->set($file->getPathname(), $request->size)->generate();
//                dd($file);
//                ImgToIco::instance()->set($imgurl, 32)->generate();
            }
//            dd(sys_get_temp_dir());
//            $imgurl = "./test.jpeg";
//            // 下载到浏览器
//            \zxf\tools\ImgToIco::instance()->set($imgurl, 32)->generate();
//            // 保存到指定文件夹
//            \zxf\tools\ImgToIco::instance()->set($imgurl, 32)->generate('E:/www');
        }
        return view('home::home.tools.images.img2ico');
    }
}
