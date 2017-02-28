<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    function getCity()
    {
    	$city = City::withTrashed()->get()->toArray();
    	$sorted = array();
    	foreach ($city as $s) {
    		$sorted[$s['city_id']]=$s['city_name'];
    	}
    	return $sorted;
    }
}
