<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInFilterCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_in_filter_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_in_filter_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->unique(['product_in_filter_id', 'category_id'], 'product_in_filter_categories_u_pc');

            $table->foreign('product_in_filter_id')->references('id')->on('product_in_filters')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_in_filter_categories');
    }
}
