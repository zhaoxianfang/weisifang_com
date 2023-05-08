# 安装&部署
## 安装依赖
```
composer install

```
# 配置.env 文件

> 复制`.env.example`文件为`.env`文件
> 修改`APP_URL` 等的配置信息

## 重新生成key
```
php artisan key:generate
```

## 设置文件夹权限
```
chmod -R 775 /www/
chown -R nobody.nobody /www/
```
### 项目目录下的 storage/framework/sessions 目录写数据会报无权限错误
laravel file_put_contents(xxxx): Failed to open stream: Permission denied 
解决(直接给文件夹设置777权限)：
```
chmod -R 777 storage/framework/
```

## 分页视图

```
php artisan vendor:publish --tag=laravel-pagination
```
## 自定义错误页面

```
php artisan vendor:publish --tag=laravel-errors
```

## 配置定时任务

crontab -uroot -e

```
默认
* * * * * /usr/bin/php /www/weisifang_com/artisan schedule:run >> /dev/null 2>&1
或者跟换你的php 路径
* * * * * /usr/local/php8/bin/php /www/weisifang_com/artisan schedule:run >> /dev/null 2>&1
```

## 重新加载composer

```
composer dump-autoload
```

## 部署 Passport
```
composer require laravel/passport
php artisan migrate
php artisan passport:install
php artisan passport:keys 或者 php artisan passport:keys --force
```

## 多模块使用

### 发布模块和配置
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

### 重新加载composer
```
composer dump-autoload
```
