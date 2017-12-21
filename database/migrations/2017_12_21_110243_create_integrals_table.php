<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('订单表id');
            $table->integer('user_id')->index()->comment('用户表id');
            $table->integer('integral')->comment('获得的积分');
            $table->tinyInteger('type')->comment('积分类型（1：免费商品，2：套装商品）');
            $table->tinyInteger('status')->default('1')->comment('积分状态（1：待确认，2：已获得，3：退款不可用积分）');
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
        Schema::dropIfExists('integrals');
    }
}
