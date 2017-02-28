@extends('adminlte::page')

@section('title', 'Chamber - Confirmation Payment')

@section('content_header')

@stop

@section('content')
    <?php
      $status = array(
          "0" => "Booked",
          "1" => "Confirmed",
          "2" => "Canceled",
          "3" => "Failed",
        );
    ?>
    <div class="row">
        <div class="col-md-12">
          @if (Session::has('msg'))
              @if(Session::get('msg')['c'] == '1')
                  <div class="alert alert-info">{{ Session::get('msg')['m'] }}</div>
              @else
                  <div class="alert alert-danger">{{ Session::get('msg')['m'] }}</div>
              @endif
          @endif
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Confirmation Payment</b></h3>
                </div>
                {{Form::open(['url'=>'studio/detailreservasi','method'=>'POST','role'=>'form'])}}
                <div class="box-body">
                    <div class="form-group">
                      <label for="nama_studio col-md-3">Reservation Code</label>
                      {{Form::text('reservasi_nomor_booking','',['class'=>'form-control col-md-9','id'=>'nama_studio','placeholder'=>'Enter the reservation number'])}}
                    </div>
                </div>
                <div class="box-footer">
                  {{Form::submit('Check',['class'=>'btn btn-primary'])}}
                </div>
                {{Form::close()}}
            </div>
            @if(Session::has('msg'))
            @if(isset(Session::get('msg')['s']) && Session::get('msg')['s']=="oke")
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Reservation Detail</b></h3>
                </div>
                <div class="box-body">
                  <h3>{{$reservasi['reservasi_nomor_booking']}}</h3>
                  <div class="row">
                    <div class="col-md-3">
                      Studio
                    </div>
                    <div class="col-md-9">
                      {{$data['studio']}}, {{$data['city']}}
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      Room
                    </div>
                    <div class="col-md-9">
                      {{$data['room']}}
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      Customer
                    </div>
                    <div class="col-md-9">
                      {{$data['user']}}
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      Session
                    </div>
                    <div class="col-md-9">
                      @foreach($data['detail'] as $d)
                        {{$jadwal[$d['jadwal_id']-1]['jadwal_start'].'-'.$jadwal[$d['jadwal_id']-1]['jadwal_end']}}<br>
                      @endforeach
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      Status
                    </div>
                    <div class="col-md-9">
                      {{$status[$reservasi['reservasi_status']]}}
                    </div>
                  </div>
                  <div>
                    <div class="col-md-3">
                      Bill
                    </div>
                    <div class="col-md-9">
                      <h4><b>{{$reservasi['reservasi_tagihan']}}</b></h4>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                    @if($reservasi['reservasi_status']=='0')
                  <a href="#modalku" data-toggle="modal">
                      <button type="button" class="btn btn-primary">Confirm</button>
                  </a>
                    @else 
                      <button type="button" class="btn btn-primary disabled">Confirm</button>
                    @endif
                </div>
                <div id="modalku" class="modal fade modal-danger" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title"><b>Confirmation Payment</b></h4>
                      </div>
                      <div class="modal-body">
                        {{Form::open(['url'=>'r/issue','method'=>'POST','role'=>'form'])}}
                          <div class="form-group">
                            <label for="nama_city">Are you sure you want to confirm this payment? ({{$reservasi['reservasi_tagihan']}})</label>
                            {{Form::hidden('reservasi_id',$reservasi['reservasi_id'])}}
                          </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                          {{Form::submit('Yes',['class'=>'btn btn-danger'])}}
                        {{Form::close()}}
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
            @endif
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#tabletable').DataTable();
        });
    </script>
@stop