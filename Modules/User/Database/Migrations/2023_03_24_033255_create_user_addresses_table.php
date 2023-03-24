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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0)->index()->comment('用户id');
            $table->string('name')->comment('名称');
            $table->string('mobile')->nullable()->comment('手机号码');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('area')->nullable()->comment('县区');
            $table->string('address')->nullable()->comment('(选中的)详细地址');
            $table->string('detail')->nullable()->comment('详细地址');
            $table->string('code')->nullable()->comment('编号');
            $table->boolean('is_default')->default(false)->comment('是否默认');
            $table->json('map_geo')->nullable();
            $table->string('latitude')->nullable()->comment('维度');
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('status')->default('normal')->comment('状态');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `user_addresses` comment '用户地址'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
};
