<?php

if (!function_exists('get_user_info')) {
    /**
     * 获取laravel 已经登录的用户信息，没有登录的 返回false
     *
     * @param string|null $field
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function get_user_info(?string $field = null): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $user     = null;
        $authList = config('auth.guards');
        foreach ($authList as $authName => $val) {
            if (auth($authName)->check()) {
                $user = auth($authName)->user();
                break;
            }
        }
        return !empty($user) ? (empty($field) ? $user : $user[$field]) : null;
    }
}
if (!function_exists('is_mobile')) {
    /**
     * 判断当前浏览器是否为移动端
     */
    function is_mobile(): bool
    {
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
            return true;
        } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
            return true;
        } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return true;
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('str_code')) {
    /**
     * 字符串加密解密函数
     *
     * @param string      $string    字符串
     * @param string      $operation ENCODE:加密 DECODE:解密
     * @param int         $expiry    过期时间（s） 0 表示不设置过期时间
     * @param string|null $key       自定义密钥
     *
     * @return array|string|string[]
     */
    function str_code(string $string, string $operation = 'ENCODE', int $expiry = 0, ?string $key = '')
    {
        try {
            if ($operation == 'DECODE') {
                $string = str_replace(['[a]', '[b]', '[c]'], ['+', '&', '/'], $string);
            }

            $ckey_length = 4;
            $key         = md5($key ? $key : 'weisifang.com');
            $keya        = md5(substr($key, 0, 16));
            $keyb        = md5(substr($key, 16, 16));
            $keyc        = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
            $cryptkey    = $keya . md5($keya . $keyc);
            $key_length  = strlen($cryptkey);

            $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;

            $string_length = strlen($string);
            $result        = '';
            $box           = range(0, 255);
            $rndkey        = array();

            for ($i = 0; $i <= 255; $i++) {
                $rndkey[$i] = ord($cryptkey[$i % $key_length]);
            }

            for ($j = $i = 0; $i < 256; $i++) {
                $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
                $tmp     = $box[$i];
                $box[$i] = $box[$j];
                $box[$j] = $tmp;
            }

            for ($a = $j = $i = 0; $i < $string_length; $i++) {
                $a       = ($a + 1) % 256;
                $j       = ($j + $box[$a]) % 256;
                $tmp     = $box[$a];
                $box[$a] = $box[$j];
                $box[$j] = $tmp;
                $result  .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
            }

            if ($operation == 'DECODE') {
                if ((substr($result, 0, 10) == 0 || (int)substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                    return substr($result, 26);
                } else {
                    return '';
                }
            } else {
                $ustr = $keyc . str_replace('=', '', base64_encode($result));
                $ustr = str_replace(['+', '&', '/'], ['[a]', '[b]', '[c]'], $ustr);
                return $ustr;
            }
        } catch (\Exception $err) {
            return false;
        }
    }
}

if (!function_exists('to_full_text_search_str')) {
    /**
     * 把搜索字符串 组装 成 mysql 全文索引搜索 FullText 的搜索关键字字符串
     *
     * @param string $string 搜索的关键字
     *
     * @return string 处理后的字符串
     */
    function to_full_text_search_str(string $string): string
    {
        $string = preg_replace("/\*+/", '*', $string);
        $string = preg_replace("/\~+/", '~', $string);
        $string = preg_replace("/\-+/", '-', $string);
        $string = preg_replace("/\++/", '+', $string);
        $string = preg_replace("/\s+/", ' ', $string);// 把多个连续空格替换为一个空格
        $string = trim($string);
        if (empty($string)) {
            return '';
        }
        try {
            $strArr = explode(' ', $string);                // 拆分成数组
            $resArr = [];
            foreach ($strArr as $keyword) {
                $keyword = trim($keyword);
                if (empty($keyword) || in_array($keyword, ['+', '-', '~', '*'])) {
                    continue;
                }
                if (in_array($prefix = mb_substr($keyword, 0, 1, "utf-8"), ['+', '-', '~'])) {
                    $res_str  = str_replace(['+', '-', '~'], '', $keyword, $count);
                    $resArr[] = "$prefix*{$res_str}*";
                } else {
                    $resArr[] = "*{$keyword}*";
                }
            }
            return empty($resArr) ? '' : implode(' ', $resArr);
        } catch (\Exception $e) {
            $string = trim($string);
            return "*{$string}*";
        }
    }

    // 全文搜索使用说明：
    // 模糊搜索 在搜索词前后加 星号 * ;例: '*search*'、 'search*'、'*search'
    // + 表示and必须包含。- 表示not一定不包含; 例: '+apple -banana'
    // apple和banana之间是空格，空格表示or，即至少包含apple、banana中的一个; 例: 'apple banana'
    // 必须包含apple，但是如果同时也包含banana则会获得更高的权重; 例: '+apple banana'
    // ~ 异或运算符。返回的记录必须包含apple，但是如果同时也包含banana会降低权重。但是它没有 +apple -banana 严格，因为后者如果包含banana压根就不返回; 例: '+apple ~banana'
    // 返回同时包含apple和banana或者同时包含apple和orange的记录。但是同时包含apple和banana的记录的权重高于同时包含apple和orange的记录; 例: '+apple +(>banana <orange)'

    // 自然语言模式
    // return self::query()->whereFullText('title', $string)->orWhereFullText('content', $string)->get();
    // 布尔模式
    // return self::query()->whereFullText(['title', 'content'], '+测试 -公司', ['mode' => 'boolean'])->count();
    // 自然扩展模式
    // return self::query()->whereFullText('content', '测试', ['expanded' => true])->paginate(10);
    // 模型使用
    // return self::query()->whereFullText('content', '测试')->get();

    // return self::query()->whereFullText(['title', 'content'], to_full_text_search_str($string), ['mode' => 'boolean'])->get();
}


if (!function_exists('force_res_forbidden')) {
    /**
     * 403 Forbidden - 服务器已经理解请求，但是拒绝执行它[强制终止]
     *
     * @param string $message
     * @param null   $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function force_res_forbidden(string $message = '', $code = null)
    {
        return response()->json(['message' => $message, 'code' => $code], 403)->send();
    }
}
