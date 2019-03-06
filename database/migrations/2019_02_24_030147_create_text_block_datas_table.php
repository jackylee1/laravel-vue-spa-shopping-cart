<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextBlockDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_block_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('text_block_title_id')->unsigned();
            $table->char('title');
            $table->integer('type')->default(0); //0 - страница, ссылка
            $table->char('url')->nullable();
            $table->char('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->integer('sorting_order')->default(0); //порядок сортировке в блоке

            $table->foreign('text_block_title_id')->references('id')->on('text_block_titles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_block_datas');
    }
}
