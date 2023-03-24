<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_origins', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index()->comment('用户id');
            $table->string('nickname', 30)->nullable()->comment('昵称');
            $table->unsignedTinyInteger('gender')->default(0)->comment('性别：0未设置，1男，2女');
            $table->string('cover')->nullable()->index()->comment('头像');
            $table->string('type')->index()->comment('登录类型（手机号phone 邮箱email 用户名username）或第三方应用名称（微信weixin 微博weibo 腾讯QQqq等）');
            $table->string('open_id')->unique()->comment('标识（手机号 邮箱 用户名或第三方应用的唯一标识）');
            $table->string('credential')->nullable()->comment('密码凭证（站内的保存密码，站外的不保存或保存token）');
            $table->boolean('verified')->default(true)->comment('是否已经验证');
            $table->dateTime('authorized_at')->nullable()->comment('授权时间（获取用户信息）');
            $table->json('user_info')->nullable()->comment('授权获取的用户信息');
            $table->boolean('logged_in')->default(false)->comment('是否最近登录');
            $table->dateTime('login_at')->nullable()->comment('最近登录时间');
            $table->json('all')->nullable()->comment('第三方回调的完整数据');
            $table->timestamps();
            $table->string('unionid')->nullable()->index();
            $table->string('secret')->nullable()->comment('登录密钥');
        });
        \DB::statement("ALTER TABLE `user_origins` comment '用户注册来源'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_auths');
    }
};
