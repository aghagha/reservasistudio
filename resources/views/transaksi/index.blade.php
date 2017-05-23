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
                              <td>
                                @if($r['refunded_at']==null && $r['reservasi_refund']!= '0')
                                  <a href="#">
                                      <button type="button" class="btn btn-warning btn-xs refund-btn" value="{{$r['reservasi_id']}}">
                                          Refund now
                                      </button>
                                  </a>
                                @else
                                  {{$r['refunded_at']}}
                                @endif
                              </td>
                              <td class='refund-this'>{{$r['reservasi_refund']}}</td>
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
    <div id="refund-modal" class="modal fade modal-danger" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Refund Reservastion</b></h4>
          </div>
          <div class="modal-body">
            {{Form::open(['role'=>'form'])}}
              <div class="form-group">
                <label for="nama_city">Refund cost:</label>
                <h3 id="refund-amount">amount here</h3>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <a id="refund-url" href="#">
              <button type="button" class="btn btn-danger">Done</button>
            </a>
            {{Form::close()}}
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        $(document).ready(function(){
            $('#tabletable').DataTable();
            $('.refund-btn').click(function(){
              var r_id = $(this).val();
              var url = 'refundpayment/'+r_id;
              $('#refund-amount').text($(this).closest('tr').children('td.refund-this').text());
              $('#refund-url').attr('href',url);
              $('#refund-modal').modal('show');
            });
        });
    </script>
@stop