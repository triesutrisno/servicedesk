@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tableTiket').DataTable({
          "iDisplayLength": 50
        });
        
        $('.pilihTeknisi').click( function(){
            $('#nikTeknisi').val($(this).attr('data_nik'));
            $('#namaTeknisi').val($(this).attr('data_nama'));            
            $('#namaTeknisi2').text($(this).attr('data_nama'));
            $('#emailTeknisi').val($(this).attr('data_email'));
            $('#myModalTeknisi').modal('hide');
        });
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
<br />
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Forward Tiket</h4>                                  
                <form method="POST" action="{{ url('tugasku/forward') }}/{{ $datas[0]->tiketDetailId }}/{{ $datas[0]->tiketId }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label for="kode_tiket" class="col-md-4 control-label">Nomor Tiket</label>
                            <div class="col-md-6">
                                <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                                {{ $datas[0]->kode_tiket }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="tiketLayanan" class="col-md-4 control-label">Layanan</label>
                            <div class="col-md-6">
                              <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                              {{ $datas[0]->nama_layanan }}
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label for="tiketService" class="col-md-4 control-label">Service</label>
                            <div class="col-md-6">
                              <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                              {{ $datas[0]->ServiceName }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="subServiceId" class="col-md-4 control-label">Sub Service</label>
                            <div class="col-md-6">
                                 <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                                  {{ $datas[0]->ServiceSubName }}
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label for="tiketPrioritas" class="col-md-4 control-label">Prioritas</label>
                            <div class="col-md-6">
                                <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                                @if($datas[0]->tiketPrioritas=='1')
                                    Biasa
                                @elseif($datas[0]->tiketPrioritas=='2')
                                    Segera
                                @elseif($datas[0]->tiketPrioritas=='3')
                                    Prioritas dan Penting
                                @else

                               @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="tiketStatus" class="col-md-4 control-label">Status</label>
                            <div class="col-md-6">
                                <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                                @if($datas[0]->tiketStatus=='1')
                                    <label class="badge badge-warning">open</label>
                                @elseif($datas[0]->tiketStatus=='2')
                                    <label class="badge badge-warning">Diapprove Atasan Unit</label
                                @elseif($datas[0]->tiketStatus=='3')
                                    <label class="badge badge-danger">Ditolak Atasan Unit</label>
                                @elseif($datas[0]->tiketStatus=='4')
                                    <label class="badge badge-success">Disetujui</label>                                    
                                @elseif($datas[0]->tiketStatus=='5')
                                    <label class="badge badge-danger">Ditolak</label>
                                @elseif($datas[0]->tiketStatus=='6')
                                    <label class="badge badge-info">Dikerjakan</label>
                                @elseif($datas[0]->tiketStatus=='7')
                                    <label class="badge badge-primary">Selesai</label>
                                @elseif($datas[0]->tiketStatus == '8')
                                    <label class="badge badge-dark">Close</label>
                                @elseif($datas[0]->tiketStatus == '9')
                                    <label class="badge badge-warning">Pending</label>
                                @elseif($datas[0]->tiketStatus == '10')
                                    <label class="badge badge-danger">Cancle</label>
                                @elseif($datas[0]->tiketStatus == '11')
                                    <label class="badge badge-warning">Forward</label>
                               @endif
                            </div>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="tiketKeterangan" class="col-md-4 control-label">Deskripsi Tiket</label>
                        <div class="col-md-6">{{ $datas[0]->tiketKeterangan }}</div>
                    </div>
                    <div class="form-group">
                        <label for="tiketNikTeknisi" class="col-md-4 control-label">Teknisi</label>
                        <div class="input-group col-md-6">
                            <input type="text" name="nikTeknisi" id="nikTeknisi" class="form-control" required>
                            <input type="hidden" name="namaTeknisi" id="namaTeknisi" readonly="true" class="form-control" required>
                            <input type="hidden" name="emailTeknisi" id="emailTeknisi" readonly="true" class="form-control" required>
                            <a href="#" data-toggle="modal" data-target="#myModalTeknisi" style="text-decoration:none">
                            <div class="input-group-append bg-primary border-primary">
                                <span class="input-group-text bg-transparent">                                    
                                    <i class="fa fa-search text-white"></i>
                                </span>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-6" id="namaTeknisi2"></div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="col-md-4 control-label">Keterangan *</label>
                        <div class="col-md-6">
                            <textarea class="form-control" required id="keterangan" name="keterangan" rows="6"></textarea>                            
                        </div>
                    </div>                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success mr-2">Simpan</button>
                        <button type='reset' class="btn btn-light">Reset</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalTeknisi" tabindex="-1" role="dialog" aria-labelledby="myModalTeknisi" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" style="background: #fff;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Forward Ke</h5>
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
