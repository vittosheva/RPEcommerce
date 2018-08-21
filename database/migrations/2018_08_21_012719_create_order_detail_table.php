<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_order_detail');
            $table->integer('id_order');
            $table->integer('id_order_invoice');
            $table->integer('id_shop');
            $table->integer('product_id');
            $table->integer('product_attribute_id');
            $table->string('product_name')->nullable();
            $table->integer('product_quantity');
            $table->decimal('product_price', 20, 6);
            $table->decimal('product_quantity_discount', 20, 6);
            $table->string('product_reference')->nullable();
            $table->decimal('unit_price_tax_excl', 20, 6);
            $table->decimal('total_price_tax_excl', 20, 6);
            $table->decimal('tax', 20, 6);
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
        Schema::dropIfExists('order_detail');
    }
}
