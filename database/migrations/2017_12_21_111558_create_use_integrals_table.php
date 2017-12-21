<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseIntegralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_integrals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('前台用户表id');
            $table->tinyInteger('mean')->comment('使用途径(1:兑换产品 2:银行卡提现)');
            $table->integer('order_id')->comment('订单表id')->nullable();
            $table->integer('use_integral')->comment('消耗的积分');
            $table->tinyInteger('status')->comment('兑换状态(1:兑换成功)');
            $table->tinyInteger('type')->comment('积分类型(2:随意使用的积分 1:只能兑换红包的积分)');
            $table->string('remark')->default('""')->comment('客服备注')->nullable();
            $table->tinyInteger('state')->comment('客服标记是否转账');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('use_integrals');
    }
}
