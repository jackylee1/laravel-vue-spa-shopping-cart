<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name');
            $table->string('user_surname');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->char('status')->default('user'); //user or administration
            $table->text('description')->nullable();
            $table->integer('discount')->default(0);
            $table->integer('reliability')->default(1);
            /*
             * 1 - надежный
             * 2 - не надежный
             */
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
