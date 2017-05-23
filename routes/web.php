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
  Route::post('newjadwal', 'JadwalController@getNewJadwal');
});

Route::group(['prefix'=>'r'],function (){
  Route::post('store', 'ReservasiController@store');
  Route::post('edit', 'ReservasiController@edit');
  Route::post('cancel', 'ReservasiController@cancel');
  Route::post('issue', 'ReservasiController@issue');
  Route::post('history', 'ReservasiController@history');
  Route::post('kontak', 'ReservasiController@getKontak');
});

Route::group(['prefix'=>'web'],function(){
  //action form login
  Route::post('login', 'LoginController@login');
  //action form logout
  Route::post('logout', 'LoginController@logout');
});

Route::group(['prefix'=>'studio','middleware'=>['admincheck']],function (){
  Route::get('dashboard_studio','HomeController@indexstudio');

  Route::get('add','StudioController@showAddPage');
  Route::post('create',['as'=>'studio.store','uses'=>'StudioController@store']);

  Route::get('city','CityController@index');
  Route::get('addcity','CityController@showAddPage');
  Route::post('storecity','CityController@store');
  Route::post('editcity','CityController@edit');
  Route::post('deletecity','CityController@delete');

  Route::get('user','UserController@showListPage');
  Route::get('adduser','UserController@showAddPage');
  Route::post('deleteuser','UserController@delete');
  Route::post('newuser','UserController@registerweb');
});

Route::group(['prefix'=>'studio','middleware'=>['studiocheck']],function(){
  Route::get('dashboard','HomeController@index');

  Route::get('edit/{studio_id}',['as'=>'studio.edit','uses'=>'StudioController@showEditPage']);
  Route::post('update',['as'=>'studio.update','uses'=>'StudioController@edit']);

  Route::get('admin','UserController@showEditPage');
  Route::post('edituser','UserController@editweb');

  Route::get('list','StudioController@showListPage');
  Route::post('delete',['as'=>'studio.delete','uses'=>'StudioController@delete']);

  Route::post('addroom',['as'=>'studio.addroom','uses'=>'RoomController@store']);
  Route::post('editroom','RoomController@update');
  Route::post('delroom','RoomController@delete');

  Route::get('issue',['as'=>'studio.issue','uses'=>'ReservasiController@showIssuePage']);
  Route::post('detailreservasi','ReservasiController@getdetail');
  Route::get('transaction',['as'=>'studio.transaction','uses'=>'ReservasiController@showTransactionPage']);

  Route::get('refundpayment/{id}','ReservasiController@issueRefund');

  Route::post('report','TransactionController@export');
});

Auth::routes();

//Route::get('/home', 'HomeController@index');
