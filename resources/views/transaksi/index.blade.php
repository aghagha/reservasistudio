@extends('adminlte::page')

@section('title', 'Chamber - Add Stduio')

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
                    <h3 class="box-title"><b>Transaction List</b></h3>
                </div>
                <div class="box-body">
                    <table id="tabletable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                              <th>Reservation Code</th>
                              <th>Reserved by</th>
                              <th>Studio</th>
                              <th>Room</th>
                              <th>Schedule</th>
                              <th>Date</th>
                              <th>Bill</th>
                              <th>Status</th>
                              <th>Refunded at</th>
                              <th>Refund Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasi as $r)
                            <tr>
                              <td>{{$r['reservasi_nomor_booking']}}</td>
                              <td>
                                {{$user[$r['user_id']]['name']}}<br>
                                {{$user[$r['user_id']]['email']}}
                              </td>
                              <td>{{$studio[$r['studio_id']]}}, {{$city[$r['studio_id']]['city_name']}}</td>
                              <td>{{$room[$r['room_id']]}}</td>
                              <td>
                                @foreach($r['detail'] as $rd)
                                  {{$rd}}<br>
                                @endforeach
                              </td>
                              <td>{{$r['reservasi_tanggal']}}</td>
                              <td>{{$r['reservasi_tagihan']}}</td>
                              <td>{{$status[$r['reservasi_status']]}}</td>
                              <td>{{$r['refunded_at']}}</td>
                              <td>{{$r['reservasi_refund']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title"><b>Export to Excel</b></h3>
              </div>
              <div class="box-body">
                <div>
                  {{Form::open(['url'=>'studio/report','method'=>'POST','role'=>'form', 'class'=>'form-horizontal'])}}
                    <div class="form-group">
                      <label for="nama_city" class="col-md-1">Daily</label>
                      <div class="col-md-3">
                        {{Form::select('studio_id',$studio,null,['class'=>'form-control'])}}
                      </div>
                      <div class="col-md-3">
                        {{Form::date('hari',\Carbon\Carbon::now(),['class'=>'form-control'])}}
                      </div>
                      <div class="col-md-3">
                        {{Form::submit('View',['class'=>'btn btn-primary'])}}
                      </div>
                    </div>
                  {{Form::close()}}
                </div>
              </div>
              <div class="box-body">
                <div>
                  {{Form::open(['url'=>'studio/report','method'=>'POST','role'=>'form', 'class'=>'form-horizontal'])}}
                    <div class="form-group">
                      <label for="nama_city" class="col-md-1">Monthly</label>
                      <div class="col-md-3">
                        {{Form::select('studio_id',$studio,null,['class'=>'form-control'])}}
                      </div>
                      <div class="col-md-3">
                        <input type="month" name="bulan" class="form-control">
                      </div>
                      <div class="col-md-3">
                        {{Form::submit('View',['class'=>'btn btn-primary'])}}
                      </div>
                    </div>
                  {{Form::close()}}
                </div>
              </div>
              <div class="box-body">
                <div>
                  {{Form::open(['url'=>'studio/report','method'=>'POST','role'=>'form', 'class'=>'form-horizontal'])}}
                    <div class="form-group">
                      <label for="nama_city" class="col-md-1">Annually</label>
                      <div class="col-md-3">
                        {{Form::select('studio_id',$studio,null,['class'=>'form-control'])}}
                      </div>
                      <div class="col-md-3">
                        <input type="text" name="tahun" class="form-control">
                      </div>
                      <div class="col-md-5">
                        {{Form::submit('View',['class'=>'btn btn-primary'])}}
                      </div>
                    </div>
                  {{Form::close()}}
                </div>
              </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#tabletable').DataTable();
        });
    </script>
@stop