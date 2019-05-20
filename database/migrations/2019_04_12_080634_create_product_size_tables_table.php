<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSizeTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_size_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('size_table_id')->unsigned();

            $table->unique(['product_id', 'size_table_id']);

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
            $table->foreign('size_table_id')
                ->references('id')->on('size_tables')
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
        Schema::dropIfExists('product_size_tables');
    }
}
