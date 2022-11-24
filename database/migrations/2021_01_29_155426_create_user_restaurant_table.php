<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_restaurant', function (Blueprint $table) {
            $table->increments('id');

            $table->string('phone');
            $table->string('name');
            $table->string('website');
            $table->string('address');
            $table->string('lat')->nullable();
            $table->string('lot')->nullable();

            $table->integer('city_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('user_restaurant');
    }
}
