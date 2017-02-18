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
      for ($i=0; $i < (4+$t); $i++) { 
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
                $jadwal[$i][$tanggal][$r['room_id']]=$tmp_j;
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
}
