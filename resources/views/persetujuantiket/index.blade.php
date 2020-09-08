
@section('js')
<script src="{{asset('bs4/js/bootstrap-confirmation.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js" integrity="sha512-eUQ9hGdLjBjY3F41CScH3UX+4JDSI9zXeroz7hJ+RteoCaY+GP/LDoM8AO+Pt+DRFw3nXqsjh9Zsts8hnYv8/A==" crossorigin="anonymous"></script>
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
        
        $('.pilihSetuju').click( function(){
            $('#tiketId').val($(this).attr('data-tiket_id'));
        });
        
        $('.pilihTeknisi').click( function(){
            $('#nikTeknisi').val($(this).attr('data_nik'));
            $('#namaTeknisi').text($(this).attr('data_nama'));
            $('#emailTeknisi').val($(this).attr('data_email'));
            $('#myModalTeknisi').modal('hide');
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
                          <a href="{{ url('/tiket')}}/detail/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-warning" title="Detail">
                              <i class="fa fa-search icon-lg"></i>
                          </a>
                          @if($data->tiketStatus=='2')
                          <a href="#" class="btn btn-icons btn-inverse-primary pilihSetuju" data-tiket_id="{{ $data->tiketId }}" title="Setuju" data-toggle="modal" data-target="#myModalApprove">
                              <i class="fa fa-check-square icon-lg"></i>
                          </a> 
                          <form action="{{ url('persetujuantiket/reject') }}/{{ $data->tiketId }}" method="post" class="d-inline">
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
<div class="modal fade bd-example-modal-lg" id="myModalApprove" tabindex="-1" role="dialog" aria-labelledby="myModalApprove" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Persetujuan Tiket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>      
      <form action="{{ url('persetujuantiket/approve') }}" method="post">
        @method('patch')
        @csrf
      <div class="modal-body">
          <div class="form-group">
              <label for="tiketNikAtasanService" class="col-md-4 control-label">Teknisi</label>
              <div class="input-group col-md-6">
                  <input type="text" name="nikTeknisi" id="nikTeknisi" class="form-control" required>
                  <input type="hidden" name="emailTeknisi" id="emailTeknisi" readonly="true" class="form-control" required>
                  <input type="hidden" name="tiketId" id="tiketId" readonly="true" class="form-control" required>
                  <a href="#" data-toggle="modal" data-target="#myModalTeknisi" style="text-decoration:none">
                  <div class="input-group-append bg-primary border-primary">
                      <span class="input-group-text bg-transparent">                                    
                          <i class="fa fa-search text-white"></i>
                      </span>
                  </div>
                  </a>
              </div>
              <div class="col-md-6" id="namaTeknisi"></div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <input type="submit" name="Setuju" value="Setuju" class="btn btn-primary">
      </div>      
     </form>
    </div>
  </div>
</div>
<div class="modal fade" id="myModalTeknisi" tabindex="-1" role="dialog" aria-labelledby="myModalTeknisi" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" style="background: #fff;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cari Teknisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>NIK</th>
                                                <th>NAMA</th>
                                                <th>JABATAN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $nik = $nikLama = "";
                                            foreach($dtAtasanService as $data){
                                                $nik = $data['NIK'];
                                                if($nik!=$nikLama){
                                                @endphp
                                                <tr class="pilihTeknisi" data_nik="{{ $data['NIK'] }}" data_nama="{{ $data['NAMA'] }}" data_email="{{ $data['EMAIL'] }}">
                                                    <td><a href="#" style="text-decoration:none">{{$data['NIK']}}</a></td>
                                                    <td>{{$data['NAMA']}}</td>
                                                    <td>{{$data['URAIAN_JAB']}}</td>
                                                </tr>
                                                @php
                                                $nikLama = $data['NIK'];
                                                }
                                            }
                                            @endphp
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection