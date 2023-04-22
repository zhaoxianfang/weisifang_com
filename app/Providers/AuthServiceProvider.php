<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //if (!$this->app->routesAreCached()) {

        // Passport::hashClientSecrets(); //(不推荐) 如果您希望客户端密钥在存储到数据库时使用 Hash 对其进行加密
        Passport::tokensExpireIn(now()->addHour(8)); // 默认令牌发放有效期8小时
        Passport::refreshTokensExpireIn(now()->addDays(10));// 刷新令牌 新增20天
        Passport::personalAccessTokensExpireIn(now()->addMonths(2)); // 初次授权2个月
        // }
    }
}
