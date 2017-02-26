@extends('adminlte::page')

@section('title', 'Chamber - Add Stduio')

@section('content_header')
@stop

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><b>Add City</b></h3>
				</div>
				{{Form::open(['url'=>'studio/storecity','method'=>'POST','role'=>'form'])}}
                	<div class="box-body">
                		<div class="row">
                      		<div class="col-md-8">
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
		                			<label for="nama_city">City Name</label>
		                			{{Form::textarea('city_name','',['class'=>'form-control','id'=>'nama_city','placeholder'=>"Enter the city's name, use comma to add another city, ex :'Bandung, Jogjakarta, Medan'"])}}
		                		</div>
		                		{{Form::submit('Submit',['class'=>'btn btn-primary'])}}
		                	</div>
		                </div>
                	</div>
                {{Form::close()}}
			</div>
		</div>
	</div>
@stop