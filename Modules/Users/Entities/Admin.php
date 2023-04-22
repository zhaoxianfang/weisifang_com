<?php

namespace Modules\Users\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

// 使用laravel 默认模式 登录
//use Laravel\Sanctum\HasApiTokens;
// 使用 Passport 方式登录 Passport也是支持 session 登录模式滴
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 不能被批量赋值的属性
     * 如果你想让所有属性都可以批量赋值， 你可以将 $guarded 定义成一个空数组。 如果你选择解除你的模型的保护，你应该时刻特别注意传递给 Eloquent 的 fill、create 和 update 方法的数组：
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // 用户状态 0未激活，1正常，2冻结
    const STATUS_NOT_USED = 0;
    const STATUS_NORMAL   = 1;
    const STATUS_FREEZE   = 2;

    static $statusMaps = [
        self::STATUS_NOT_USED => '未激活',
        self::STATUS_NORMAL   => '正常',
        self::STATUS_FREEZE   => '冻结',
    ];
}
