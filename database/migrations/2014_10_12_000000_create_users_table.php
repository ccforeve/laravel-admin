<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid')->comment('微信openid');
            $table->string('nickname')->comment('昵称');
            $table->string('head')->comment('头像');
            $table->unsignedTinyInteger('type')->comment('1:普通用户 2:经销商');
            $table->integer('p_id')->default(0)->comment('上级推广人id');
            $table->integer('dealer_id')->default(0)->comment('上级经销商id');
            $table->string('bank_name')->default("")->comment('提现账户');
            $table->string('bank_number')->default("")->comment('账户绑定的手机号');
            $table->string('bank_username')->default("")->comment('开户姓名');
            $table->unsignedTinyInteger('subscribe')->default(0)->comment('关注公众号（1:已关注）');
            $table->integer('address_id')->default(0)->comment('默认最新的购买的地址');
            $table->unsignedTinyInteger('is_extension')->default(0)->comment('是否有推广订单');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
