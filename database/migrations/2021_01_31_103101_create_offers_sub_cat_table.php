<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersSubCatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_sub_cat', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('offers_id')->unsigned()->index();
            $table->foreign('offers_id')->references('id')->on('offers')->onDelete('cascade');

            $table->integer('sub_category_id')->unsigned()->index();
            $table->foreign('sub_category_id')->references('id')->on('sub_category')->onDelete('cascade');

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
        Schema::dropIfExists('offers_sub_cat');
    }
}
