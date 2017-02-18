<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('studios', function (Blueprint $table){
        $table->increments('studio_id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('user_id')->on('users');
        $table->integer('city_id')->unsigned();
        $table->foreign('city_id')->references('city_id')->on('cities');
        $table->string('studio_nama');
        $table->string('studio_alamat');
        $table->string('studio_telepon');
        $table->string('studio_open_hour');
        $table->string('studio_close_hour');
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
        Schema::drop('studios');
    }
}
