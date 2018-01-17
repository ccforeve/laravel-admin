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
            $table->tinyInteger('product_type')->comment('产品类型1:免费领取 2:套装 3:20元免费领取一次')->nullable();
            $table->integer('status')->index()->default(0)->comment('支付状态(0:未支付 1:已支付 2：已退款)')->nullable();
            $table->integer('confirm')->default(0)->comment('发货状态(0:未发货 1:已发货 2:已收货)')->nullable();
            $table->float('product_price', 5, 2)->comment('产品价格');
            $table->float('original_price', 5, 2)->comment('原本需要支付价格');
            $table->float('pay_price', 5, 2)->comment('最终支付价格')->nullable();
            $table->integer('use_integral')->default(0)->comment('使用的积分数')->nullable();
            $table->integer('p_id')->default(0)->comment('上级推广人id')->nullable();
            $table->integer('dealer_id')->default(0)->comment('上级经销商id')->nullable();
            $table->tinyInteger('is_status')->default(0)->comment('订单状态(0:正常状态 1:申请退款 2:收货退款)')->nullable();
            $table->tinyInteger('complete')->default(0)->comment('完整订单(需填写完收件人信息之后)')->nullable();
            $table->string('remark')->default('""')->comment('订单备注')->nullable();
            $table->tinyInteger('is_contact')->default(0)->comment('客服电话联系（1：已联系）')->nullable();
            $table->integer('distribution')->index()->comment('后台客服id（分配订单）')->nullable();
            $table->tinyInteger('activity')->default(0)->comment('活动折扣订单（1：免费 2：折扣1 3：折扣2 3：···）')->nullable();
            $table->unsignedInteger('address_id')->default(0)->comment('收货表id');
            $table->unsignedInteger('logistic_id')->default(0)->comment('物流表id');
            $table->unsignedInteger('order_refund_id')->default(0)->comment('退款表id');
            $table->unsignedInteger('order_attr_id')->default(0)->comment('免费领取商品属性表id');
            $table->timestamp('pay_at')->comment('支付时间')->nullable();
            $table->timestamp('receipt_at')->comment('自动收货时间')->nullable();
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
