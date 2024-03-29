<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name');
            $table->char('slug');
            $table->char('image_origin')->nullable();
            $table->char('image_preview')->nullable();
            $table->char('image_index_origin')->nullable();
            $table->char('image_index_preview')->nullable();
            $table->boolean('show_on_index')->default(false);
            $table->boolean('show_on_footer')->default(false);
            $table->boolean('show_on_certificate')->default(false);
            $table->boolean('show_on_header')->default(true);
            $table->integer('sorting_order')->default(0);

            $table->text('m_title')->nullable();
            $table->text('m_description')->nullable();
            $table->text('m_keywords')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types');
    }
}
