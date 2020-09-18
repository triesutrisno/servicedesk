@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 50
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-poll-box text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Ticket Masih Open</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('tiketStatus','<',7)->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Belum ditangani
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-receipt text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Tiket Selesai</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('tiketStatus', '7')->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Sudah ditangani
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-book text-success icon-lg" style="width: 40px;height: 40px;"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Ticket Masuk</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Ticket masuk
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-account-location text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Ticket Close</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('tiketStatus','8')->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-account mr-1" aria-hidden="true"></i> Ticket yang sudah Close user
                  </p>
                </div>
              </div>
            </div>
</div>
<div class="row">

  
    <div class="col-lg-12">
                  @if (Session::has('message'))
                  <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{ Session::get('message') }}</div>
                  @endif
                  </div>
</div>
<div class="row" style="margin-top: 20px;">
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Status Open Ticket sampai dengan hari ini</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                            <th>No</th>
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
                            <th>User</th>
                            <th>Progress (%)</th>
                            <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($datas as $data)

                        <tr>
                            <td class="py-1">{{$loop->iteration}}</td>
                            <td class="py-1">{{$data->kode_tiket}}</td>
                            <td class="py-1">{{$data->nama_layanan}}</td>
                            <td class="py-1">{{$data->ServiceName}}</td>
                            <td>{{$data->ServiceSubName}}</td>
                            <td>{{$data->tiketKeterangan}}</td>
                            <td>{{$data->created_at}}</td>
                            <td>{{$data->tiketTglApprove}}</td>
                            <td>{{$data->tiketTglApproveService}}</td>
                            <td> {{$data->tglMulaiMengerjakan}}   </td>
                            <td> {{$data->tglSelesaiMengerjakan}}   </td>
                            <td> {{$data->tglClose}}   </td>
                            <td>  {{$data->name}}      </td>
                            <td>  {{$data->progresProsen}}% </td>
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
