<?php

use Illuminate\Support\Facades\Route;
use \Modules\Test\Http\Controllers\Web;

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

Route::prefix('test')->group(function () {
    Route::get('/', 'TestController@index');

    Route::get('lang', [Web\TestController::class, 'lang']);

    // bootstrap 表格
    Route::get('table', [Web\TestController::class, 'table']);
    Route::get('table/get_list', [Web\TestController::class, 'getTableList']);

    // 编辑器
    Route::get('editor', [Web\EditorController::class, 'index']);

    Route::get('table/get_list', [Web\TestController::class, 'getTableList']);

    // 文档
    Route::get('docs', [Web\TestController::class, 'testDocs'])->name('test_docs');
});
