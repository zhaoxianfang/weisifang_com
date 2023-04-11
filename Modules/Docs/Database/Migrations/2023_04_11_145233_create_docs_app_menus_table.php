<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_app_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('创建用户ID');
            $table->unsignedBigInteger('doc_app_id')->index()->comment('应用ID');
            $table->string('name', 60)->comment('菜单名称');
            $table->tinyInteger('open_type')->default(0)->index()->comment('所属状态；1：公开，2：登录可见，3：仅自己可见');
            $table->integer('sort')->default(0)->comment('排序；值越大越靠前');
            $table->unsignedBigInteger('parent_id')->default(0)->nullable()->index()->comment('父级菜单id');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `docs_app_menus` comment '文档应用菜单'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs_app_menus');
    }
};
