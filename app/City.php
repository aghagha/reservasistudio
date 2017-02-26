<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
