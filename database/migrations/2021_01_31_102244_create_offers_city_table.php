<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_city', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('city_id')->unsigned()->index();
            $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');

            $table->integer('offers_id')->unsigned()->index();
            $table->foreign('offers_id')->references('id')->on('offers')->onDelete('cascade');

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
        Schema::dropIfExists('offers_city');
    }
}
