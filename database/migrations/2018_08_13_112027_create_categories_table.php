<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->nullable()->unsigned();
            $table->char('name');
            $table->char('like_name');
            $table->char('slug');
            $table->integer('sorting_order')->default(0);
            $table->boolean('show_on_header')->default(true);
            $table->integer('parent_id');
            $table->boolean('hidden_name')->default(false);
            $table->boolean('active_link')->default(true);

            $table->text('m_title')->nullable();
            $table->text('m_description')->nullable();
            $table->text('m_keywords')->nullable();

            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
