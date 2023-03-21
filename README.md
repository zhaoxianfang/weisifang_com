# 威四方

## About Laravel
Laravel 10.0

```
composer install
复制 .env.example 为 .env
php artisan key:generate
```

composer 默认地址改为中国镜像地址：
```
composer config -g repo.packagist composer https://packagist.org
```

```
composer require zxf/tools 
```
### 发布`zxf/tools`模块和配置
```
php artisan vendor:publish --provider="zxf\laravel\ServiceProvider"
```

### 在项目 composer.json 中新增自动加载
```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Modules\\": "Modules/", <-- 增加本行即可
        }
    },
```

### 创建模块
```
php artisan module:make System
```
### 创建迁移文件
```
php artisan module:make-migration create_system_logs_table System

// 执行迁移文件
php artisan module:migrate System
// 回滚迁移文件
php artisan module:migrate-rollback System
```

发布异常页面
```
php artisan vendor:publish --tag=laravel-errors
```

### 自定义接管系统异常
```
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    // App\Exceptions\Handler::class
    \Modules\System\Exceptions\CustomHandler::class // 自定义接管系统异常
);
```
