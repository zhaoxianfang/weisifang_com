<?php

namespace Modules\Core\Services;

use Modules\System\Entities\File;
use function request;

class FilesServices extends BaseService
{
    /**
     * 上传文件
     * @param $fileName 上传文件传递的文件名称字段
     */
    public function upload($fileName = '', $driver = 'files')
    {
        $request = request();
        if ($request->hasFile($fileName)) {
            $request->validate([
                $fileName => 'file|max:102400'
            ], [
                $fileName . '.max' => '上传文件大小不能超过100M'
            ]);

            //自动流存储 https://learnku.com/docs/laravel/9.x/filesystem/12229#f55d45
            $file = $request->file($fileName);

            // $file->extension() 获取的文件后缀会把 js 等的文件格式 识别为 txt
            if (!in_array($file->getClientOriginalExtension(), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'pdf', 'png', 'jpg', 'jpeg'])) {
                return [
                    'code'      => 412, //上传状态 200成功;412失败
                    'message'   => '不支持的文件格式' . $file->getClientOriginalExtension(), //提示信息
                    'file_name' => '',
                    'url'       => '', //文件访问地址
                ];
            }

            //验证是否上传成功
            if ($file->isValid()) {
                //存储
                $fileName = uniqid() . '.' . $file->extension(); // 自定义文件名
                $path     = $file->storeAs(date('Ymd'), $fileName, $driver);
                // 记录文件信息
                $fileSaveInfo = $this->writeFileInfo($file, $driver, $fileName, $path);

                return [
                    'code'      => 200, //上传状态 200成功;412失败
                    'message'   => '上传成功', //提示信息
                    'file_name' => $file->getClientOriginalName(),
                    'url'       => $fileSaveInfo->getUrl() //文件访问地址
                ];
            }
        }
        return [
            'code'      => 412, //上传状态 200成功;412失败
            'message'   => '文件上传失败', //提示信息
            'file_name' => '',
            'url'       => '', //文件访问地址
        ];
    }

    /**
     * 下载文件
     * @return void
     */
    public function download()
    {
    }

    /**
     * 记录文件上传痕迹
     *
     * @param $fileObj
     * @param $driver
     * @param $fileName
     * @param $path
     * @return File
     */
    private function writeFileInfo($fileObj, $driver, $fileName, $path)
    {
        $file = new File();
        $file->fill([
            'user_id'       => auth('web')->guest() ? (auth('admin')->id() ?? 0) : auth('web')->id(),
            'name'          => $fileName, // 文件存储名,
            'ext'           => $fileObj->getClientOriginalExtension(), // 扩展名 jpeg 的 后缀名为 jpg
            'original_name' => $fileObj->getClientOriginalName(), // 原文件名,
            'type'          => $fileObj->getClientMimeType(), // 获取上传文件的 Mime 类型 （image/png）,
            'size'          => $fileObj->getSize(),// 获取上传文件的大小
            'path'          => $path,
            'driver'        => $driver,
            'status'        => File::STATUS_UNUSED,
        ]);
        $file->save();
        return $file;
    }
}

