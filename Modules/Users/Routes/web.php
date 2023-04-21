<?php

use Illuminate\Support\Facades\Route;
use \Modules\Users\Http\Controllers\Web;

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

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', 'UserController@index');

    // 登录注册授权页
    Route::prefix('auth')->name('auth.')->group(function () {
        // 登录页
        // Route::get('login', [Web\UserController::class, 'login'])->name('login');
        Route::post('login', [Web\UserController::class, 'login']);
        // 注册
        Route::post('register', [Web\UserController::class, 'register']);

//        Route::post('login', 'DocsAuthController@loginHandle');
//        Route::get('register', 'DocsAuthController@register')->name('register');

//
//        Route::get('qqlogin', 'DocsAuthController@qqlogin')->name('qqlogin');
//        Route::get('weibologin', 'DocsAuthController@weibologin')->name('weibologin');
//
//        Route::post('callback', 'DocsAuthController@loggedIn');
    });
});
