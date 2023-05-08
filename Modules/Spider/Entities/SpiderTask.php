<?php

namespace Modules\Spider\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\Article;

class SpiderTask extends Model
{
    // 采集目标类型;属于「文章」类型的采集结果才会记录到文章表;1文章正文，2文章列表,3报刊,4其他
    const TYPE_CONTENT = 1;
    const TYPE_LIST    = 2;
    const TYPE_NEWS    = 3;
    const TYPE_OTHER   = 4;

    static $typeMaps = [
        self::TYPE_CONTENT => '正文',
        self::TYPE_LIST    => '文章列表',
        self::TYPE_NEWS    => '报刊',
        self::TYPE_OTHER   => '其他',
    ];

    // 采集状态；1成功，2失败
    const RUN_STATUS_SUCCESS = 1;
    const RUN_STATUS_FAIL    = 2;

    static $runStatusMaps = [
        self::RUN_STATUS_SUCCESS => '成功',
        self::RUN_STATUS_FAIL    => '失败',
    ];

    // 是否子任务;1是0否
    const SUB_TASKS_YES = 1;
    const SUB_TASKS_NO  = 0;

    static $subTasksMaps = [
        self::SUB_TASKS_YES => '是',
        self::SUB_TASKS_NO  => '否',
    ];

    // 任务状态；1正常，2关闭
    const STATUS_NORMAL = 1;
    const STATUS_CLOSE  = 2;

    static $statusMaps = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_CLOSE  => '关闭',
    ];

    // 爬虫采集成功后 extend 中配置的保存渠道，默认为 文章 article
    // extend 在此处的格式 ['success'=>['save'=>'default']]
    const EXTEND_SUCCESS_SAVE_TO_DEFAULT = 'default'; // extend为空时使用此配置
    const EXTEND_SUCCESS_SAVE_TO_ARTICLE = 'article';

    // 成功后的保存到指定的模型
    static $extendSuccessSaveToModels = [
        self::EXTEND_SUCCESS_SAVE_TO_DEFAULT => Article::class,
        self::EXTEND_SUCCESS_SAVE_TO_ARTICLE => Article::class,
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
        "rules"  => 'array',
        "extend" => 'array', // 扩展
        "run_at" => 'datetime',
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
     * 只查询 主任务 列表
     *
     * @demo  SpiderTask::main()->...
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopeMain($query)
    {
        $query->where('sub_tasks', self::SUB_TASKS_NO);
        $query->where('status', self::STATUS_NORMAL);
    }

}
