<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHpContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hp_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('sub_name')->nullable();
            $table->longText('summary')->nullable();
            $table->longText('sub_summary')->nullable();
            $table->longText('summary1')->nullable();
            $table->longText('sub_summary2')->nullable();
            $table->string('video')->nullable();
            $table->string('link')->nullable();
            $table->string('avatar1')->default('upload/contents/no.png');
            $table->string('avatar2')->default('upload/contents/no.png');
            $table->string('avatar3')->default('upload/contents/no.png');
            $table->string('avatar4')->default('upload/contents/no.png');
            $table->string('type');
            $table->integer('user_id')->unsigned()->index();
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
        Schema::dropIfExists('hp_contents');
    }
}
