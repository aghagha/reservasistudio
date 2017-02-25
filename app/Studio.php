<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $table = 'studios';
	use SoftDeletes;
    protected $dates = ['deleted_at'];
}
