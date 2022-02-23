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
            $('#emailTeknisi2').val($(this).attr('data_email2'));
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
                    <div class="table-responsive-sm">
                        <table class="table-responsive">
                            <tr>
                                <td width="10%">Nomor</td>
                                <td width="2%">:</td>
                                <td>{{ $datas[0]->kode_tiket }}</td>
                                <td width="10%">Layanan</td>
                                <td width="2%">:</td>
                                <td>{{ $datas[0]->nama_layanan }}</td>
                            </tr>
                            <tr>
                                <td>Service</td>
                                <td>:</td>
                                <td>{{ $datas[0]->ServiceName }}</td>
                                <td>Sub Service</td>
                                <td>:</td>
                                <td>
                                    <!--{{ $datas[0]->ServiceSubName }}-->
                                    <select class="form-control"  required id="subServiceId" name="subServiceId">
                                        <option value="">Silakan Pilih</option>
                                        @foreach($subService as $key => $val)
                                            <option value="{{ $val->id }}" {{ $datas[0]->subServiceId == $val->id ? 'selected' : '' }}>{{ $val->ServiceSubName }}</option>
                                        @endforeach                          
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Buat</td>
                                <td>:</td>
                                <td>{{ date('d-m-Y H:i', strtotime($datas[0]->created_at)) }}</td>
                                <td>File</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->file!="")
                                        <a href="{{ url('/images/fileTiket') }}/{{$datas[0]->file}}">Lampiran</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Prioritas</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->tiketPrioritas=='1')
                                        Biasa
                                    @elseif($datas[0]->tiketPrioritas=='2')
                                        Segera
                                    @elseif($datas[0]->tiketPrioritas=='3')
                                        Prioritas dan Penting
                                    @else

                                    @endif
                                </td>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->tiketStatus == '1')
                                        <label class="badge badge-warning">open</label>
                                    @elseif($datas[0]->tiketStatus == '2')
                                        <label class="badge badge-warning">Diapprove Atasan Unit</label>
                                    @elseif($datas[0]->tiketStatus == '3')
                                        <label class="badge badge-danger">Ditolak Atasan Unit</label>
                                    @elseif($datas[0]->tiketStatus == '4')
                                        <label class="badge badge-success">Disetujui</label>
                                    @elseif($datas[0]->tiketStatus == '5')
                                        <label class="badge badge-danger">Ditolak</label>
                                    @elseif($datas[0]->tiketStatus == '6')
                                        <label class="badge badge-info">Dikerjakan</label>
                                    @elseif($datas[0]->tiketStatus == '7')
                                        <label class="badge badge-primary">Selesai</label>
                                    @elseif($datas[0]->tiketStatus == '8')
                                        <label class="badge badge-dark">Close</label>
                                    @elseif($datas[0]->tiketStatus == '9')
                                        <label class="badge badge-warning">Pending</label>
                                    @elseif($datas[0]->tiketStatus == '10')
                                        <label class="badge badge-danger">Cancel</label>
                                    @elseif($datas[0]->tiketStatus == '11')
                                        <label class="badge badge-warning">Forward</label>
                                    @endif
                                </td>
                            </tr>                       
                            <tr>
                                <td>Severity</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->tiketSeverity == '1')
                                        Severity Level 1
                                    @elseif($datas[0]->tiketSeverity == '2')
                                        Severity Level 2
                                    @elseif($datas[0]->tiketSeverity == '3')
                                        Severity Level 3
                                    @elseif($datas[0]->tiketSeverity == '4')
                                        Severity Level 4
                                    @endif
                                </td>
                                <td>Maindays</td>
                                <td>:</td>
                                <td>{{ $datas[0]->tiketMaindays}} @if($datas[0]->tiketMaindays <> '') Hari @endif</td>
                            </tr>
                            <tr>
                                <td>Teknisi</td>
                                <td>:</td>
                                <td>{{ $datas[0]->namaTeknisi }}</td>
                                <td>Atasan Teknisi</td>
                                <td>:</td>
                                <td>{{ session('infoUser')['AL_NAMA'] }}</td>
                            </tr>
                            <tr>
                                <td>Deskripsi Tiket</td>
                                <td>:</td>
                                <td colspan="4" class="datae">{{ $datas[0]->tiketKeterangan}}</td>
                            </tr>
                            <tr>
                                <td>Teknisi *</td>
                                <td>:</td>
                                <td colspan="4" class="datae">
                                    <div class="input-group col-md-6">
                                        <input type="text" name="nikTeknisi" id="nikTeknisi" class="form-control" required readonly="true">
                                        <input type="hidden" name="namaTeknisi" id="namaTeknisi" readonly="true" class="form-control" required>
                                        <input type="hidden" name="emailTeknisi" id="emailTeknisi" readonly="true" class="form-control" required>
                                        <input type="hidden" name="emailTeknisi2" id="emailTeknisi2" readonly="true" class="form-control" required>
                                        <a href="#" data-toggle="modal" data-target="#myModalTeknisi" style="text-decoration:none">
                                        <div class="input-group-append bg-primary border-primary">
                                            <span class="input-group-text bg-transparent">                                    
                                                <i class="fa fa-search text-white"></i>
                                            </span>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-md-6" id="namaTeknisi2"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan *</td>
                                <td>:</td>
                                <td colspan="4" class="datae">
                                    <textarea class="form-control" required id="keterangan" name="keterangan" rows="6"></textarea>   
                                </td>
                            </tr>
                        </table>
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
                                                <tr class="pilihTeknisi" data_nik="{{ $data['NIK'] }}" data_nama="{{ $data['NAMA'] }}" data_email="{{ $data['EMAIL'] }}" data_email2="{{ $data['EMAIL2'] }}">
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
