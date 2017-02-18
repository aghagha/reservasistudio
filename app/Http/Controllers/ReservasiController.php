<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservasi;
use App\ReservasiDetail;
use App\Jadwal;
use App\Studio;
use App\Room;
use \Exception;

class ReservasiController extends Controller
{
  function store(Request $request){
    // try {
      $user_id = $request->input('user_id');
      $room_id = $request->input('room_id');
      $harga = Room::where('room_id','=',$room_id)->get()->toArray()[0]['room_harga'];
      $nomor_booking = strtoupper('S'.$this->getRandomHex(3));
      $nama_band = $request->input('nama_band');
      $waktu_booking = date('H:i:s');
      $tanggal = $request->input('tanggal');
      $jadwal = $request->input('jadwal');
      $list_jadwal = explode(',',$jadwal);
      $tagihan = sizeof($list_jadwal) * (int)$harga;

      $input = new Reservasi();
      $input->user_id =$user_id;
      $input->room_id =$room_id;
      $input->reservasi_nomor_booking =$nomor_booking;
      $input->reservasi_nama_band =$nama_band;
      $input->reservasi_tagihan =$tagihan;
      $input->reservasi_status = '0';
      $input->reservasi_waktu_booking = $waktu_booking;
      $input->reservasi_tanggal = $tanggal;
      try {
        $input->save();
        foreach ($list_jadwal as $l) {
          $detail = new ReservasiDetail();
          $detail->reservasi_id = $input->id;
          $detail->jadwal_id = $l;
          $detail->save();
          unset($detail);
        }
        $output=(object)array();
        $output->code = "1";
        $output->status = "Reservasi berhasil";
        $output->reservasi = $input;
      } catch (Exception $e) {
        return $e;
        $output=(object)array();
        $output->code = "0";
        $output->status = "Reservasi gagal";
      }
      return json_encode($output);
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

  function history(Request $request){
    $user_id = $request->input('user_id');
    $jadwal = Jadwal::all()->toArray();
    try {
      $reservasi = Reservasi::where('user_id','=',$user_id)->get()->toArray();
    } catch (Exception $e) {
      $reservasi['code']=0;
      $reservasi['status']='Request gagal';
      return json_encode((object)$reservasi);
    }
    if(count($reservasi)==0){
      $reservasi['code']=2;
      $reservasi['status']='Data kosong';
      return json_encode((object)$reservasi);
    } else {
      $j=0;
      foreach ($reservasi as $r) {
        $detail = ReservasiDetail::where('reservasi_id','=',$r['reservasi_id'])->get()->toArray();
        $len = count($detail);
        $jadwal_id = '';
        $i = 0;
        foreach ($detail as $d) {
          if($i == $len-1){
            $jadwal_id .= $jadwal[$d['jadwal_id']-1]['jadwal_start'].'-'.$jadwal[$d['jadwal_id']-1]['jadwal_end'];
            break;
          }
          $jadwal_id .=$jadwal[$d['jadwal_id']-1]['jadwal_start'].'-'.$jadwal[$d['jadwal_id']-1]['jadwal_end'].', ';
          $i++;
        }
        $reservasi[$j]['jadwal'] = $jadwal_id;
        $j++;
      }
    }
    $reservasi['code']=1;
    $reservasi['status']='Request berhasil';
    return json_encode((object)$reservasi);
  }

  function getRandomHex($num_bytes=4) {
    return bin2hex(openssl_random_pseudo_bytes($num_bytes));
  }

  function cancel(Request $request){
    $treshold = 1;
    $reservasi_id = $request->input('reservasi_id');
    $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->first();
    $output=(object)array();
    if($reservasi->count()>0){
      $tanggal = $reservasi->reservasi_tanggal;
      $now = date('Y-m-d',strtotime('now'));
      $beda = date_diff(date_create($tanggal),date_create(date('Y-m-d',strtotime('now'))))->d;
      if($beda < 1){
        $output->code = '-2';
        $output->status = 'Reservasi tidak bisa dibatalkan, sudah melebihi batas pembatalan';
        return json_encode($output);
      }

      try {
        $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->update(['reservasi_status'=>"-1"]);
        $output->code = '1';
        $output->status = 'Reservasi dibatalkan';
      } catch (Exception $e) {
        $output->code = '0';
        $output->status = 'Gagal membatalkan reservasi';
      }
    } else {
      $output->code = '-1';
      $output->status = 'Reservasi tidak ditemukan';
    }
    return json_encode($output);
  }

  function issue(Request $request){
    $reservasi_id = $request->input('reservasi_id');
    $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->first();
    $output=(object)array();
    if($reservasi->count()>0){
      try {
        $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->update(['reservasi_status'=>"1"]);
        $output->code = '1';
        $output->status = 'Issue reservasi berhasil';
      } catch (Exception $e) {
        $output->code = '0';
        $output->status = 'Issue reservasi gagal';
      }
    } else {
      $output->code = '-1';
      $output->status = 'Reservasi tidak ditemukan';
    }
    return json_encode($output);
  }
}