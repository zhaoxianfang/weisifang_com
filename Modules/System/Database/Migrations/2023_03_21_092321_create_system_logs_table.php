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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->default(0)->comment('触发的用户ID(0表示系统)');
            $table->string('title')->nullable()->comment('日志标题');
            $table->longText('context')->nullable()->comment('提示内容');
            $table->json('extra')->nullable()->comment('附加数据');
            $table->string('source_ip')->nullable()->comment('触发者ip');
            $table->string('user_agent')->nullable()->comment('userAgent');
            $table->string('level', 20)->index()->default('notice')->comment('日志级别[error:系统异常;warning:警告;notice:普通提示;lowest:最低级别]');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `system_logs` comment '系统日志'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
};
