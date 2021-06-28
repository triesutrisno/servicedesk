@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      //"iDisplayLength": 25
      "paging"  : false,
      "info"    : false
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="form-group">
      <a href="{{ url('/home') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-body">
                <h4 class="card-title">Data Tiket</h4>

                <div class="table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Status</th>
                                <th>Progress (%)</th>
                                <th>User</th>
                                <th>Nomor</th>
                                <th>Layanan</th>
                                <th>Service</th>
                                <th>Subservice</th>
                                <th>Keterangan</th>
                                <th>Tgl Open</th>
                                <th>Tgl Approve Atasan</th>
                                <th>Tgl Approve Atasan IT</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Tgl Close</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $key => $data)
                            <tr>
                                <td class="py-1">{{ $datas->firstItem() + $key }}</td>
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
                                <td>@if($data->progresProsen!="") {{$data->progresProsen}} % @endif</td>
                                <td>{{$data->name}}</td>
                                <td class="py-1">{{$data->kode_tiket}}</td>
                                <td class="py-1">{{$data->nama_layanan}}</td>
                                <td class="py-1">{{$data->ServiceName}}</td>
                                <td>{{$data->ServiceSubName}}</td>
                                <td>{{$data->tiketKeterangan}}</td>
                                <td>{{$data->created_at}}</td>
                                <td>{{$data->tiketTglApprove}}</td>
                                <td>{{$data->tiketTglApproveService}}</td>
                                <td>{{$data->tglMulaiMengerjakan}}   </td>
                                <td>{{$data->tglSelesaiMengerjakan}}   </td>
                                <td>{{$data->tglClose}}</td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                    <div class="pull-left">
                        Showing
                        {{ $datas->firstItem() }} to {{ $datas->lastItem() }} of {{ $datas->total() }} entries
                    </div>
                    <div class="pull-right">                        
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
