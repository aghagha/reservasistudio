<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationDetailTabl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservasi_details',function (Blueprint $table){
          $table->integer('reservasi_id')->unsigned();
          $table->foreign('reservasi_id')->references('reservasi_id')->on('reservasis');
          $table->integer('jadwal_id')->unsigned();
          $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('reservasi_details');
    }
}
