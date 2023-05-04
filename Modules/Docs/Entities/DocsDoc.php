<?php

namespace Modules\Docs\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocsDoc extends Model
{

    // 公开状态
    const OPEN_TYPE_OPEN       = 1;
    const OPEN_TYPE_NEED_LOGIN = 2;
    const OPEN_TYPE_ONLY_SELF  = 3;
    const OPEN_TYPE_SENSITIVE  = 9;

    static $openTypeMaps = [
        self::OPEN_TYPE_OPEN       => '公开',
        self::OPEN_TYPE_NEED_LOGIN => '登录可见',
        self::OPEN_TYPE_ONLY_SELF  => '仅自己可见',
        self::OPEN_TYPE_SENSITIVE  => '敏感待审核',
    ];

    // 文档类型
    const TYPE_EDITOR   = 1;
    const TYPE_MARKDOWN = 2;
    const TYPE_API      = 3;

    static $typeMaps = [
        self::TYPE_EDITOR   => 'editor',
        self::TYPE_MARKDOWN => 'markdown',
        self::TYPE_API      => 'api',
    ];

    public static function getTypeReversal()
    {
        return array_flip(self::$typeMaps);
    }

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
        "request_headers"   => 'array',
        "request_body"      => 'array',
        "request_examples"  => 'array',
        "response_examples" => 'array',
    ];

    /**
     * 默认加载的关联
     *
     * @var array
     */
//    protected $with = [
//        'menu'
//    ];

    /**
     * 模型的「引导」方法。
     *
     * @return void
     */
    protected static function booted()
    {
        // 使用匿名全局作用域查询 当前用户可见的文档
        static::addGlobalScope('can_show_doc', function (Builder $builder) {
            if (auth('web')->guest()) {
                // 未登录用户仅展示 公开的
                $builder->where('open_type', self::OPEN_TYPE_OPEN);
            } else {
                // 登录用户展示仅自己可见的+公开的+需要登录才可见的
                $builder->where(function (Builder $builder) {
                    $builder->whereIn('open_type', [self::OPEN_TYPE_OPEN, self::OPEN_TYPE_NEED_LOGIN])
                        ->orWhere(function (Builder $builder) {
                            $builder->where('open_type', self::OPEN_TYPE_ONLY_SELF)
                                ->where('user_id', auth('web')->id());
                        });
                });
            }
        });

        // 模型事件 处理
        //static::creating(function ($model) {
        //    $model->title   = zxf_substr($model->title, 0, 190);                  // 限制标题长度
        //    $model->content = mb_substr($model->content, 0, 4294967000, 'UTF-8'); // 限制长度
        //});
    }

    /**
     * 获取文档内容 实体转html
     *
     * @param string $value
     *
     * @return void
     */
    public function content(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
            return !empty($value) && $this->type == DocsDoc::TYPE_EDITOR ? html_entity_decode($value) : $value;
        },
            set: function ($value, $attributes) {
            return !empty($value) && $attributes['type'] == DocsDoc::TYPE_EDITOR ? htmlentities($value) : $value;
        },
        );
        // return !empty($value) && $this->type == DocsDoc::TYPE_EDITOR ? html_entity_decode($value) : $value;
    }

    public function app()
    {
        return $this->belongsTo(DocsApp::class, 'doc_app_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(DocsAppMenu::class, 'doc_menu_id', 'id');
    }

    public function getUrl()
    {
        return route('docs.doc_info', ['doc' => $this->id]); //
    }

    /**
     * 全文搜索文章标题或内容
     *
     * @param string     $string 搜索的内容
     * @param string|int $appId  文档应用id
     * @param int        $limit  每次检索多少条数据，默认20
     *
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|\Illuminate\Support\HigherOrderWhenProxy[]|mixed
     */
    public static function search(string $string, $appId = '', int $limit = 20)
    {
        return self::query()
            ->select([
                'id',
                'title',
                DB::raw('substring(content,1,50) AS content'),
            ])
            ->where(function ($query) use ($string) {
                $query->whereFullText(['title', 'content'], to_full_text_search_str($string), ['mode' => 'boolean']);
            })
            ->when($appId, function ($query, $appId) {
                $query->where('doc_app_id', $appId);
            })
            ->limit($limit)
            ->get();
    }

}
