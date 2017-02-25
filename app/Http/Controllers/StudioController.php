<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Studio;
use App\User;
use App\City;
use Redirect;
use Session;
use Input;

class StudioController extends Controller
{
    public function showAddPage(Request $request){
        $user_raw = User::where('tipe_user_id','2')->get()->toArray();
        $user = array();
        foreach ($user_raw as $u) {
            $user[$u['user_id']]=$u['user_name'];
        }
        $city_raw = City::get()->toArray();
        $city = array();
        foreach ($city_raw as $c) {
            $city[$c['city_id']]=$c['city_name'];
        }
        return view('studio.entri')->with(['user'=>$user, 'city'=>$city]);
    }

    public function showEditPage($studio_id){
        $studio = Studio::where('studio_id',$studio_id)->first()->toArray();
        $user_raw = User::where('tipe_user_id','2')->get()->toArray();
        $user = array();
        foreach ($user_raw as $u) {
            $user[$u['user_id']]=$u['user_name'];
        }
        $city_raw = City::get()->toArray();
        $city = array();
        foreach ($city_raw as $c) {
            $city[$c['city_id']]=$c['city_name'];
        }
        return view('studio.edit')->with(['studio'=>$studio,'city'=>$city, 'user'=>$user]);
    }

    public function showListPage(Request $request){
        $hak = Session::get('hak');
        $city = array();
        $city_raw = City::get()->toArray();
        foreach ($city_raw as $c) {
            $city[$c['city_id']]=$c['city_name'];
        }
        if($hak == 'ADMIN_ZUPER'){
            $studio = Studio::get()->toArray();
        } elseif($hak == 'ADMIN_STUDIO'){
            $user_id = Session::get('id');
            $studio = Studio::where('user_id',$user_id)->get()->toArray();
        }
        return view('studio.list')->with(['studio'=>$studio,'city'=>$city]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'user_id'=> 'required',
            'city_id'=> 'required',
            'studio_nama'=> 'required',
            'studio_alamat'=> 'required',
            'studio_telepon'=> 'required',
            'studio_rekening'=> 'required',
        ]);
        $user_id = $request->get('user_id');
        $city_id = $request->get('city_id');
        $studio_nama = $request->get('studio_nama');
        $studio_alamat = $request->get('studio_alamat');
        $studio_telepon = $request->get('studio_telepon');
        $studio_rekening = $request->get('studio_rekening');
        // $studio_open_hour = $request->get('studio_open_hour');
        // $studio_close_hour = $request->get('studio_close_hour');
        $code = array();
        $studio = new Studio();
        $studio->user_id = $user_id;
        $studio->city_id = $city_id;
        $studio->studio_nama = $studio_nama;
        $studio->studio_alamat = $studio_alamat;
        $studio->studio_telepon = $studio_telepon;
        $studio->studio_rekening = $studio_rekening;
        $studio->studio_open_hour = '08.00';
        $studio->studio_close_hour = '24.00';
        try {
            $studio->save();
            $code['c'] = 1;
            $code['m'] = 'Entry successfull!';
        } catch (Exception $e) {
            $code['c'] = 0;
            $code['m'] = 'Entry failed...';
        }
        Session::flash('msg',$code);
        return Redirect::back();
    }

    public function edit(Request $request){
        $code = array();
        
        $studio_id = $request->get('studio_id');
        $user_id = $request->get('user_id');
        $city_id = $request->get('city_id');
        $studio_nama = $request->get('studio_nama');
        $studio_alamat = $request->get('studio_alamat');
        $studio_telepon = $request->get('studio_telepon');
        $studio_rekening = $request->get('studio_rekening');
        if($studio_nama == '' || $studio_alamat == '' || $studio_telepon == '' || $studio_rekening == ''){
            $code['c']=-1;
            $code['m']='There are empty fields';
            Session::flash('msg',$code);
            return Redirect::back();
        }
        // $studio_open_hour = '08.00';//$request->get('studio_open_hour');
        // $studio_close_hour = '24.00';//$request->get('studio_close_hour');
        try {
            $studio = Studio::where('studio_id',$studio_id)->update(['city_id'=>$city_id,
                                                                     'studio_nama'=>$studio_nama,
                                                                     'user_id'=>$user_id,
                                                                     'studio_alamat'=>$studio_alamat,
                                                                     'studio_telepon'=>$studio_telepon,
                                                                     'studio_rekening'=>$studio_rekening]);   
            $code['c']=1;
            $code['m']='Update Successfull!';
        } catch (Exception $e) {
            $code['c']=0;
            $code['m']='Update Failed...';
        }
        Session::flash('msg',$code);
        return Redirect::back();
    }

    public function delete($studio_id){
        $studio = Studio::where('studio_id',$studio_id);
        try {
            $studio->delete();
            $code['c'] = 1;
            $code['m'] = 'Studio deleted!';
        } catch (Exception $e) {
            $code['c'] = 0;
            $code['m'] = 'Delete failed...';
        }
        Session::flash('msg',$code);
        return Redirect::back();
    }

    public function getKontak(Request $request){
    	$studio_id = $request->get('studio_id');
    	$studio = Studio::where('studio_id',$studio_id)->first(['studio_nama','studio_alamat','studio_telepon','studio_rekening'])->toArray();
    	$output = (object)array();
    	if(count($studio)>0){
    		$output->code = '1';
    		$output->status = 'Request berhasil';
    		$output->studio = $studio;
    		return json_encode($output);
    	} else {
    		$output->code = '0';
    		$output->status = 'Request gagal';
    		return json_encode($output);
    	}
    }
}
