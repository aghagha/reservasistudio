<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'u'],function (){
  Route::post('register', 'UserController@register');
});

Route::group(['prefix'=>'j'],function (){
  Route::post('jadwal', 'JadwalController@lihatJadwal');
});

Route::group(['prefix'=>'r'],function (){
  Route::post('store', 'ReservasiController@store');
  Route::post('edit', 'ReservasiController@edit');
});

Route::group(['prefix'=>'studio'],function (){

});
