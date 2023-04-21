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

### 安装 passport

```
composer require laravel/passport
php artisan migrate
php artisan passport:install

php artisan passport:keys
修改 access_token 长度
php artisan passport:keys --length=512 --force
```

#### 特别说明

> 如果使用 passport创建接口登录报错：Personal access client not found. Please create one
> 1、你没有运行过 那么你应该运行 php artisan passport:install
> 2、如果1不行，那么再试试 php artisan passport:client --personal

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
    \Modules\Core\Exceptions\CustomHandler::class // 自定义接管系统异常
);
```

git忽略文件不生效

```
解决方案
# 清除缓存文件
git rm -r --cached .
git add .
git commit -m ".gitignore重写缓存"
git pull
git push
```
