<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservasi;
use App\ReservasiDetail;
use App\Studio;
use \Exception;

class ReservasiController extends Controller
{
  function store(Request $request){
    // try {
      $user_id = $request->input('user_id');
      $studio_id = $request->input('studio_id');
      $harga = Studio::where('studio_id','=',$studio_id)->get()->toArray()[0]['studio_harga'];
      $nomor_booking = strtoupper('S'.$this->getRandomHex(3));
      $nama_band = $request->input('nama_band');
      $waktu_booking = date('H:i:s');
      $tanggal = $request->input('tanggal');
      $jadwal = $request->input('jadwal');
      $list_jadwal = explode(',',$jadwal);
      $tagihan = sizeof($list_jadwal) * (int)$harga;

      $input = new Reservasi();
      $input->user_id =$user_id;
      $input->studio_id =$studio_id;
      $input->reservasi_nomor_booking =$nomor_booking;
      $input->reservasi_nama_band =$nama_band;
      $input->reservasi_tagihan =$tagihan;
      $input->reservasi_status = '0';
      $input->reservasi_waktu_booking = $waktu_booking;
      $input->reservasi_tanggal = $tanggal;
      $input->save();

      foreach ($list_jadwal as $l) {
        $detail = new ReservasiDetail();
        $detail->reservasi_id = $input->id;
        $detail->jadwal_id = $l;
        $detail->save();
        unset($detail);
      }
    // } catch (Exception $e) {
      // return $e;
      //return 'Gagal melakukan reservasi';
    // }
  }

  function edit(Request $request){
    $reservasi_id = $request->input('reservasi_id');
    $reservasi = Reservasi::where('reservasi_id','=',$reservasi_id)->get();
    if($reservasi->count()==0){
      return 'Data Reservasi tidak ada';
    } else {
      $user_id = $request->input('user_id');
      $studio_id = $request->input('studio_id');
      $harga = Studio::where('studio_id','=',$studio_id)->get()->toArray()[0]['studio_harga'];
      $nama_band = $request->input('nama_band');
      $waktu_booking = date('H:i:s');
      $tanggal = $request->input('tanggal');
      $jadwal = $request->input('jadwal');
      $list_jadwal = explode(',',$jadwal);
      $tagihan = sizeof($list_jadwal) * (int)$harga;

      $data = [
        'reservasi_nama_band' =>$nama_band,
        'reservasi_tagihan'   =>$tagihan,
        'reservasi_tanggal'   =>$tanggal
      ];

      Reservasi::where('reservasi_id','=',$reservasi_id)->update($data);

      ReservasiDetail::where('reservasi_id','=',$reservasi_id)->delete();

      foreach ($list_jadwal as $l) {
        $detail = new ReservasiDetail();
        $detail->reservasi_id = $reservasi_id;
        $detail->jadwal_id = $l;
        $detail->save();
        unset($detail);
      }
    }
  }

  function getRandomHex($num_bytes=4) {
    return bin2hex(openssl_random_pseudo_bytes($num_bytes));
  }
}
