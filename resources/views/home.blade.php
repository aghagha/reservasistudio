@extends('adminlte::page')

@section('title', 'Chamber')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-green"><i class="fa fa-bar-chart"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Today's Reservation</span>
				<span class="info-box-number">{{$booking}} <small>reservation(s)</small></span>
            </div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Today's Omzet</span>
				<span class="info-box-number"><small>IDR </small>{{$omzet}}</span>
            </div>
		</div>
	</div>
</div>
@stop