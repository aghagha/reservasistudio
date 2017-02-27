<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Studio;
use App\Jadwal;
use App\Reservasi;
use App\Room;
use App\ReservasiDetail;
use \Exception;

class JadwalController extends Controller
{
    //lihat jadwal yang tersedia pada hari H
    function lihatKotaStudio(Request $request){
      $city = City::all();
      $studio = Studio::all();
      $output = (object)array();
      $output->city = $city;
      $output->studio = $studio;
      return json_encode($output);
    }

    function lihatJadwal(Request $request){
      $output = (object)array();
      $tanggal = $request->input('tanggal');
      $t =0;
      while (true) {
        $tanggal = date('Y-m-d',strtotime('-1 day', strtotime($tanggal)));
        if($tanggal < date('Y-m-d',strtotime('now'))){
          $tanggal = date('Y-m-d',strtotime('+1 day', strtotime($tanggal)));
          break;
        }
        $t++;
      }
      $studio_id = $request->input('studio_id');
      
      $room = Room::where('studio_id','=',$studio_id)->get()->toArray();
      $jadwal = array();
      $i = 0;
      $jjj = $t +4;
      for ($i=0; $i<$jjj; $i++) { 
        $reservasi = Reservasi::where('reservasi_tanggal',$tanggal)
                                ->where('reservasi_status','1')
                                ->get();
        
        $jadwal[$i]['tanggal']=$tanggal;
        if($reservasi->count() == 0){
          $j = Jadwal::all();
          $k = 0;
          $temp_room = array();
          foreach ($room as $r) {
            $temp_room[$k]['room_id']=$r['room_id'];
            $temp_room[$k]['jadwal']=$j;
            $temp_room[$k]['harga']=$r['room_harga'];
            $k++;
          }
          $jadwal[$i]['room']=$temp_room;
            // return json_encode($jadwal);
        } else {
          $reservasi = $reservasi->toArray();
          $reservasi_detail = array();
          $list_jadwal = Jadwal::all()->toArray();
          $k=0;
          $temp_room = array();
          foreach ($room as $r) {
            $temp_room[$k]['room_id']=$r['room_id'];
            $temp_room[$k]['harga']=$r['room_harga'];
            $reservasi = Reservasi::where('reservasi_tanggal','=',$tanggal)
                                    ->where('reservasi_status','1')
                                    ->where('room_id','=',$r['room_id'])
                                    ->get();
            if($reservasi->count() == 0){
              $temp_room[$k]['jadwal']=$list_jadwal;
              // $jadwal[$tanggal][$r['room_id']]=$list_jadwal;
            }
            else {
              foreach ($reservasi as $rsv) {
                $tmp = ReservasiDetail::where('reservasi_id','=',$rsv['reservasi_id'])
                                        ->get()->toArray();
                foreach ($tmp as $t) {
                  $reservasi_detail[$r['room_id']][$t['jadwal_id']]=false;
                }
                $tmp_j = array();
                foreach ($list_jadwal as $j) {
                  if(!array_key_exists($j['jadwal_id'],$reservasi_detail[$r['room_id']])){
                    array_push($tmp_j,$j);
                  }
                }
                $temp_room[$k]['jadwal']=$tmp_j;
                //$jadwal[$i][$tanggal][$r['room_id']]=$tmp_j;
              }  
            }
            $k++;
          } 
          $jadwal[$i]['room']=$temp_room;
        }
        if($jadwal[$i]==null){
          $jadwal[$i]['code'] = '0';
          $jadwal[$i]['status'] = 'Pencarian gagal atau tidak ditemukan';
        } else {
          $jadwal[$i]['code'] = '1';
          $jadwal[$i]['status'] = 'Pencarian berhasil';
        }
        $tanggal = date('Y-m-d',strtotime('+1 day', strtotime($tanggal)));
      }
      $output = (object)array();
      $output->list_jadwal = $jadwal;
      return json_encode($output);
    }

    function getNewJadwal(Request $request){
      /*the master plan:ndapetin semua jadwal yang available pada hari H booking dengan room yang sama, 
      lalu ditambah dengan jadwal yang sudah ada di detail reservasi*/
      $reservasi_id = $request->get('reservasi_id');
      $output = array();
      try {
        $reservasi = Reservasi::where('reservasi_id',$reservasi_id)->first()->toArray();
        $room = Room::where('room_id',$reservasi['room_id'])->first(['room_nama'])->toArray();
        $studio = Studio::withTrashed()->where('studio_id',$reservasi['studio_id'])->first(['studio_nama'])->toArray();
        $reservasi['room_nama']=$room['room_nama'];
        $reservasi['studio_nama']=$studio['studio_nama'];
        $tanggal = $reservasi['reservasi_tanggal'];
        $room_id = $reservasi['room_id'];
        $j_booked = ReservasiDetail::where('reservasi_id',$reservasi_id)->get()->toArray();
        $jumlah = ReservasiDetail::where('reservasi_id',$reservasi_id)->get()->count();
        $list_jadwal = Jadwal::get()->toArray();
        $jj_booked = array();
        foreach ($j_booked as $j) {
          array_push($jj_booked, $list_jadwal[$j['jadwal_id']]);
        }
        $jadwal=array();
        $reservasi_booked = Reservasi::where('room_id',$room_id)
                                        ->where('reservasi_tanggal',$tanggal)
                                        ->where('reservasi_id','!=',$reservasi_id)
                                        ->where('reservasi_status','1')
                                        ->get()
                                        ->toArray();
        if(count($reservasi_booked)==0){
          $jadwal = $list_jadwal;
        } else {
          $reservasi_sorted = array();
          $i=0;
          foreach ($reservasi_booked as $r) {
            $reservasi_sorted[$i]=$r['reservasi_id'];
            $i++;
          }
          $jadwal_booked = ReservasiDetail::whereIn('reservasi_id',$reservasi_sorted)->get()->toArray();
          $jadwal_sorted = array();
          $i=0;
          foreach ($jadwal_booked as $j) {
            $jadwal_sorted[$i]=$j['jadwal_id'];
            $i++;
          }
          $jadwal = Jadwal::whereNotIn('jadwal_id',$jadwal_sorted)->get()->toArray();
        }
        $object['code'] = '1';
        $object['message'] = 'Pencarian jadwal berhasil';
        $object['reservasi'] = $reservasi;
        $object['jumlah'] = $jumlah;
        $object['jadwal_lama'] = $jj_booked;
        $object['jadwal'] = $jadwal;
      } catch (Exception $e) {
        echo $e;
        $object['code'] = '0';
        $object['message'] = 'Pencarian jadwal gagal';
      }
      return json_encode($object);
    }
}
