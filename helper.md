# 记录一下常用的几个操作

## git 使用

> 文档：https://gitee.com/progit/

### git 记住账号密码

```
1.进入命令行
2.输入如下命令
git config --global credential.helper store
3.使用git pull
此时输入了账号密码系统就可以记住
```

### git 强制覆盖拉取代码

```
方式一；
git fetch --all  运行 fetch 以将所有 origin/ 引用更新为最新：
git branch backup-master 【可选】备份当前分支：
git reset --hard origin/master 或者  git reset --hard

方式二；
git reset --hard HEAD
git pull
```

### 修改仓库源

1、查看远程库

```
git remote -v
```

2 设置git远程库的用户名密码

```
git remote set-url origin [url]
```

```
如：git remote set-url origin https://username:passwd@ip:port/test/name.git
```

3、先删除再修改地址

```
git remote rm origin
git remote add origin [url]
```

### gitee 使用token 推拉代码

```
git clone https://gitee用户名:私人令牌@gitee.com/gitee用户名/仓库名.git
```

## composer

> https://zhuanlan.zhihu.com/p/569760310

安装

```
composer install
或者
composer install -vvv
composer install --ignore-platform-req=ext-sodium --ignore-platform-req=ext-sodium
```

```
01、composer list：获取帮助信息；
02、composer init：以交互方式填写composer.json文件信息；
03、composer install：从当前目录读取composer.json文件，处理依赖关系，并安装到vendor目录下；
04、composer update：获取依赖的最新版本，升级composer.lock文件；
05、composer require：添加新的依赖包到composer.json文件中并执行更新；
06、composer remove twbs/bootstrap; 卸载依赖包
07、composer search：在当前项目中搜索依赖包；
08、composer show：列举所有可用的资源包；
09、composer validate：检测composer.json文件是否有效；
10、composer self-update：将composer工具更新到最新版本；
    composer self-update -r ：回滚到安装的上一个版本
11、composer diagnose：执行诊断命令
12、composer clear：清除缓存
13、composer create-project：基于composer创建一个新的项目；
14、composer dump-autoload：在添加新的类和目录映射是更新autoloader
15、composer config -g repo.packagist 查看镜像地址
16、composer config -l -g 查看所有全局配置
```

### 如何安装和更新包

本地没有有 `vendor` 文件夹时(初次执行时) 需要执行 `composer install` 会生成composer.lock
新增修改了依赖包[本地已经有 `vendor` 文件夹时(已有`composer.lock`)]
需要执行 `composer update` 来跟新 `composer.json` 并重新生成`composer.lock`

默认地址改为中国镜像地址：

```
composer config -g repo.packagist composer https://packagist.phpcomposer.com
```

中国镜像地址还原成默认地址：（注意：这个是将中国镜像还原）

```
composer config -g repo.packagist composer https://packagist.org
```

## 部署

### 安装依赖

```
composer install
或者
composer install -vvv
composer install --ignore-platform-req=ext-sodium --ignore-platform-req=ext-sodium

```

重新加载composer

```
composer dump-autoload
```

### 配置.env 文件

> 复制`.env.example`文件为`.env`文件
> 修改`APP_URL` 等的配置信息

### 重新生成key

```
php artisan key:generate
```

### 创建符号链接

> 如果public目录中已经有符号链接 images和storage，只需要删除后重新执行下面的命令即可

```
php artisan storage:link
```

```
php artisan passport:keys
```

### 修改 access_token 长度

```
php artisan passport:keys --length=512 --force // 默认长度 4096 不建议修改长度
```

### 设置文件夹权限

```
chown -R nobody.nobody files
chown -R nobody.nobody photo
chown -R nobody.nobody storage
chown -R nobody.nobody vendor

chmod -R 775 /www/
```

### 执行数据库迁移

```
php artisan migrate
```

### 分页视图

```
php artisan vendor:publish --tag=laravel-pagination
```

### 自定义错误页面

```
php artisan vendor:publish --tag=laravel-errors
```

## 配置定时任务

crontab -uroot -e

```
* * * * * /usr/bin/php /www/weisifang_com/artisan schedule:run >> /dev/null 2>&1
```

## excel 处理

安装

