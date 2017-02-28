@extends('adminlte::page')

@section('title', 'Chamber - Add Studio User')

@section('content_header')
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Add User</b></h3>
        </div>
        {{Form::open(['url'=>'studio/newuser','method'=>'POST','role'=>'form'])}}
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
                    <div class="form-group">
                      <label for="nama_user">Name</label>
                      {{Form::text('user_name','',['class'=>'form-control','id'=>'nama_user','placeholder'=>"Enter a name for this user"])}}
                    </div>
                    <div class="form-group">
                      <label for="email_user">Email</label>
                      {{Form::text('user_email','',['class'=>'form-control','id'=>'email_user','placeholder'=>"Enter this user's email"])}}
                    </div>
                    <div class="form-group">
                      <label for="telepon_user">Phone number</label>
                      {{Form::text('user_hp','',['class'=>'form-control','id'=>'telepon_user','placeholder'=>"Enter this user's phone number"])}}
                    </div>
                    <div class="form-group">
                      <label for="new_pw2">New Password</label>
                      {{Form::password('user_new_password',['class'=>'form-control','id'=>'new_pw','placeholder'=>"Enter a new password"])}}
                    </div>
                    <div class="form-group">
                      <label for="new_pw2">Confirm New Password</label>
                      {{Form::password('user_new_password2',['class'=>'form-control','id'=>'new_pw2','placeholder'=>"Confirm the new password"])}}
                    </div>
                    <div class="form-group">
                      <label for="password_user">Enter Your Password to Add this User</label>
                      {{Form::password('user_password',['class'=>'form-control','id'=>'password_user','placeholder'=>"Enter your password to confirm"])}}
                    </div>
                  </div>
                  <div class="box-footer">
                    {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
                  </div>
                {{Form::close()}}
      </div>
    </div>
  </div>
@stop