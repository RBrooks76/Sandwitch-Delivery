<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products_feature', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_products_id')->unsigned()->index();
            $table->foreign('order_products_id')->references('id')->on('order_products')->onDelete('cascade');

            $table->integer('products_feature_id')->unsigned()->index();
            $table->foreign('products_feature_id')->references('id')->on('products_feature')->onDelete('cascade');

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
        Schema::dropIfExists('order_products_feature');
    }
}
