<?php
if (!function_exists('source_local_website')) {
    /**
     * 判断跳转url的上一个地址（来源地址）是不是从本站跳转过来的
     *
     * @return array [true|false,$referer]
     */
    function source_local_website()
    {
        $referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        return [empty($referer) || stripos($referer, 'weisifang') !== false, $referer];
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
