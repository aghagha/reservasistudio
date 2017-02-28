@extends('adminlte::page')

@section('title', 'Chamber - List Studio')

@section('content_header')

@stop

@section('content')
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
                    <h3 class="box-title"><b>List Studio</b></h3>
                </div>
                <div class="box-body">
                    <table id="tabletable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                              <th>Name</th>
                              <th>City</th>
                              <th>Address</th>
                              <th>Phone</th>
                              <th>Open</th>
                              <th>Bank Accout Info</th>
                              <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studio as $s)
                            <tr>
                              <td>{{$s['studio_nama']}}</td>
                              <td>{{$city[$s['city_id']]}}</td>
                              <td>{{$s['studio_alamat']}}</td>
                              <td>{{$s['studio_telepon']}}</td>
                              <td>{{$s['studio_open_hour']}} to {{$s['studio_close_hour']}}</td>
                              <td>{{$s['studio_rekening']}}</td>
                              <td>
                                <a href="{{route('studio.edit',['studio_id'=>$s['studio_id']])}}">
                                    <button type="button" class="btn btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
                                @if(Session::get('hak')=='ADMIN_STUDIO')
                                <a href="#r{{$s['studio_id']}}" data-toggle="modal">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </a>
                                @endif
                                <a href="#d{{$s['studio_id']}}" data-toggle="modal">
                                    <button type="button" class="btn btn-danger">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </a>
                              </td>
                            </tr>
                            <div id="d{{$s['studio_id']}}" class="modal fade modal-danger" tabindex="-1" role="dialog">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><b>Delete studio</b></h4>
                                  </div>
                                  <div class="modal-body">
                                    {{Form::open(['url'=>'studio/delete','method'=>'POST','role'=>'form'])}}
                                      <div class="form-group">
                                        <label for="nama_city">Are you sure you want to delete this studio?</label>
                                        {{Form::hidden('studio_id',$s['studio_id'])}}
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                      {{Form::submit('Yes, delete this studio',['class'=>'btn btn-danger'])}}
                                    {{Form::close()}}
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <div id="r{{$s['studio_id']}}" class="modal fade modal" tabindex="-1" role="dialog">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><b>Add a new room for {{$s['studio_nama']}}</b></h4>
                                  </div>
                                  <div class="modal-body">
                                    {{Form::open(['url'=>'studio/addroom','method'=>'POST','role'=>'form'])}}
                                      <div class="form-group">
                                        <label for="nama_city">Name</label>
                                        {{Form::text('room_name','',['placeholder'=>'Enter a name for the room','class'=>'form-control'])}}
                                      </div>
                                      <div class="form-group">
                                        <label for="nama_city">Cost</label>
                                        {{Form::number('room_harga','',['placeholder'=>'How much the room will cost, ex:100000','class'=>'form-control'])}}
                                      </div>
                                        {{Form::hidden('studio_id',$s['studio_id'])}}
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                                      {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
                                    {{Form::close()}}
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- End box body studio -->
            </div><!-- End box -->
            @if(Session::get('hak')=='ADMIN_STUDIO')
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title"><b>List Room</b></h3>
              </div>
              <div class="box-body">
                <table id="tabletable2" class="table table-bordered table-striped">
                  <thead>
                    <th>Name</th>
                    <th>Studio</th>
                    <th>Price/session</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                    @foreach($room as $r)
                    <tr>
                      <td>{{$r['room_nama']}}</td>
                      <td>{{$std[$r['studio_id']]}}</td>
                      <td>{{$r['room_harga']}}</td>
                      <td>
                        <a href="#edit{{$r['room_id']}}" data-toggle="modal">
                          <button type="button" class="btn btn-warning">
                              <i class="fa fa-pencil"></i>
                          </button>
                        </a>
                        <a href="#del{{$r['room_id']}}" data-toggle="modal">
                          <button type="button" class="btn btn-danger">
                              <i class="fa fa-close"></i>
                          </button>
                        </a>
                      </td>
                    </tr>
                    <div id="edit{{$r['room_id']}}" class="modal fade modal" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><b>Edit {{$r['room_nama']}}</b></h4>
                          </div>
                          <div class="modal-body">
                            {{Form::open(['url'=>'studio/editroom','method'=>'POST','role'=>'form'])}}
                              <div class="form-group">
                                <label for="nama_city">Name</label>
                                {{Form::text('room_nama',$r['room_nama'],['class'=>'form-control'])}}
                              </div>
                              <div class="form-group">
                                <label for="nama_city">Cost</label>
                                {{Form::number('room_harga',$r['room_harga'],['class'=>'form-control'])}}
                              </div>
                                {{Form::hidden('room_id',$r['room_id'])}}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                              {{Form::submit('Save Changes',['class'=>'btn btn-primary'])}}
                            {{Form::close()}}
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div id="del{{$r['room_id']}}" class="modal fade modal-danger" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><b>Delete {{$r['room_nama']}}</b></h4>
                          </div>
                          <div class="modal-body">
                            {{Form::open(['url'=>'studio/delroom','method'=>'POST','role'=>'form'])}}
                              <div class="form-group">
                                <label for="nama_city">Are you sure you want to delete this room?</label>
                                {{Form::hidden('room_id',$r['room_id'])}}
                              </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                              {{Form::submit('Yes, delete this room',['class'=>'btn btn-danger'])}}
                            {{Form::close()}}
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#tabletable').DataTable();
            $('#tabletable2').DataTable();
        });
    </script>
@stop