<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInFilterTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_in_filter_trees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_in_filter_id')->unsigned();
            $table->integer('filter_id')->unsigned();

            $table->unique(['product_in_filter_id', 'filter_id'], 'product_in_filter_tree_u_pf');

            $table->foreign('product_in_filter_id')->references('id')->on('product_in_filters')->onDelete('cascade');
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
        Schema::dropIfExists('product_in_filter_trees');
    }
}
