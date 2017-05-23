<?php

namespace App\Http\Controllers;
use Hash;

use Illuminate\Http\Request;
use Illuminate\Http\Input;
use App\User;
use \Exception;
use Session;
use Redirect;

class UserController extends Controller
{
    function register(Request $request){
      // dd($request->all());
      $tipe_user = $request->input('tipe_user');
      $user_name = $request->input('user_name');
      $user_email = $request->input('user_email');
      $user_password = $request->input('user_password');
      $user_hp = $request->input('user_hp');
      $web = $request->input('web');

      $output = (object)array();
      $count = User::where('user_email','=',$user_email)->get();
      if($count->count() >= 1){
        $output->code = "-1";
        $output->status = "Email sudah dipakai";
        return json_encode($output);
      }

      $user = new User;
      $user->tipe_user_id = $tipe_user;
      $user->user_name = $user_name;
      $user->user_email = $user_email;
      $user->user_password = hash('md5', $user_password);
      $user->user_hp = $user_hp;
      try {
        $user->save();
        $output->user = $user;
        $output->code = "1";
        $output->status = "Registrasi berhasil";
        if($web != '')return json_encode($output);
        else {
          $code = array();
          $code['c']=1;
          $code['m']='User registered';
          Session::flash('msg',$code);
          return Redirect::back();
        }
      } catch (Exception $e) {
	      $error = (object)array();
        $error->code = "0";
        $error->status = "Registrasi gagal";
        if($web != '')return json_encode($error);
        else {
          $code['c']=0;
          $code['m']='Input new user failed';
          Session::flash('msg',$code);
          return Redirect::back();
        }
      }
    }

    function login(Request $request){
      $user_email = $request->input('user_email');
      $user_password = $request->input('user_password');

      $output = (object)array();
      if($user_email == NULL || $user_password == NULL) {
        $output->code = "-1";
        $output->status = "Email dan/atau password kosong";
        return json_encode($output);
      }

      $user = User::where('user_email','=',$user_email)
                    ->where('user_password','=',hash('md5', $user_password))
                    ->get();
      if($user->count()>=1){
        $user[0]->user_id = (string)$user[0]->user_id;
        $user[0]->tipe_user_id = (string)$user[0]->tipe_user_id;
        $output->user = $user[0];
        $output->code = "1";
        $output->status = "Login berhasil";
        return json_encode($output);
      } else {
        $output->code = "0";
        $output->status = "Kombinasi email dan password salah";
        return json_encode($output);
      }
    }

    function view(Request $request){
      $output = (object)array();
      $user_id = $request->input('user_id');
      try {
        $user = User::where('user_id',$user_id)->first();
        $output->user = $user;
        $output->code = '1';
        $output->status = 'berhasil menampilkan data user';
      } catch (Exception $e) {
        $output->code = '0';
        $output->status = 'Gagal menampilkan data user';
      }
      return json_encode($output);
    }

    function edit(Request $request){
      $output = (object)array();
      $user_id = $request->input('user_id');
      $user_name = $request->input('user_name');
      $user_email = $request->input('user_email');
      $user_password = $request->input('user_password');
      $user_new_password = $request->input('user_new_password');
      if($user_new_password == null)$user_new_password=$user_password;
      $user_hp = $request->input('user_hp');
      $old_password = User::where('user_id',$user_id)->first()->user_password;
      if(hash('md5', $user_password) != $old_password){
        $output->code = '-1';
        $output->status = 'Password lama tidak cocok';
        return json_encode($output);
      }
      try {
        $user = User::where('user_id',$user_id)->update(['user_name'=>$user_name,
                                                         'user_email'=>$user_email,
                                                         'user_password'=>hash('md5', $user_new_password),
                                                         'user_hp'=>$user_hp]);
        $output->code = '1';
        $output->status = 'Berhasil mengupdate profile';
      } catch (Exception $e) {
        $output->code = '0';
        $output->status = 'Gagal mengupdate profile';
      }
      return json_encode($output);
    }

    function delete(Request $request){
      $code = array();
      $user_id = $request->input('user_id');
      $user = User::where('user_id',$user_id);
      try{
        $user->delete();
        $code['c']=1;
        $code['m']="User deleted!";
      } catch(Exception $e){
        $code['c']=0;
        $code['m']="Failed to delete user...";
      } 
      Session::flash('msg',$code);
      return Redirect::back();
    }

    function showListPage(Request $request){
      $user = User::where('tipe_user_id','!=','3')->get()->toArray();
      return view('user.index')->with('user',$user);
    }

    function showAddPage(Request $request){
      return view('user.add');
    }

    function showEditPage(Request $request){
      $user_id = Session::get('id');
      $user = User::where('user_id',$user_id)->first()->toArray();
      return view('user.edit')->with(['user'=>$user]);
    }

    function editWeb(Request $request){
      $code = array();
      $user_id = $request->input('user_id');
      $user_name = $request->input('user_name');
      $user_email = $request->input('user_email');
      $user_hp = $request->input('user_hp');
      $user_new_password= $request->input('user_new_password');
      $user_new_password2 = $request->input('user_new_password2');
      $test = $request->input('test');
      $user_password = $request->input('user_password');
      if(hash('md5', $user_password)!=$test){
        $code['c'] = '0';
        $code['m'] = 'Old password does not match';
        Session::flash('msg',$code);
        return Redirect::back();
      }
      else if($user_new_password != $user_new_password){
        $code['c'] = '0';
        $code['m'] = 'New password does not match';
        Session::flash('msg',$code);
        return Redirect::back(); 
      }
      else {
        if($user_new_password!='')$input = $user_new_password;
        else $input = $user_password;
        try{
          $user = User::where('user_id',$user_id)->update(['user_name'=>$user_name,
                                                      'user_email'=>$user_email,
                                                      'user_hp'=>$user_hp,
                                                      'user_password'=>hash('md5', $input)]);
          $code['c'] = '1';
          $code['m'] = 'Changes succesfully saved!';
        } catch(Exception $e){
          $code['c'] = '1';
          $code['m'] = 'Changes could not be made!';
        }
      }
      
      Session::flash('msg',$code);
      return Redirect::back(); 
    }

    function registerweb(Request $request){
      $code = array();
      $user_name = $request->input('user_name');
      $user_email = $request->input('user_email');
      $user_hp = $request->input('user_hp');
      $user_new_password= $request->input('user_new_password');
      $user_new_password2 = $request->input('user_new_password2');
      $user_password = $request->input('user_password');
      if(Session::get('hak')=='ADMIN_ZUPER'){
        $user = User::where('user_id',Session::get('id'))->first();
      } else {
        $code['c']=-1;
        $code['m']='Not authorized';
        Session::flash('msg',$code);
        return Redirect::back();
      }
      $user->toArray();
      $password = $user['user_password'];
      if(hash('md5', $user_password)!=$password || $user_new_password!=$user_new_password2){
        $code['c']=0;
        $code['m']='Failed to add user';
        Session::flash('msg',$code);
        return Redirect::back();
      }
      $user = new User;
      $user->tipe_user_id = '2';
      $user->user_name = $user_name;
      $user->user_email = $user_email;
      $user->user_password = hash('md5', $user_new_password);
      $user->user_hp = $user_hp;
      try {
        $user->save();
      } catch (Exception $e) {
        $code['c']=0;
        $code['m']='Failed to add user';
        Session::flash('msg',$code);
        return Redirect::back();
      }
      $code['c']=1;
      $code['m']='User added';
      Session::flash('msg',$code);
      return Redirect::back();
    }

    //////////////
    function testTA(Request $request){
      return json_encode($request->nama);
    }
    /////////////
}
