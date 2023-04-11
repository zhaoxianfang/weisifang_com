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
        Schema::create('docs_docs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('创建用户ID');
            $table->unsignedBigInteger('doc_app_id')->index()->comment('应用ID');
            $table->unsignedBigInteger('doc_menu_id')->index()->comment('应用菜单ID');
            $table->string('title')->fullText()->comment('接口标题');
            $table->longText('content')->fullText()->comment('文档内容或者api描述');
            $table->integer('sort')->default(0)->comment('排序；值越大越靠前');
            $table->tinyInteger('type')->default(2)->index()->comment('文档类型；1：富文本，2：Markdown，3：api 接口');
            $table->tinyInteger('open_type')->default(0)->index()->comment('开放状态；1：公开，2：登录可见，3：仅创建用户自己可见,9:敏感待审核');
            $table->timestamps();
            $table->string('method', 10)->nullable()->default('get')->comment('接口请求类型');
            $table->string('api_url')->nullable()->default('')->comment('接口请求路径');
            $table->text('request_headers')->nullable()->comment('请求头信息');
            $table->text('request_body')->nullable()->comment('请求主体');
            $table->text('request_examples')->nullable()->comment('请求数据样例');
            $table->text('response_examples')->nullable()->comment('响应样例');
            $table->fulltext(['title', 'content']);
        });
        \DB::statement("ALTER TABLE `docs_docs` comment '文档里面的文章'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs_docs');
    }
};
