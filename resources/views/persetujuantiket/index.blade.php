
@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 20
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="form-group">
      <a href="{{ url('persetujuantiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Data Persetujuan Tiket</h4>
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
                      <th>Kode Ticket</th>                      
                      <th>Layanan</th>
                      <th>Service</th>
                      <th>Subservice</th>
                      <th>Keterangan</th>
                      <th>Tgl Buat</th>
                      <th>Prioritas</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($datas as $data)
                  
                 
                    <tr>                        
                      <td>
                          @csrf                          
                          <a href="{{ url('/tiket')}}/detail/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-primary" title="Detail">
                              <i class="fa fa-search icon-lg"></i>
                          </a>
                          @if($data->tiketStatus=='1')
                          <a href="{{ url('/approvetiket')}}/approve/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-warning" title="Approve">
                              <i class="fa fa-edit icon-lg"></i>
                          </a> 
                          <a href="{{ url('/approvetiket')}}/reject/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-info" data-id="{{ $data->tiketId }}" title="Reject">
                              <i class="fa fa-trash-o icon-lg"></i>
                          </a>
                          @endif
                      </td>
                        <!--<td align="center">{{$loop->iteration}}</td>-->
                        <td class="py-1">{{$data->kode_tiket }}</td>
                        <td>{{ $data->layanan[0]['nama_layanan'] }}</td>
                        <td>{{ $data->service[0]['ServiceName'] }}</td>
                        <td>{{ $data->subService[0]['ServiceSubName'] }}</td>
                        <td>{{ $data->tiketKeterangan}}</td>
                        <td>{{ date('d-m-Y H:i', strtotime($data->created_at)) }}</td>                        
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
                                <label class="badge badge-warning">Sdh App Atasan</label>
                            @elseif($data->tiketStatus == '3')
                                <label class="badge badge-warning">Sudah App Atasan IT </label>
                            @elseif($data->tiketStatus == '4')
                                <label class="badge badge-success">Selesai</label>
                            @elseif($data->tiketStatus == '7')
                                <label class="badge badge-success">Ditolak Atasan IT</label>
                            @elseif($data->tiketStatus == '6')
                                <label class="badge badge-success">Ditolak Atasan Unit</label>
                            @else
                                <label class="badge badge-success">close</label>
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