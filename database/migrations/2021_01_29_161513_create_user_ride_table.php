<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ride', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('license');

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
        Schema::dropIfExists('user_ride');
    }
}
