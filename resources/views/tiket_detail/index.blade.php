
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
          "iDisplayLength": 20,
            "order": [[ 7, "asc" ]],  
        });
        table.column( 7 ).visible( false );
    });
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="form-group">
      <a href="{{ url('tugasku') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Data Tugasku</h4>
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
                      <th>Nomor</th>                      
                      <!--<th>Layanan</th>-->
                      <th>Service</th>
                      <th>Subservice</th>
                      <th>Keterangan</th>
                      <th>Tgl Buat</th>
                      <th>Prioritas</th>
                      <th>KodeStatus</th>
                      <th>Status</th>
                      <th>Progres</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($datas as $data)
                  
                 
                    <tr>                        
                        <td>
                            @csrf                          
                            <a href="{{ url('/tugasku')}}/detail/{{ $data->tiketDetailId }}" class="btn btn-icons btn-inverse-primary" title="Detail">
                                <i class="fa fa-search icon-lg"></i>
                            </a>
                            @if($data->tiketDetailStatus<'6' && $data->nikTeknisi==session('infoUser')['NIK'])
                            <a href="{{ url('/tugasku')}}/solusi/{{ $data->tiketDetailId }}" class="btn btn-icons btn-inverse-warning" title="Solusi">
                                <i class="fa fa-send-o icon-lg"></i>
                            </a>
                            <!--
                            <a href="{{ url('/tugasku')}}/forward/{{ $data->tiketDetailId }}" class="btn btn-icons btn-inverse-info" title="Forward">
                                <i class="fa fa-share icon-lg"></i>
                            </a>-->
                            @endif
                        </td>                        
                        <td class="py-1">{{$data->kode_tiket }}</td>
                        <!--<td>{{ $data->nama_layanan }}</td>-->
                        <td>{{ $data->ServiceName }}</td>
                        <td>{{ $data->ServiceSubName }}</td>
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
                        <td>{{ $data->tiketStatus }}</td>
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
                                <label class="badge badge-danger">Cancle</label>
                            @endif
                        </td>
                        <td>
                            @if($data->progresProsen!="")
                                {{ $data->progresProsen }} %
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