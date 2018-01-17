<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_pays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->comment('每点击一次生产微信支付订单号');
            $table->integer('user_id')->comment('用户id');
            $table->integer('order_id')->comment('订单表id');
            $table->tinyInteger('mode')->comment('支付方式(1:微信 2:支付宝)');
            $table->tinyInteger('status')->default(0)->comment('支付状态')->nullable();
            $table->string('trade_no')->comment('第三方支付平台支付单号')->nullable();
            $table->string('refund_no')->comment('退款订单号')->nullable();
            $table->tinyInteger('refund_state')->comment('退款状态（0：未退款  1：已退款）')->nullable();
            $table->dateTime('refund_success_time')->comment('退款完成时间')->nullable();

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
        Schema::dropIfExists('pay_orders');
    }
}
