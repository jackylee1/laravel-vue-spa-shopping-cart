<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovaPoshtaCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_poshta_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref');
            $table->string('area');
            $table->string('settlement_type');
            $table->string('city_id');
            $table->string('description')->nullable();
            $table->string('settlement_type_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_poshta_cities');
    }
}
