<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrder1Table extends Migration
{
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {

            $table->integer('city_id')->unsigned()->index();
            $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
