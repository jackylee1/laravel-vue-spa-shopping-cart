<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAvailableFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_available_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_available_id')->unsigned();
            $table->integer('filter_id')->unsigned();

            $table->foreign('filter_id')
                ->references('id')
                ->on('filters')
                ->onDelete('cascade');
            $table->foreign('product_available_id')
                ->references('id')
                ->on('product_availables')
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
        Schema::dropIfExists('product_available_filters');
    }
}
