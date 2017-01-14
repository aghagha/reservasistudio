<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jadwal;
use App\Reservasi;
use App\ReservasiDetail;
use \Exception;

class JadwalController extends Controller
{
    //lihat jadwal yang tersedia pada hari H
    function lihatJadwal(Request $request){
      $tanggal = $request->input('tanggal');
      if($tanggal == '') return 'Input tanggal kosong';

      $reservasi = Reservasi::where('reservasi_tanggal','=',$tanggal)->get();

      if($reservasi->count() == 0){
        $jadwal = Jadwal::all();
        return json_encode($jadwal);
      } else {
        $reservasi = $reservasi->toArray();
        $reservasi_detail = array();
        foreach ($reservasi as $rsv) {
          $tmp = ReservasiDetail::where('reservasi_id','=',$rsv['reservasi_id'])->get()->toArray();
          //array_push($reservasi_detail,$tmp);
          foreach ($tmp as $t) {
            $reservasi_detail[$t['jadwal_id']]=false;
          }
        }
        $list_jadwal = Jadwal::all()->toArray();
        $jadwal = array();
        foreach ($list_jadwal as $j) {
          if(!array_key_exists($j['jadwal_id'],$reservasi_detail)){
            array_push($jadwal,$j);
          }
        }
        $reservasi_id = $request->input('reservasi_id');
        $jadwal_lama = ReservasiDetail::where('reservasi_id','=',$reservasi_id)->get()->toArray();
        foreach ($jadwal_lama as $j) {
          array_push($jadwal,$j);
        }
        return json_encode($jadwal);
      }
    }
}
