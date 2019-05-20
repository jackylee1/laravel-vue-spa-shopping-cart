<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkToSocialNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_to_social_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->char('url');
            $table->char('image_preview');
            $table->char('image_origin');
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
        Schema::dropIfExists('link_to_social_networks');
    }
}
