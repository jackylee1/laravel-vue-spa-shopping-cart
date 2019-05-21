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
            $table->text('preview_description')->nullable();
            $table->text('like_preview_description')->nullable();
            $table->float('price');
            $table->float('discount_price')->nullable();
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
            $table->integer('main_image')->nullable();
            $table->date('date_inclusion')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('in_xml')->default(false);

            $table->text('m_title')->nullable();
            $table->text('m_description')->nullable();
            $table->text('m_keywords')->nullable();

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
