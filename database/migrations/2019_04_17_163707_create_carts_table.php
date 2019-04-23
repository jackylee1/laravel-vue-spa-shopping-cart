<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->char('key')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('user_promotional_code_id')->nullable();
            $table->integer('order_payment_method_id')->nullable();
            $table->integer('delivery')->default(1);
            $table->char('area_id')->nullable();
            $table->char('city_id')->nullable();
            $table->char('warehouse_id')->nullable();
            $table->char('user_name')->nullable();
            $table->char('user_surname')->nullable();
            $table->char('user_patronymic')->nullable();
            $table->char('phone')->nullable();
            $table->char('email')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
