<?php

namespace Modules\Logs\Entities;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class SystemLog extends Model
{

    // 日志级别[error:系统异常;warning:警告;notice:普通提示;lowest:最低级别]
    const LEVEL_ERROR   = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_NOTICE  = 'notice';
    const LEVEL_LOWEST  = 'lowest';

    static $levelMaps = [
        self::LEVEL_ERROR   => '异常',
        self::LEVEL_WARNING => '警告',
        self::LEVEL_NOTICE  => '普通',
        self::LEVEL_LOWEST  => '最低',
    ];

    /**
     * 模型的属性默认值。 自动赋值属性
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     * 不能被批量赋值的属性
     * 如果你想让所有属性都可以批量赋值， 你可以将 $guarded 定义成一个空数组。 如果你选择解除你的模型的保护，你应该时刻特别注意传递给 Eloquent 的 fill、create 和 update 方法的数组：
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 可批量赋值的属性。
     *
     * @var array
     */
    // protected $fillable = [
    //    'options->enabled', // options 是 JSON 列属性
    // ];

    /**
     * 类型转换
     *
     * @var array
     */
    protected $casts = [
        "extra" => 'array',
    ];

    // 截断表
    public function truncate()
    {
        // return self::truncate();
    }

    /**
     * 模型的「引导」方法。
     *
     * @return void
     */
    protected static function booted()
    {

    }

    /**
     * 记录系统日志信息
     *
     * @param string       $title
     * @param array|string $content
     * @param int|string   $user_id
     * @param array|string $extra
     * @param string       $level
     *
     * @return void
     * @throws Exception
     */
    public static function writeLog(string $title = '', array|string $content = [], int|string $user_id = 0, array|string $extra = [], string $level = self::LEVEL_NOTICE)
    {
        $request      = request();
        $extra['url'] = $request->fullUrl();
        // 检测爬虫
        $crawlerInfo           = is_crawler(true);
        $extra['is_crawler']   = !empty($crawlerInfo);
        $extra['crawler_name'] = $crawlerInfo;
        // 请求数据
        $extra['params'] = $request->input();
        self::create([
            'user_id'     => $user_id,
            'module_name' => underline_convert(get_module_name()),// 使用小写下划线模块名称,
            'title'       => $title,
            'context'     => json_encode((array)$content),
            'extra'       => $extra,
            'source_ip'   => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'level'       => $level,
        ]);
    }

    /**
     * 统一记录抛出的异常错误信息
     *
     * @param Throwable $err 是 PHP7.0 开始出现的异常类，他是 \Error \Exception 的父类
     *
     * @throws Exception
     */
    public static function writeErr(Throwable $err)
    {
        $userId = (int)get_user_info('id');
        self::writeLog('系统异常', [
            "异常信息："      => $err->getMessage(),   //返回用户自定义的异常信息
            "异常代码："      => $err->getCode(),      //返回用户自定义的异常代码
            "文件名："        => $err->getFile(),      //返回发生异常的PHP程序文件名
            "异常代码所在行" => $err->getLine(),        //返回发生异常的代码所在行的行号
            "传递路线"       => $err->getTrace(),      //返回发生异常的传递路线
            // "传递路线"    => $e->getTraceAsString(),      //返回发生异常的传递路线
        ], $userId, [], self::LEVEL_ERROR);
    }

}
