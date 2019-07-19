<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->char('name');
            $table->char('like_name');
            $table->char('slug')->nullable();
            $table->integer('type');
            $table->char('image_origin')->nullable();
            $table->char('image_preview')->nullable();
            $table->integer('attached_filter_to_image')->nullable();
            $table->integer('sorting_order')->default(0);
            $table->boolean('show_on_index')->default(false);
            $table->boolean('show_on_header')->default(false);
            $table->boolean('show_on_footer')->default(false);
            $table->boolean('show_image')->default(true);
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
}
