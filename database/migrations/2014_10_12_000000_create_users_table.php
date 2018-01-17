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
            $table->unsignedTinyInteger('type')->comment('1:普通用户 2:经销商')->nullable();
            $table->integer('p_id')->default(0)->comment('上级推广人id')->nullable();
            $table->integer('dealer_id')->default(0)->comment('上级经销商id')->nullable();
            $table->string('bank_name')->default("")->comment('提现账户')->nullable();
            $table->string('bank_number')->default("")->comment('账户绑定的手机号')->nullable();
            $table->string('bank_username')->default("")->comment('开户姓名')->nullable();
            $table->unsignedTinyInteger('subscribe')->default(0)->comment('关注公众号（1:已关注）')->nullable();
            $table->integer('address_id')->default(0)->comment('默认最新的购买的地址')->nullable();
            $table->unsignedTinyInteger('is_extension')->default(0)->comment('是否有推广订单')->nullable();
            $table->unsignedInteger('scale')->default(0)->comment('经销商推广积分比例')->nullable();
            $table->timestamp('exchange_time')->comment('兑换时间')->nullable();
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
