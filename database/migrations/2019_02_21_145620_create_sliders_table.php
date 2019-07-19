<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->char('title');
            $table->text('description')->nullable();
            $table->text('url')->nullable();
            $table->text('title_align')->default('left');
            $table->text('title_color')->default('#fff');
            $table->text('btn_align')->default('left');
            $table->char('image_origin');
            $table->char('image_preview');
            $table->integer('sorting_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
