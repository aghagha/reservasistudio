<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    function getJadwal(){
    	$jadwal = Jadwal::all();
    	$sorted = array();
    	foreach ($jadwal as $j) {
    		$sorted[$j['jadwal_id']]=$j['jadwal_start']."-".$j['jadwal_end'];
    	}
    	return $sorted;
    }
}
