<?php

use Illuminate\Support\Facades\Route;
use \Modules\Docs\Http\Controllers\Web;

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
    Route::get('/', [Web\DocsController::class, 'index'])->name('docs_home');
    // 创建文档
    Route::get('create', [Web\DocsAppController::class, 'create'])->name('create');
    Route::post('create', [Web\DocsAppController::class, 'store'])->name('store');
    // 某文档首页
    Route::get('{docsApp}', [Web\DocsAppController::class, 'firstPage'])->name('doc_first_page');

});