```
composer require psr/simple-cache:^2.0 maatwebsite/excel

composer require maatwebsite/excel
```

发布

```
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

github : https://github.com/SpartnerNL/Laravel-Excel
docs : https://docs.laravel-excel.com/3.1/getting-started/installation.html

## 一些操作命令

```
systemctl start mysqld    #开启MySQL
systemctl status mysqld   #查看MySQL状态
systemctl enable mysqld    #设置开机启动            
# systemctl daemon-reload    #重新加载

systemctl restart mysqld   #重启MySQL
systemctl stop firewalld   #关闭防火墙

```

```
systemctl start nginx.service
systemctl status nginx.service
nginx -s reload
systemctl reload nginx.service
systemctl restart nginx.service

设置开机自启
systemctl enable nginx

检查状态：nginx -t
```

```
加载服务
systemctl start php-fpm.service
systemctl status php-fpm.service

配置开机启动服务 
systemctl enable php-fpm.service

其他几个命名
systemctl stop php-fpm.service
systemctl restart php-fpm.service
systemctl disable php-fpm.service
```

```
重启Linux
reboot
重启后查看redis服务
chkconfig --list

redis启动/停止
启动服务：service redis start
停止服务：service redis stop
重启服务：service redis restart
```

## linux

```
> 把文件夹 www_root 所属组改为 www 组
sudo chgrp -R www /www/
sudo chmod -R ug+rwx /www/

chown -R nobody.nobody /www/
chmod -R 775 /www/

fuser -k 80/tcp

查看mysql 读取配置的顺序
mysqld --help --verbose | more 
```

## 常见问题

### git

```
error: cannot open .git/FETCH_HEAD: Permission denied
解决 chown -R user:user .git
```

### Git拉取的代码出现不管有没有修改的文件都变成了修改状态处理方法

处理方法：

```
// 项目目录下执行
git config core.filemode false

//全局设置
git config --global core.filemode false
```

如果打不开项目可以尝试修改项目文件所属组
> 把文件夹 www_root 所属组改为 www 组
> sudo chgrp -R www /www/
> sudo chmod -R ug+rwx /www/

```
chown -R nobody.nobody /www/
chmod -R 775 /www/

nginx 平滑重启：nginx -s reload

fuser -k 80/tcp
```

mysql 报错 only_full_group_by的问题：https://blog.csdn.net/qq_43427354/article/details/128462293

查看：select @@GLOBAL.sql_mode;

```
ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION
```

修改mysql sql_mode

```
vim /etc/my.cnf
sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"
```

出现问题：Could not find package xxx with stability stable. 是composer 的中国镜像出了问题，把他改了，换成另外一个地址就行了：

```
composer config -g repo.packagist composer https://packagist.org
```

Git拉取的代码出现不管有没有修改的文件都变成了修改状态处理方法 处理方法：

```
// 项目目录下执行
git config core.filemode false

//全局设置
git config --global core.filemode false
```

laravel file_put_contents(xxxx): Failed to open stream: Permission denied 解决(直接给文件夹设置777权限)：chmod -R 777
xxx/

## 其他

composer总结 https://zhuanlan.zhihu.com/p/569760310
PHP基础知识 - PHP函数大全 https://blog.csdn.net/qq_35453862/article/details/125874242
PHP 函数的完整参考手册 https://blog.csdn.net/Fancie_Wong/article/details/53007273

## 如何使用语言本地化

在`config/app.php`中配置 中文语言包

```
'locale' => 'zh-CN',
```

### 在Test 模块中定义语言包

`Modules/Test/Resources/lang` 文件夹下配置
> zh-CN.json 文件 和 zh-CN 文件夹方式可以任选其一或者都使用

```
zh-CN.json
// 内容：{ "hello": "您好 by zh-CN.json" }  
// 调用：{{ __('hello') }}
//      echo __('hello');

zh-CN/abc.php
// 内容：return [ 'hello' => '您好 by zh-CN/abc.php', 'welcome' => 'Welcome, :name' ]; 
// 调用：{{ __('test::abc.hello')  }} 
//      {{ __('test::abc.welcome', ['name' => '张三'])  }}
```




## 多模块
清理路由和配置
```
php artisan optimize
```
