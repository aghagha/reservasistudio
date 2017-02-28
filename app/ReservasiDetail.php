<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservasiDetail extends Model
{
    protected $table = 'reservasi_details';

    function getDetail($arr,$jadwal){
    	$detail = ReservasiDetail::whereIn('reservasi_id',$arr)->get()->toArray();
    	$sorted = array();
    	foreach ($detail as $s) {
    		if(!isset($sorted[$s['reservasi_id']])){
    			$sorted[$s['reservasi_id']] = $jadwal[$s['jadwal_id']];
    		} else {
    			$sorted[$s['reservasi_id']] = $sorted[$s['reservasi_id']].', '.$jadwal[$s['jadwal_id']];
    		}
    	}
    	return $sorted;
    }
}
