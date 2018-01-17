<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAttrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('packing')->comment('0：不包装，1：包装');
            $table->unsignedInteger('spec')->comment('商品规格');
            $table->unsignedInteger('postage')->comment('邮费');
            $table->unsignedInteger('postage_area')->comment('偏远地区额外邮费')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_attr');
    }
}
