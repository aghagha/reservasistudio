<?php

namespace App\Http\Controllers;

use App\Reservasi;
use App\Studio;
use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
 
    public function index()
    {
    	if(Session::get('hak') == 'ADMIN_ZUPER'){
    		// date('Y-m-d')
    		$reservasi = Reservasi::where('reservasi_tanggal',date('Y-m-d'))->where('reservasi_status','1')->get();
    	} else {
    		$user_id = Session::get('id');
    		$studio = Studio::where('user_id',$user_id)->get()->toArray();
    		$i = 0; $s_id = array();
    		foreach ($studio as $s) {
    			$s_id[$i]['studio_id']=$s['studio_id'];
    			$i++;
    		}
    		$reservasi = Reservasi::whereIn('studio_id',$s_id)->where('reservasi_tanggal',date('Y-m-d'))->where('reservasi_status','1')->get();
    	}
    	$booking = $reservasi->count();
		$reservasi = $reservasi->toArray();
		$omzet = 0;
		foreach ($reservasi as $r) {
			$omzet+=(int)$r['reservasi_tagihan'];
		}

        return view('home',['booking'=>$booking,'omzet'=>$omzet]);
    }

    public function indexstudio()
    {
    	
        return view('studio_home');
    }
}
