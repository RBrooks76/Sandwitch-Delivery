<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');

            $table->string('order_id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('ip')->nullable();
            $table->integer('phone_active')->default(0);

            $table->string('lat')->nullable();
            $table->string('log')->nullable();

            $table->decimal('total');
            $table->integer('status')->default(0);

            $table->integer('payment_type')->default(0);

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
        Schema::dropIfExists('order');
    }
}
