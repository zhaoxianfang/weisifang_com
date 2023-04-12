<?php

namespace Modules\Core\Services;

use Illuminate\Support\Str;

class BaseService
{
    /**
     * @var object 对象实例
     */
    protected static $instance = [];

    /**
     * 初始化类
     * @access public
     */
    public static function instance()
    {
        $calledClassSlug = Str::slug(get_called_class(), '');
        if (empty(self::$instance[$calledClassSlug]) || is_null(self::$instance[$calledClassSlug])) {
            $args                             = func_get_args();
            self::$instance[$calledClassSlug] = new static(...$args);
        }
        return self::$instance[$calledClassSlug];
    }


}
