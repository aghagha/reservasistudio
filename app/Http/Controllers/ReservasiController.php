<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservasi;
use App\ReservasiDetail;
use App\City;
use App\Jadwal;
use App\Studio;
use App\Room;
use App\User;
use \Exception;
use Session;
use Redirect;

class ReservasiController extends Controller
{
  function store(Request $request){
      $output=(object)array();
    // try {
      $user_id = $request->input('user_id');
      $room_id = $request->input('room_id');
      $studio_id = Room::where('room_id',$room_id)->first(['studio_id'])->toArray()['studio_id'];
      $harga = Room::where('room_id','=',$room_id)->get()->toArray()[0]['room_harga'];
      $nomor_booking = strtoupper('S'.$this->getRandomHex(3));
      $nama_band = $request->input('nama_band');
      $waktu_booking = date('H:i:s');
      $tanggal = $request->input('tanggal');
      $jadwal = $request->input('jadwal');
      if($jadwal == ''){
        $output->code = "-1";
        $output->status = "Belum memilih jadwal";
        return json_encode($output);
      }
      $list_jadwal = explode(',',$jadwal);
      $tagihan = sizeof($list_jadwal) * (int)$harga;

      $input = new Reservasi();
      $input->user_id =$user_id;
      $input->studio_id =$studio_id;
      $input->room_id =$room_id;
      $input->reservasi_nomor_booking =$nomor_booking;
      $input->reservasi_nama_band =$nama_band;
      $input->reservasi_tagihan =$tagihan;
      $input->reservasi_status = '0';
      $input->reservasi_refund = '0';
      $input->reservasi_waktu_booking = $waktu_booking;
      $input->reservasi_tanggal = $tanggal;
      try {
        $input->save();
        foreach ($list_jadwal as $l) {
          $detail = new ReservasiDetail();
          $detail->reservasi_id = $input->id;
          $detail->jadwal_id = (int)$l;
          $detail->save();
          unset($detail);
        }
        $output->code = "1";
        $output->status = "Reservasi berhasil";
        $output->reservasi = $input;
      } catch (Exception $e) {
        $output->code = "0";
        $output->status = "Reservasi gagal";
      }
      return json_encode($output);
  }

  function edit(Request $request){
    $reservasi_id = $request->input('reservasi_id');
    $reservasi = Reservasi::where('reservasi_id','=',$reservasi_id)->get();
    $ouput=array();
    if($reservasi->count()==0){
      $output['code']='-1';
      $output['message'] = 'Data Reservasi tidak ada';
    } else {
      $reservasi->toArray();
      $jumlah = ReservasiDetail::where('reservasi_id',$reservasi_id)->get()->count();
      $nama_band = $request->input('nama_band');
      if($nama_band == '') $nama_band=$reservasi['reservasi_nama_band'];
      $jadwal = $request->input('jadwal');
      $list_jadwal = explode(',',$jadwal);
      if(count($list_jadwal) != $jumlah){
        $output['code']='0';
        $output['message'] = 'Edit reservasi gagal';
      } else {
        $data = [
          'reservasi_nama_band' =>$nama_band,
        ];

        try {
          Reservasi::where('reservasi_id','=',$reservasi_id)->update($data);
          ReservasiDetail::where('reservasi_id','=',$reservasi_id)->delete();
          foreach ($list_jadwal as $l) {
            $detail = new ReservasiDetail();
            $detail->reservasi_id = $reservasi_id;
            $detail->jadwal_id = $l;
            $detail->save();
            unset($detail);
          }
          $output['code']='1';
          $output['message'] = 'Edit reservasi berhasil';
        } catch (Exception $e) {
          $output['code']='0';
          $output['message'] = 'Edit reservasi gagal';
        }
      }
    }
    return json_encode($output);
  }

