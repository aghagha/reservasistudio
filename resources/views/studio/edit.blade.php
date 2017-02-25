@extends('adminlte::page')

@section('title', 'Chamber - Add Stduio')

@section('content_header')
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Edit Studio</b></h3>
        </div>
        {{Form::open(['url'=>'studio/update','method'=>'POST','role'=>'form'])}}
                  <div class="box-body">
                    @if(count($errors)>0)
                      <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                    @endif
                    @if (Session::has('msg'))
                      @if(Session::get('msg')['c'] == '1')
                        <div class="alert alert-info">{{ Session::get('msg')['m'] }}</div>
                      @else
                        <div class="alert alert-danger">{{ Session::get('msg')['m'] }}</div>
                      @endif
                    @endif   
                    @if(Session::get('hak')=='ADMIN_ZUPER')
                    <div class="form-group">
                      <label for="user_studio">User</label>
                      {{Form::select('user_id',$user,$studio['user_id'],['class'=>'form-control','placeholder'=>'Whose studio this will be?'])}}
                    </div>
                    @else
                      {{Form::hidden('user_id',$studio['user_id'])}}
                    @endif
                    <div class="form-group">
                      <label for="nama_studio">Name</label>
                      {{Form::text('studio_nama',$studio['studio_nama'],['class'=>'form-control','id'=>'nama_studio','placeholder'=>'Enter a name for the studio'])}}
                    </div>
                    <div class="form-group">
                      <label for="kota_studio">City</label>
                      {{Form::select('city_id',$city,$studio['city_id'],['class'=>'form-control','placeholder'=>'Where is the studio located?'])}}
                    </div>
                    <div class="form-group">
                      <label for="alamat_studio">Address detail</label>
                      {{Form::text('studio_alamat',$studio['studio_alamat'],['class'=>'form-control','id'=>'alamat_studio','placeholder'=>"Enter studio's address detail"])}}
                    </div>
                    <div class="form-group">
                      <label for="telepon_studio">Phone number</label>
                      {{Form::text('studio_telepon',$studio['studio_telepon'],['class'=>'form-control','id'=>'telepon_studio','placeholder'=>"Enter studio's phone number"])}}
                    </div>
                    <div class="form-group">
                      <label for="bank_studio">Bank Account</label>
                      {{Form::textarea('studio_rekening',$studio['studio_rekening'],['class'=>'form-control','id'=>'bank_studio','placeholder'=>"Enter studio's bank detail for payment, example : 'BNI 123455677 a/n anonim'"])}}
                    </div>
                  </div>
                  {{Form::hidden('studio_id',$studio['studio_id'])}}
                  <div class="box-footer">
                    {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
                  </div>
                {{Form::close()}}
      </div>
    </div>
  </div>
@stop