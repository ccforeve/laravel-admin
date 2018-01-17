<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('status')->default(0)->comment('退款状态（0：申请，1：退款完成）')->nullable();
            $table->float('money')->comment('退款金额')->nullable();
            $table->timestamp('apply_at')->comment('申请时间')->nullable();
            $table->timestamp('end_at')->comment('退款完成时间')->nullable()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_refund');
    }
}
