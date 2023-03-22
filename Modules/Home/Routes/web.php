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

        // html | css | js 字符串压缩
        Route::any('jsmin', 'tools\string\Jsmin@index')->name('js_min');
        // json 格式化
        Route::any('json', 'tools\string\JsonTools@index');
        // unicode 转码
        Route::any('unicode', 'tools\string\Unicode@index');
        Route::any('serialize', 'tools\string\Serialize@index');
    });

    Route::prefix('images')->name('images.')->group(function () {
        // 图片压缩裁剪
        Route::any('compressor', 'tools\images\Compressor@index')->name('img_compressor');
        // 条形码 || 二维码
        Route::any('qrcode', 'tools\images\Qrcode@index')->name('create_qrcode');
        // 字符串生成图片
        Route::any('create', 'tools\images\StrToImg@index')->name('str2img');
        // 图片转ico
        Route::any('ico', 'tools\images\ImgToIco@index')->name('img2ico');
    });


    // 其他路由

    // demo : /tools/text2png/ApiDoc2.0上线啦/1000/100/FFFFFF/7B00FF/0/qiuhong.html
    Route::get('/text2png/{text}/{width?}/{height?}/{color?}/{bgcolor?}/{rotate?}/{font?}/{allow_wrap?}', 'tools\images\StrToImg@create')->where('text', '.*');
    // mysql 数据字典生成
    Route::any('mysql/dictionary', 'tools\mysql\Dictionary@index');
});
