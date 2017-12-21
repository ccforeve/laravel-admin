<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->comment('唯一标识(编号)');
            $table->integer('product_id')->comment('产品表id');
            $table->integer('user_id')->comment('前台用户表id');
            $table->tinyInteger('type')->comment('产品类型1:免费领取 2:套装 3:20元免费领取一次');
            $table->integer('status')->comment('支付状态(0:未支付 1:已支付 2：已退款)');
            $table->integer('confirm')->comment('发货后的状态(0:未发货 1:已发货 2:已收货)');
            $table->float('product_price', 5, 2)->comment('产品价格');
            $table->float('pay_price', 5, 2)->comment('支付价格');
            $table->integer('use_integral')->comment('使用的积分数');
            $table->integer('p_id')->comment('上级推广人id');
            $table->integer('dealer_id')->comment('上级经销商id');
            $table->tinyInteger('is_status')->comment('订单状态(0:正常状态 1:取消订单（隐藏订单） 2:申请退款 3:收货退款)');
            $table->tinyInteger('complete')->comment('完整订单(需填写完收件人信息之后)');
            $table->string('remark')->default('""')->comment('订单备注')->nullable();
            $table->tinyInteger('is_contact')->comment('客服电话联系（1：已联系）');
            $table->integer('distribution')->index()->comment('后台客服id（分配订单）');
            $table->integer('address_id')->comment('收货表id');
            $table->integer('logistic_id')->comment('物流表id');
            $table->tinyInteger('activity')->comment('活动折扣订单（1：免费 2：折扣1 3：折扣2 3：···）');
            $table->dateTime('pay_time')->comment('支付时间')->nullable();
            $table->dateTime('receipt_time')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
