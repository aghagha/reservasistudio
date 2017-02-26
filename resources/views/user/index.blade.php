@extends('adminlte::page')

@section('title', 'Chamber - List of Users')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>List of Users</b></h3>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($user as $u)
                          <tr>
                            <td>{{$u['user_id']}}</td>
                            <td>{{$u['user_name']}}</td>
                            <td>{{$u['user_email']}}</td>
                            <td>{{$u['user_hp']}}</td>
                            <td>
                              <a href="#d{{$u['user_id']}}" data-toggle="modal">
                                <button type="button" class="btn btn-danger">
                                  <i class="fa fa-close"></i>
                                </button>
                              </a>
                            </td>
                          </tr>
                          <div id="d{{$u['user_id']}}" class="modal fade modal-danger" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title"><b>Delete user</b></h4>
                                </div>
                                <div class="modal-body">
                                  {{Form::open(['url'=>'studio/deleteuser','method'=>'POST','role'=>'form'])}}
                                    <div class="form-group">
                                      <label for="nama_city">Are you sure you want to delete this user?</label>
                                      {{Form::hidden('user_id',$u['user_id'])}}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                    {{Form::submit('Yes, delete this user',['class'=>'btn btn-danger'])}}
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