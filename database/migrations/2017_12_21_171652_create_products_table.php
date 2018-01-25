<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('产品名');
            $table->float('original_price')->comment('原价');
            $table->float('price')->comment('现价');
            $table->string('photo')->comment('产品图/详情产品图/订单产品图')->nullable();
            $table->tinyInteger('type')->comment('产品类型（1:免费领取 2:套装）');
            $table->text('details')->comment('产品详情');
            $table->tinyInteger('sort')->comment('排序');
            $table->float('praise')->comment('好评率');
            $table->integer('buy_count')->comment('购买数量');
            $table->tinyInteger('shelves')->default('1')->comment('上架（0：不上架）');
            $table->integer('stock')->comment('库存');
            $table->string('audio')->default('""')->comment('音频链接');
            $table->integer('tl_free_num')->comment('限时购免费数量');
            $table->double('tl_one_off')->comment('限时购折扣（%）');
            $table->integer('tl_one_num')->comment('限时折扣数量');
            $table->double('tl_two_off')->comment('限时第二折扣（%）');
            $table->integer('tl_two_num')->comment('限时第二折扣数量');
            $table->dateTime('tl_begin_time')->comment('限时购开始时间');
            $table->dateTime('tl_end_time')->comment('限时购到期时间');
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
        Schema::dropIfExists('products');
    }
}
