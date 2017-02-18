<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('rooms', function (Blueprint $table){
        $table->increments('room_id');
        $table->integer('studio_id')->unsigned();
        $table->foreign('studio_id')->references('studio_id')->on('studios');
        $table->string('room_nama');
        $table->string('room_harga');
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
        //
    }
}
