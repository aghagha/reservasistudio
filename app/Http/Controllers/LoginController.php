<?php

namespace App\Http\Controllers;
use Hash;
use Input;
use Session;
use Redirect;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    //
    public function login(Request $request){

    	$data = Input::all();
    	$data['user_password'] = hash('md5', $data['user_password']);
    	$user = User::checkLogin($data)->first();
    	if(!is_null($user)){
			$user=$user->toArray();
		}
		if(count($user)>0){
			if($user['tipe_user_id'] == 2){
				Session(['user'=>$user,'id'=>$user['user_id'],'hak'=>'ADMIN_STUDIO']);
			} elseif($user['tipe_user_id'] == 3) {
				Session(['user'=>$user,'id'=>$user['user_id'],'hak'=>'ADMIN_ZUPER']);
			} else {
				return Redirect::back()->withErrors(array('email'=>' ','password'=>'Wrong email/password combination'));
			}
			// dd(Session::all());
			return redirect('studio/dashboard');
		} else {
			return Redirect::back()->withErrors(array('email'=>' ','password'=>'Wrong email/password combination'));
		}
    }
    public function logout()
	{
		Session::forget('hak');
		Session::forget('name');
		Session::forget('id');
		return Redirect::to('login');
	}
}
