
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
            "iDisplayLength": 20,
            "searching": false,
            "order": [[ 9, "asc" ]],  
        });
        table.column( 9 ).visible( false );
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
                <form action="tugasku" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="nomer" id="nomer" class="form-control" placeholder="Nomer Tiket" value="{{ $nomor }}">
                        </div>
                        <!--<div class="col-md-3">
                            <input type="text" name="service" class="form-control" placeholder="Service">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="subservice" class="form-control" placeholder="Sub Service">
                        </div>-->
                        <div class="col-md-2">                          
                            <select class="form-control" name="status" placeholder="Status">
                                <option value="">Pilih Status</option>
                                <option value="1"{{ $status=='1' ? 'selected' : '' }}>Open</option>
                                <option value="2"{{ $status=='2' ? 'selected' : '' }}>Diapprove Atasan Unit</option>
                                <option value="3"{{ $status=='3' ? 'selected' : '' }}>Ditolak Atasan Unit</option>
                                <option value="4"{{ $status=='4' ? 'selected' : '' }}>Disetujui</option>
                                <option value="5"{{ $status=='5' ? 'selected' : '' }}>Ditolak</option>
                                <option value="6"{{ $status=='6' ? 'selected' : '' }}>Dikerjakan</option>
                                <option value="7"{{ $status=='7' ? 'selected' : '' }}>Selesai</option>
                                <option value="8"{{ $status=='8' ? 'selected' : '' }}>Close</option>
                                <option value="9"{{ $status=='9' ? 'selected' : '' }}>Pending</option>
                                <option value="10"{{ $status=='10' ? 'selected' : '' }}>Cancle</option>                              
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success mr-2">Cari</button>
                        </div>
                    </div>
                </form>
              <br />
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
                      <th>UserBy</th>
                      <th>Teknisi</th>
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
                        <td>{{ $data->userBy }}</td>
                        <td>{{ $data->namaTeknisi }}</td>
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