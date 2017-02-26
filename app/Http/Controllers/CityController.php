<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use Session;
use Redirect;

class CityController extends Controller
{
   function index()
   {
   		$city = City::get()->toArray();
   		return view('city.index')->with(['city'=>$city]);
   }

   function showAddPage()
   {
   		return view('city.add');
   }

   function store(Request $request)
   {
   		$code = array();
   		$city_list = $request->get('city_name');
   		$city = explode(',', $city_list);
   		$data = array();
   		foreach ($city as $c) {
   			$tmp = array(
   				'city_name' => $c,
   			);
   			array_push($data, $tmp);
   			unset($tmp);
   		}
   		try {
   			City::insert($data);
   			$code['c'] = 1;
   			$code['m'] = 'Isertion success!';
   		} catch (Exception $e) {
   			$code['c'] = 0;
   			$code['m'] = 'Insertion failed...';
   		}
   		Session::flash('msg',$code);
   		return Redirect::back();
   }

   function edit(Request $request)
   {
   		$code = array();
   		$city_id = $request->get('city_id');
   		$city_name = $request->get('city_name');
   		try {
   			City::where('city_id',$city_id)->update(['city_name'=>$city_name]);
   			$code['c'] = 1;
   			$code['m'] = 'Edit success!';
   		} catch (Exception $e) {
   			$code['c'] = 0;
   			$code['m'] = 'Edit failed...';
   		}
   		Session::flash('msg',$code);
   		return Redirect::back();
   }

   function delete(Request $request){
   		$city_id = $request->get('city_id');
   		$code = array();
   		try {
   			$city = City::where('city_id',$city_id);
   			$city->delete();
   			$code['c'] = 1;
   			$code['m'] = 'City deleted!';
   		} catch (Exception $e) {
   			$code['c'] = 0;
   			$code['m'] = 'Delete failed...';
   		}
   		Session::flash('msg',$code);
   		return Redirect::back();
   }
}
