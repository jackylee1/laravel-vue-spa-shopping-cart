<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->nullable()->unsigned();
            $table->string('user_name')->nullable();
            $table->string('user_surname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('note')->nullable();

            $table->integer('order_status_id')->nullable();
            $table->integer('delivery_method')->nullable();
            $table->integer('order_payment_method_id')->nullable();
            $table->integer('promotional_code_id')->nullable();

            $table->char('area_id')->nullable();
            $table->char('city_id')->nullable();
            $table->char('warehouse_id')->nullable();

            $table->float('total_price')->nullable();
            $table->float('total_discount_price')->nullable();

            $table->boolean('read_status')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
