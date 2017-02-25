<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // use Notifiable;

    protected $table = 'users';
    
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
    function scopechecklogin($query, $data)
    {
        return $query->where('user_email','=',$data['user_email'])
                    ->where('user_password','=',$data['user_password']);
    }
}
