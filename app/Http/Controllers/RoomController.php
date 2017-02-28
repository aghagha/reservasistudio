<?php

namespace App\Http\Controllers;
use App\Room;
use Session;
use Redirect;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    function store(Request $request){
    	$code = array();
    	$name = $request->get('room_name');
    	$price = $request->get('room_harga');
    	$studio = $request->get('studio_id');
    	$room = new Room;
    	$room->room_nama = $name;
    	$room->room_harga = $price;
    	$room->studio_id = $studio;
    	try {
    		$room->save();
    		$code['c']=1;
    		$code['m']="Room added!";
    	} catch (Exception $e) {
    		$code['c']=0;
    		$code['m']="Failed to add room...";
    	}
    	Session::flash('msg',$code);
    	return Redirect::back();
    }

    function update(Request $request){
    	$code = array();
    	$name = $request->get('room_nama');
    	$price = $request->get('room_harga');
    	$room_id = $request->get('room_id');
    	try {
    		Room::where('room_id',$room_id)->update(['room_nama'=>$name,'room_harga'=>$price]);
    		$code['c']=1;
    		$code['m']="Changes saved!";
    	} catch (Exception $e) {
    		$code['c']=0;
    		$code['m']="Failed to save changes...";
    	}
    	Session::flash('msg',$code);
    	return Redirect::back();
    }

    function delete(Request $request){
    	$code = array();
    	$room_id = $request->get('room_id');
    	try {
    		Room::where('room_id',$room_id)->delete();
    		$code['c']=1;
    		$code['m']="Room deleted!";
    	} catch (Exception $e) {
    		$code['c']=0;
    		$code['m']="Delete room failed...";
    	}
    	Session::flash('msg',$code);
    	return Redirect::back();
    }
}
