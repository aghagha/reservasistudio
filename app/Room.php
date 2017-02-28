<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
	use SoftDeletes;
    protected $dates = ['deleted_at'];

    function getRoom($arr){
    	$room = Room::whereIn('room_id',$arr)->get()->toArray();
    	$sorted = array();
    	foreach ($room as $s) {
    		$sorted[$s['room_id']]=$s['room_nama'];
    	}
    	return $sorted;
    }
}
