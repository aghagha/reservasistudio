<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Input;
use App\User;
use \Exception;

class UserController extends Controller
{
    function register(Request $request){

      $tipe_user = $request->input('tipe_user');
      $user_name = $request->input('user_name');
      $user_email = $request->input('user_email');
      $user_password = $request->input('user_password');
      $user_hp = $request->input('user_hp');

      $user = new User;
      $user->tipe_user_id = $tipe_user;
      $user->user_name = $user_name;
      $user->user_email = $user_email;
      $user->user_password = hash('md5',$user_password);
      $user->user_hp = $user_hp;
      try {
        $user->save();
      } catch (Exception $e) {
        return "Gagal input data";
      }
    }
}
