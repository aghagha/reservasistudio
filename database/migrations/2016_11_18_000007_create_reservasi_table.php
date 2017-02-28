<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('reservasis', function (Blueprint $table){
        $table->increments('reservasi_id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('user_id')->on('users');
        $table->integer('studio_id')->unsigned();
        $table->foreign('studio_id')->references('studio_id')->on('studios');
        $table->integer('room_id')->unsigned();
        $table->foreign('room_id')->references('room_id')->on('rooms');
        $table->string('reservasi_nomor_booking')->unique();
        $table->string('reservasi_nama_band');
        $table->string('reservasi_tagihan');
        $table->string('refund_status');
        $table->string('reservasi_status');
        $table->string('reservasi_waktu_booking');
        $table->string('reservasi_batas');
        $table->string('reservasi_tanggal');
        $table->string('reservasi_refund');
        $table->timestamp('refunded_at');
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
        Schema::drop('reservasis');
    }
}
