<?php

use Illuminate\Support\Facades\Route;
use \Modules\Docs\Http\Controllers\Web;

Route::pattern('id', '[0-9]+');
Route::pattern('ids', '[,0-9]+');

Route::pattern('name', '[a-zA-Z]+');

Route::pattern('app', '[0-9]+');
Route::pattern('doc', '[0-9]+');
Route::pattern('menu', '[0-9]+');
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

Route::prefix('docs')->name('docs')->group(function () {
    // 文档主页
    Route::get('/', [Web\DocsController::class, 'index'])->name('home');
    // apps 应用管理
    Route::prefix('')->group(function () {
        // 创建文档
        Route::get('create', [Web\DocsAppController::class, 'create'])->name('create');
        Route::post('create', [Web\DocsAppController::class, 'store'])->name('store');
        // 某文档首页
        Route::get('{docsApp}', [Web\DocsAppController::class, 'firstPage'])->name('doc_first_page');
    });

    // 登录注册授权页
    Route::prefix('auth')->name('auth.')->group(function () {
        // 登录页
        Route::get('login', [Web\DocsAuthController::class, 'login'])->name('login');
        Route::post('login', [Web\DocsAuthController::class, 'loginHandle']);
        Route::get('register', [Web\DocsAuthController::class, 'register'])->name('register');
        Route::post('register', [Web\DocsAuthController::class, 'registerHandle']);

        Route::get('qqlogin', [Web\DocsAuthController::class, 'qqlogin'])->name('qqlogin');
        Route::get('weibologin', [Web\DocsAuthController::class, 'weibologin'])->name('weibologin');

        Route::post('callback', [Web\DocsAuthController::class, 'loggedIn']);
    });
});
