<?php

use Illuminate\Support\Facades\Route;
use Modules\Home\Http\Controllers\Web;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('')->group(function () {
    Route::get('/', [Web\HomeController::class, 'index'])->name('home');
});

// 在线工具
Route::prefix('tools')->name('tools.')->group(function () {
    // 字符处理
    Route::prefix('string')->name('string.')->group(function () {
        // css | js 代码压缩
        Route::any('code_minify', [Web\tools\string\CodeMinify::class, 'index'])->name('code_minify');
        // unicode 转码
        Route::any('unicode', [Web\tools\string\Unicode::class, 'index'])->name('unicode');
        // json 格式化
        Route::any('json', [Web\tools\string\JsonTools::class, 'index'])->name('json');
        // serialize 序列话和反序列化
        Route::any('serialize', [Web\tools\string\Serialize::class, 'index'])->name('serialize');
    });

    Route::prefix('images')->name('images.')->group(function () {
        // 图片压缩裁剪
        Route::any('compressor', [Web\tools\images\Compressor::class, 'index'])->name('img_compressor');
        // 条形码 || 二维码
        Route::any('qrcode', [Web\tools\images\Qrcode::class, 'index'])->name('create_qrcode');
        // 字符串生成图片
        Route::any('create', [Web\tools\images\StrToImg::class, 'index'])->name('str2img');
        // 图片转ico
        Route::any('ico', [Web\tools\images\ImgToIco::class, 'index'])->name('img2ico');
    });

    // 其他路由

    // demo : /tools/text2png/ApiDoc2.0上线啦/1000/100/FFFFFF/7B00FF/0/qiuhong.html
    Route::get('/text2png/{text}/{width?}/{height?}/{color?}/{bgcolor?}/{rotate?}/{font?}', [Web\tools\images\StrToImg::class, 'create'])->where('text', '.*');
    // mysql 数据字典生成
    Route::any('mysql/dictionary', [Web\tools\mysql\Dictionary::class, 'index']);
});
