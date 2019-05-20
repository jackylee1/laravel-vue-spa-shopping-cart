<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductFilterTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_filter_trees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_product_id')->unsigned();
            $table->integer('filter_id')->unsigned();

            $table->unique(['order_product_id', 'filter_id'], 'order_product_filter_tree_u_of');

            $table->foreign('order_product_id')->references('id')->on('order_products')->onDelete('cascade');
            $table->foreign('filter_id')->references('id')->on('filters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_filter_trees');
    }
}
