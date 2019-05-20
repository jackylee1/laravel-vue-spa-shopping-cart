<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('favorite_id')->unsigned();
            $table->integer('product_id')->unsigned();

            $table->foreign('favorite_id')
                ->references('id')->on('favorites')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_products');
    }
}
