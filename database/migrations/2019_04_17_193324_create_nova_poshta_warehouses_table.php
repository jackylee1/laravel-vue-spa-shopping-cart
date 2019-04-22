<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovaPoshtaWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_poshta_warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref');
            $table->string('city_ref');
            $table->string('site_key');
            $table->string('type_of_warehouse');
            $table->string('number');
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_poshta_warehouses');
    }
}
