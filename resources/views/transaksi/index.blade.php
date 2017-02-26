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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>List Studio</b></h3>
                </div>
                <div class="box-body">
                    @if (Session::has('msg'))
                        @if(Session::get('msg')['c'] == '1')
                            <div class="alert alert-info">{{ Session::get('msg')['m'] }}</div>
                        @else
                            <div class="alert alert-danger">{{ Session::get('msg')['m'] }}</div>
                        @endif
                    @endif
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
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#tabletable').DataTable();
        });
    </script>
@stop