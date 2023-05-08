<?php

use Illuminate\Support\Facades\Route;
use \Modules\Callback\Http\Controllers\Web;

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

Route::prefix('callback')->name('callback.')->group(function () {
    Route::get('/', 'CallbackController@index');

    Route::prefix('tencent')->name('tencent.')->group(function () {
        Route::any('login', [Web\Tencent\Connect::class, 'login'])->name('login');
        //Route::any('callback', 'Tencent\Connect@receive');
        Route::any('callback', [Web\Tencent\Connect::class, 'notify']);
        Route::any('notify', [Web\Tencent\Connect::class, 'notify']);
//        Route::any('callback', function () {
//            return 'hello';
//        });
    });
});
