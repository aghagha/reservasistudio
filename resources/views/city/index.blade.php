@extends('adminlte::page')

@section('title', 'Chamber - List of Cities')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>List of Cities</b></h3>
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
                            <th>No</th>
                            <th>City</th>
                            <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($city as $c)
                          <tr>
                            <td>{{$c['city_id']}}</td>
                            <td>{{$c['city_name']}}</td>
                            <td>
                              <a href="#m{{$c['city_id']}}" data-toggle="modal">
                                <button type="button" class="btn btn-warning">
                                  <i class="fa fa-pencil"></i>
                                </button>
                              </a>
                              <a href="#d{{$c['city_id']}}" data-toggle="modal">
                                <button type="button" class="btn btn-danger">
                                  <i class="fa fa-close"></i>
                                </button>
                              </a>
                            </td>
                          </tr>
                          <div id="m{{$c['city_id']}}" class="modal fade" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title"><b>Edit city</b></h4>
                                </div>
                                <div class="modal-body">
                                  {{Form::open(['url'=>'studio/editcity','method'=>'POST','role'=>'form'])}}
                                    <div class="form-group">
                                      <label for="nama_city">City's new name</label>
                                      {{Form::text('city_name',$c['city_name'],['class'=>'form-control','id'=>'nama_city','placeholder'=>"Enter the city's name, use comma to add another city, ex :'Bandung, Jogjakarta, Medan'"])}}
                                    </div>
                                    {{Form::hidden('city_id',$c['city_id'])}}
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    {{Form::submit('Save changes',['class'=>'btn btn-primary'])}}
                                  {{Form::close()}}
                                </div>
                              </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                          </div><!-- /.modal -->
                          <div id="d{{$c['city_id']}}" class="modal fade modal-danger" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title"><b>Delete city</b></h4>
                                </div>
                                <div class="modal-body">
                                  {{Form::open(['url'=>'studio/deletecity','method'=>'POST','role'=>'form'])}}
                                    <div class="form-group">
                                      <label for="nama_city">Are you sure you want to delete this city?</label>
                                      {{Form::hidden('city_id',$c['city_id'])}}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                    {{Form::submit('Yes, delete this city',['class'=>'btn btn-danger'])}}
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
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#tabletable').DataTable();
        });
    </script>
@stop