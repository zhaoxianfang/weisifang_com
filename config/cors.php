<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    /* 你可以为 1 个或多个路径启用 CORS;例如 ['api/*'] */
    'paths'                    => ['api/*', 'sanctum/csrf-cookie'],

    /* 匹配请求方法 */
    'allowed_methods'          => ['*'],

    /* 匹配请求源; 可以使用通配符，例如 * 或 *.mydomain.com 或 mydomain.com:* */
    // 'allowed_origins'          => ['*'],
    'allowed_origins'          => ['weisifang_com.test', 'weisifang.com'],

    /* 使用 preg_match 匹配请求源 */
    'allowed_origins_patterns' => [],

    /* 设置 Access-Control-Allow-Headers 响应头 */
    'allowed_headers'          => ['*'],

    /* 设置 Access-Control-Expose-Headers 响应头 */
    'exposed_headers'          => [],

    /* 设置 Access-Control-Max-Age 响应头 */
    'max_age'                  => 0,

    /* 设置 Access-Control-Allow-Credentials 响应头 */
    'supports_credentials'     => false,

];
