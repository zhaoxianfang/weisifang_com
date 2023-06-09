<?php

namespace Modules\Docs\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DocsAppMenu extends Model
{

    // 公开状态
    const OPEN_TYPE_OPEN       = 1;
    const OPEN_TYPE_NEED_LOGIN = 2;
    const OPEN_TYPE_ONLY_SELF  = 3;

    static $openTypeMaps = [
        self::OPEN_TYPE_OPEN       => '公开',
        self::OPEN_TYPE_NEED_LOGIN => '登录可见',
        self::OPEN_TYPE_ONLY_SELF  => '仅自己可见',
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
     * 默认加载的关联
     *
     * @var array
     */
    protected $with = [
        'menus',
        'docs',
    ];

    /**
     * 模型的「引导」方法。
     *
     * @return void
     */
    protected static function booted()
    {
        // 使用匿名全局作用域查询 当前用户可见的菜单
        static::addGlobalScope('can_show', function (Builder $builder) {
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
    }

    /**
     * 查询 当前用户可见的 局部作用域。[局部作用域] 无传参
     *
     * @demo  DocsAppMenu::CanShow()->...
     *        DocsAppMenu::canShow()->orWhere->...
     *
     * @param $query
     *
     * @return void
     */
    public function scopeCanShow($query)
    {
        if (auth('web')->guest()) {
            // 未登录用户仅展示 公开的
            $query->where('open_type', self::OPEN_TYPE_OPEN);
        } else {
            $query->where(function (Builder $builder) {
                $builder->whereIn('open_type', [self::OPEN_TYPE_OPEN, self::OPEN_TYPE_NEED_LOGIN])
                    ->orWhere(function (Builder $builder) {
                        $builder->where('open_type', self::OPEN_TYPE_ONLY_SELF)
                            ->where('user_id', auth('web')->id());
                    });
            });
        }
    }

    // 该目录下的文章列表
    public function docs()
    {
        return $this->hasMany(DocsDoc::class, 'doc_menu_id', 'id')->orderByDesc('sort')->orderBy('created_at');
    }

    // 该目录下的子目录
    public function menus()
    {
        return $this->hasMany(DocsAppMenu::class, 'parent_id', 'id')->orderByDesc('sort')->orderBy('created_at');
    }

    // 父级menu
    public function menu()
    {
        return $this->belongsTo(DocsAppMenu::class, 'parent_id', 'id');
    }

    public function app()
    {
        return $this->belongsTo(DocsApp::class, 'doc_app_id', 'id');
    }
}
