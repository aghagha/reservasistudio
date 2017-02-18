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

Route::get('/tes', function () {
    return 'test';
});

Route::post('/register', 'UserController@register');

Route::group(['prefix'=>'u'],function (){
  Route::post('register', 'UserController@register');
  Route::post('login', 'UserController@login');
  Route::post('view', 'UserController@view');
  Route::post('edit', 'UserController@edit');
});

Route::group(['prefix'=>'j'],function (){
  Route::get('kotastudio', 'JadwalController@lihatKotaStudio');
  Route::post('jadwal', 'JadwalController@lihatJadwal');
});

Route::group(['prefix'=>'r'],function (){
  Route::post('store', 'ReservasiController@store');
  Route::post('edit', 'ReservasiController@edit');
  Route::post('cancel', 'ReservasiController@cancel');
  Route::post('issue', 'ReservasiController@issue');
  Route::post('history', 'ReservasiController@history');
});

Route::group(['prefix'=>'web'],function(){
  Route::get('/',function(){return view('welcome');});
});

Route::group(['prefix'=>'studio'],function (){

});
