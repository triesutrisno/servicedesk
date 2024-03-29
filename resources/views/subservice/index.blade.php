@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 10
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

  <div class="col-lg-2">
    <a href="{{ route('subservice.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Tambah Sub Service</a>
  </div>
    <div class="col-lg-12">
                  @if (Session::has('message'))
                  <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{ Session::get('message') }}</div>
                  @endif
                  </div>
</div>
<div class="row" style="margin-top: 20px;">
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
              <div class="card-header p-1" style="background: #000080;"></div>
                <div class="card-body">
                  <h4 class="card-title">Data Sub Service</h4>

                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                        <th>No</th>
                        <th>Nama Service</th>
                        <th>Nama Sub Service</th>
                        <th>Unit</th>
                        <!-- <th>Standart Penyelesaian</th> -->
                        <th>Status</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($datas as $data)
                        <tr>
                          <td class="py-1">{{$loop->iteration}}</td>
                          <td class="py-1">{{ $data->ServiceName }}</td>
                          <td class="py-1">{{$data->ServiceSubName}}</td>
                          <td class="py-1">{{$data->nama_unit}}</td>
                          
                          <td>
                            @if($data->ServiceSubStatus == '1')
                            <label class="badge badge-success">Aktif</label>
                            @elseif($data->ServiceSubStatus == '0')
                            <label class="badge badge-danger">Tidak Aktif</label>
                            @endif
                          <td>
                           <div class="btn-group dropdown">
                          <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                            <a class="dropdown-item" href="{{route('subservice.edit', $data->id)}}"> Edit </a>


                          </div>
                        </div>
                        <!-- <a href="{{route('subservice.edit', $data->id)}}"><span class="fa fa-edit"></span></a> -->
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
               {{--  {!! $datas->links() !!} --}}
                </div>
              </div>
            </div>
          </div>
@endsection
