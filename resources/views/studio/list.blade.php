@extends('adminlte::page')

@section('title', 'Chamber - Add Stduio')

@section('content_header')

@stop

@section('content')
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
                                <a href="{{route('studio.delete',['studio_id'=>$s['studio_id']])}}">
                                    <button type="button" class="btn btn-danger">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </a>
                              </td>
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