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
            $table->char('article');
            $table->char('slug');
            $table->char('name');
            $table->char('like_name');
            $table->text('description');
            $table->text('preview_description');
            $table->text('like_preview_description');
            $table->float('price');
            $table->integer('discount')->nullable();
            $table->integer('main_image')->nullable();
            $table->boolean('status')->default(false);
            $table->date('date_inclusion')->nullable(); //дата включения
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
        Schema::dropIfExists('products');
    }
}
