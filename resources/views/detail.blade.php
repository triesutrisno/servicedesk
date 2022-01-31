@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      //"iDisplayLength": 25
      "bSort" : false
      "paging"  : false,
      "info"    : false,      
       "bFilter": false,
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
                <form action="{{ url('/home/detail')}}/{{$id}}" method="post">
                @csrf
                <div class="row alert alert-warning">
                      <div class="col-sm-12 col-md-2">
                          <label class="text-small">Nomer : </label>
                          <input type="text" name="nomer" class="form-control" value="{{ $param['nomer'] }}" autocomplete="off">
                      </div>
                      <div class="col-sm-12 col-md-3">
                          <label class="text-small">Nama : </label>
                          <input type="text" name="nama" class="form-control" value="{{ $param['nama'] }}" autocomplete="off">
                      </div>
                      <div class="col-sm-12 col-md-3">
                          <label class="text-small">Nama Atasan Service : </label>
                          <input type="text" name="namaAtasanService" class="form-control" value="{{ $param['namaAtasanService'] }}" autocomplete="off">
                      </div>
                      <div class="col-sm-12 col-md-3">
                          <label class="text-small">Status : </label>
                          <select name="status" class="form-control">
                              <option value="">Silakan Pilih</option>
                              <option value="1" {{ $param['status']=='1' ? 'selected' : '' }}>Open</option>
                              <option value="2"{{ $param['status']=='2' ? 'selected' : '' }}>Diapprove Atasan Unit</option>
                              <option value="3"{{ $param['status']=='3' ? 'selected' : '' }}>Ditolak Atasan Unit</option>
                              <option value="4"{{ $param['status']=='4' ? 'selected' : '' }}>Disetujui</option>
                              <option value="5"{{ $param['status']=='5' ? 'selected' : '' }}>Ditolak</option>
                              <option value="6"{{ $param['status']=='6' ? 'selected' : '' }}>Dikerjakan</option>
                              <option value="7"{{ $param['status']=='7' ? 'selected' : '' }}>Selesai</option>
                              <option value="8"{{ $param['status']=='8' ? 'selected' : '' }}>Close</option>
                              <option value="9"{{ $param['status']=='9' ? 'selected' : '' }}>Pending</option>
                              <option value="10"{{ $param['status']=='10' ? 'selected' : '' }}>Cancel</option>
                              <option value="11"{{ $param['status']=='11' ? 'selected' : '' }}>Diforward</option>
                          </select>
                      </div>
                      <div class="col-sm-12 col-md-1">
                          <br />
                          <button type="submit" class="btn btn-success mr-2">Cari</button>
                      </div>
                </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Progress (%)</th>
                                <th>User</th>
                                <th>Nomor</th>
                                <th>Layanan</th>
                                <th>Service</th>
                                <th>Subservice</th>                                
                                <th>Nama Atasan Service</th>
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
                                <a href="{{ url('/home')}}/show/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-warning" title="Detail">
                                    <i class="fa fa-search icon-lg"></i>
                                </a>
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
                                <td>@if($data->progresProsen!="") {{$data->progresProsen}} % @endif</td>
                                <td>{{$data->name}}</td>
                                <td class="py-1">{{$data->kode_tiket}}</td>
                                <td class="py-1">{{$data->nama_layanan}}</td>
                                <td class="py-1">{{$data->ServiceName}}</td>
                                <td>{{$data->ServiceSubName}}</td>
                                <td>{{$data->nameAtasanService}}</td>
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
