<?php

namespace Modules\Users\Entities;


use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
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
     * 类型转换
     *
     * @var array
     */
    protected $casts = [
        // "extra" => 'array',
    ];
}
