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
  Route::get('dashboard','HomeController@index');
  Route::get('dashboard_studio','HomeController@indexstudio');

  Route::get('add','StudioController@showAddPage');
  Route::post('create',['as'=>'studio.store','uses'=>'StudioController@store']);

  Route::get('edit/{studio_id}',['as'=>'studio.edit','uses'=>'StudioController@showEditPage']);
  Route::post('update',['as'=>'studio.update','uses'=>'StudioController@edit']);
});

Route::group(['prefix'=>'studio','middleware'=>['studiocheck']],function(){
  Route::get('list','StudioController@showListPage');
  Route::get('delete/{studio_id}',['as'=>'studio.delete','uses'=>'StudioController@delete']);

  Route::get('transaction',['as'=>'studio.transaction','uses'=>'ReservasiController@showTransactionPage']);
});

Auth::routes();

//Route::get('/home', 'HomeController@index');
