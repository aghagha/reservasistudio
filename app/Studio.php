<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $table = 'studios';
	use SoftDeletes;
    protected $dates = ['deleted_at'];

    function getStudio($arr)
    {
    	$studio = Studio::withTrashed()->whereIn('studio_id',$arr)->get()->toArray();
    	$sorted = array();
    	foreach ($studio as $s) {
    		$sorted[$s['studio_id']]=$s['studio_nama'];
    	}
    	return $sorted;
    }
}