  function history(Request $request){
    $user_id = $request->input('user_id');
    $jadwal = Jadwal::all()->toArray();
    $reservasi = array();
    try {
      $reservasi['reservasi'] = Reservasi::where('user_id','=',$user_id)->get()->toArray();
    } catch (Exception $e) {
      $reservasi['code']=0;
      $reservasi['status']='Request gagal';
      return json_encode((object)$reservasi);
    }
    if(count($reservasi['reservasi'])==0){
      $reservasi['code']=2;
      $reservasi['status']='Data kosong';
      return json_encode((object)$reservasi);
    } else {
      $j=0;
      foreach ($reservasi['reservasi'] as $r) {
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
        $room = Room::where('room_id',$r['room_id'])->first()->toArray();
        $studio = Studio::where('studio_id',$room['studio_id'])->first()->toArray();
        $reservasi['reservasi'][$j]['room_nama'] = $room['room_nama'];
        $reservasi['reservasi'][$j]['studio_nama'] = $studio['studio_nama'];
        $reservasi['reservasi'][$j]['jadwal'] = $jadwal_id;
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
    $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->get();
    // dd($reservasi);
    $output=(object)array();
    if($reservasi->count()>0){
      $reservasi = $reservasi->toArray();
      // dd($reservasi);
      $tanggal = $reservasi[0]['reservasi_tanggal'];
      $now = date('Y-m-d',strtotime('now'));
      $beda = date_diff(date_create($tanggal),date_create(date('Y-m-d',strtotime('now'))))->d;
      if($beda < 1){
        $output->code = '-2';
        $output->status = 'Reservasi tidak bisa dibatalkan, sudah melebihi batas pembatalan';
        return json_encode($output);
      } else {
        $amount_refund = $reservasi[0]['reservasi_tagihan'];
      }

      try {
        $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->update(['reservasi_status'=>"2", 'reservasi_refund'=>$amount_refund]);
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

  function getKontak(Request $request){
    $code=array();
    $reservasi_id = $request->input('reservasi_id');
    $room_id = Reservasi::where('reservasi_id',$reservasi_id)->first(['room_id'])->toArray()['room_id'];
    $studio_id = Room::where('room_id',$room_id)->first(['studio_id'])->toArray()['studio_id'];
    $studio = Studio::where('studio_id',$studio_id)->first(['studio_nama','studio_alamat','studio_telepon','studio_rekening'])->toArray();
    $output = (object)array();
    if(count($studio)>0){
      $output->code = '1';
      $output->status = 'Request berhasil';
      $output->studio = $studio;
      return json_encode($output);
    } else {
      $output->code = '0';
      $output->status = 'Request gagal';
      return json_encode($output);
    }
  }

  function showTransactionPage(){
    $room = array();
    $studio = array();
    $city = array();
    $tmp_std = array();
    $hak = Session::get('hak');
    $user_id = Session::get('id');
    $jadwal = Jadwal::get()->toArray();
    $city_raw = City::withTrashed()->get()->toArray();
    if($hak == 'ADMIN_ZUPER'){
      try {
        $studio_id = Studio::withTrashed()->get(['studio_id','studio_nama','city_id'])->toArray();
        //inisiasi studio, city, tmp_std
        foreach ($studio_id as $s) {
          $studio[$s['studio_id']]=$s['studio_nama'];
          $city[$s['studio_id']]=$city_raw[$s['city_id']];
          array_push($tmp_std, array('studio_id'=>$s['studio_id']));
        }
        $room_id = Room::whereIn('studio_id',$tmp_std)->get(['room_id','room_nama'])->toArray();
        $reservasi = Reservasi::where('reservasi_status',1)->orWhere('reservasi_status',2)->get()->toArray();
      } catch (Exception $e) {
        $code['c']=0;
        $code['m']='Request Gagal';
        Session::flash('msg',$code);
        return Redirect::back();
      }
    } elseif($hak == 'ADMIN_STUDIO'){
      try {
        $studio_id = Studio::withTrashed()->where('user_id',$user_id)->get(['studio_id','studio_nama','city_id'])->toArray();
        //inisiasi studio, city, tmp_std
        foreach ($studio_id as $s) {
          $studio[$s['studio_id']]=$s['studio_nama'];
          $city[$s['studio_id']]=$city_raw[$s['city_id']];
          array_push($tmp_std, array('studio_id'=>$s['studio_id']));
        }
        $room_id = Room::whereIn('studio_id',$tmp_std)->get(['room_id','room_nama'])->toArray();
        unset($tmp_std);
        $tmp_std = array();
        foreach ($room_id as $r) {
          array_push($tmp_std,array('room_id'=>$r['room_id']));
        }
        $reservasi = Reservasi::whereIn('room_id',$tmp_std)->where('reservasi_status',1)
                                 ->orWhere('reservasi_status',2)->get()->toArray();
      } catch (Exception $e) {
        $code['c']=0;
        $code['m']='Request Gagal';  
        Session::flash('msg',$code);
        return Redirect::back();
      }
    }

    try{
      //memasukkan room ke array
      // $room_id = Room::whereIn('studio_id',$tmp_std)->get(['room_id','room_nama'])->toArray();
      foreach ($room_id as $r) {
        $room[$r['room_id']]=$r['room_nama'];
      }
      $user_raw = array();
      $i=0;
      foreach ($reservasi as $r) {
        $detail_raw = ReservasiDetail::where('reservasi_id',$r['reservasi_id'])->get()->toArray();
        $detail = array();
        foreach ($detail_raw as $d) {
          $detail[$d['reservasi_detail_id']]=$jadwal[$d['jadwal_id']-1]['jadwal_start'].'-'.$jadwal[$d['jadwal_id']-1]['jadwal_end'];
        }
        $reservasi[$i]['detail']=$detail;
        array_push($user_raw, $r['user_id']);
        $i++;
      }
      $user = User::whereIn('user_id',$user_raw)->get()->toArray();
      $users = array();
      foreach ($user as $u) {
        $users[$u['user_id']]['name']=$u['user_name'];
        $users[$u['user_id']]['email']=$u['user_email'];
      }
    } catch (Exception $e){
        echo $e;exit;
      $code['c']=0;
      $code['m']='Request Gagal';  
      Session::flash('msg',$code);
      return Redirect::back();
    }
    return view('transaksi.index')->with(['reservasi'=>$reservasi,'user'=>$users,'room'=>$room,'studio'=>$studio, 'city'=>$city]);
  }
}