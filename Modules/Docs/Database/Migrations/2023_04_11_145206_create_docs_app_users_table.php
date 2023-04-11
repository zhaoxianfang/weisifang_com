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
        Schema::create('docs_app_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('用户ID');
            $table->unsignedBigInteger('audit_id')->index()->comment('审核操作人ID');
            $table->timestamp('audit_at')->nullable()->comment('审核操作时间');
            $table->unsignedBigInteger('doc_app_id')->index()->comment('应用ID');
            $table->string('extra_nickname', 30)->nullable()->comment('用户在本文档中的备注昵称');
            $table->tinyInteger('role')->default(0)->index()->comment('在本文档中拥有的角色；0:待审核，3：参与者/伙伴：5：文档编辑；7：管理员，9：创始人');
            $table->tinyInteger('status')->default(0)->index()->comment('所属状态；0:待审核；1：同意，2：驳回，3：移出');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `docs_app_users` comment '接口文档应用成员列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs_app_users');
    }
};
