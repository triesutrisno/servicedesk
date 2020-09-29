
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable({
          "iDisplayLength": 20
        });
        //$('[data-toggle="confirmation"]').confirmation();
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            // other options
        });
    } );
</script>
<script src="{{asset('bs4/js/bootstrap-confirmation.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js" integrity="sha512-eUQ9hGdLjBjY3F41CScH3UX+4JDSI9zXeroz7hJ+RteoCaY+GP/LDoM8AO+Pt+DRFw3nXqsjh9Zsts8hnYv8/A==" crossorigin="anonymous"></script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="form-group">
      <a href="{{ url('approvetiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Data Approve Tiket</h4>
              @if (session('pesan'))
                    @if (session('kode')=='99')
                        <div class="alert alert-success">
                            <i class="fa  fa-info-circle bigger-120 blue"></i>
                            {{ session('pesan') }}
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fa  fa fa-times-rectangle bigger-120 red"></i>
                            {{ session('pesan') }}
                        </div>
                    @endif
               @endif
              
              <div class="table-responsive">
                <table class="table table-striped" id="table">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <!--<th>No</th>-->
                      <th>Nomor</th>                      
                      <th>Layanan</th>
                      <th>Service</th>
                      <th>Subservice</th>
                      <th>Keterangan</th>
                      <th>Tgl Buat</th>
                      <th>UserBy</th>
                      <th>Prioritas</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($datas as $data)
                  
                 
                    <tr>                        
                      <td>
                          @csrf                          
                          <a href="{{ url('/approvetiket')}}/detail/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-warning" title="Detail">
                              <i class="fa fa-search icon-lg"></i>
                          </a>
                          @if($data->tiketStatus=='1')
                          <form action="{{ url('approvetiket/approve') }}/{{ $data->tiketId }}" method="post" class="d-inline">
                              @method('patch')
                              @csrf
                              <button class="btn btn-icons btn-inverse-primary" data-toggle="confirmation" data-singleton="true" data-title="Anda yakin approve data ini ?">
                                  <i class="fa fa-check-square icon-lg"></i>
                              </button>
                          </form>
                          <form action="{{ url('approvetiket/reject') }}/{{ $data->tiketId }}" method="post" class="d-inline">
                              @method('patch')
                              @csrf
                              <button class="btn btn-icons btn-inverse-danger" data-toggle="confirmation" data-singleton="true" data-title="Anda yakin mereject data ini ?">
                                  <i class="fa fa fa-times-rectangle-o icon-lg"></i>
                              </button>
                          </form>
                          @endif
                      </td>
                        <!--<td align="center">{{$loop->iteration}}</td>-->
                        <td class="py-1">{{$data->kode_tiket }}</td>
                        <td>{{ $data->nama_layanan }}</td>
                        <td>{{ $data->ServiceName }}</td>
                        <td>{{ $data->ServiceSubName }}</td>
                        <td>{{ $data->tiketKeterangan}}</td>
                        <td>{{ date('d-m-Y H:i', strtotime($data->created_at)) }}</td>                         
                        <td>{{ $data->name}}</td>                     
                        <td>
                          @if($data->tiketPrioritas == '1')
                              Biasa
                          @elseif($data->tiketPrioritas == '2')
                              Segera
                          @elseif($data->tiketPrioritas == '3')
                              Prioritas dan Penting
                          @else

                          @endif
                        </td>
                        <td>
                            @if($data->tiketStatus == '1')
                            <label class="badge badge-warning">open</label>
                            @elseif($data->tiketStatus == '2')
                                <label class="badge badge-warning">Diapprove Atasan Unit</label>
                            @elseif($data->tiketStatus == '3')
                                <label class="badge badge-danger">Ditolak Atasan Unit</label>
                            @elseif($data->tiketStatus == '4')
                                <label class="badge badge-success">Disetujui</label>
                            @elseif($data->tiketStatus == '5')
                                <label class="badge badge-danger">Ditolak</label>
                            @elseif($data->tiketStatus == '6')
                                <label class="badge badge-info">Dikerjakan</label>
                            @elseif($data->tiketStatus == '7')
                                <label class="badge badge-primary">Selesai</label>
                            @elseif($data->tiketStatus == '8')
                                <label class="badge badge-dark">Close</label>
                            @elseif($data->tiketStatus == '9')
                                <label class="badge badge-warning">Pending</label>
                            @elseif($data->tiketStatus == '10')
                                <label class="badge badge-danger">Cancel</label>
                            @elseif($data->tiketStatus == '11')
                                <label class="badge badge-warning">Forward</label>
                            @endif
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