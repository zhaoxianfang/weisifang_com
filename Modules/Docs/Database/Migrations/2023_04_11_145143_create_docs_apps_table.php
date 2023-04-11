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
        Schema::create('docs_apps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uni_code', 30)->nullable()->unique()->comment('应用唯一标识');
            $table->string('app_name', 60)->nullable()->comment('应用名称');
            $table->string('app_cover')->nullable()->comment('应用封面图');
            $table->json('urls')->nullable()->comment('应用接口可能用到的跳转地址:json格式');
            $table->string('description')->nullable()->comment('应用描述');
            $table->integer('sort')->default(0)->comment('排序；值越大越靠前');
            $table->tinyInteger('open_type')->nullable()->default(1)->index()->comment('应用公开类型；1全公开，2仅文档成员可见');
            $table->unsignedBigInteger('create_by')->default(0)->index()->comment('应用创建人');
            $table->string('theme', 20)->nullable()->default('default')->comment('应用主题风格');
            $table->smallInteger('mark_days')->default(7)->comment('标记多少天内修改的文档');
            $table->string('team_name')->nullable()->comment('团队名称/创作者');
            $table->timestamps();
            $table->tinyInteger('status')->default(1)->index()->comment('应用状态；1正常，0停用');
        });
        \DB::statement("ALTER TABLE `docs_apps` comment '文档应用'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs_apps');
    }
};
