@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tableTiket').DataTable({
          "iDisplayLength": 50
        });
        
        //$('#tglWawancara').datetimepicker({
        //    autoclose: true,
        //    format: 'yyyy-mm-dd',
            //todayHighlight: true,
            //format: "mm-yyyy",
            //viewMode: "months", 
            //minViewMode: "months"
        //});
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
                <h4 class="card-title">Tambah Data Solusi Tiket</h4>                                  
                <form method="POST" action="{{ url('tugasku/solusi') }}/{{ $datas[0]->tiketDetailId }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label for="kode_tiket" class="col-md-4 control-label">No Tiket</label>
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
                                @elseif($datas[0]->tiketStatus=='7')
                                    <label class="badge badge-dark">Close</label>
                               @endif
                            </div>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="tiketKeterangan" class="col-md-4 control-label">Deskripsi Tiket</label>
                        <div class="col-md-6">{{ $datas[0]->tiketKeterangan }}</div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="col-md-4 control-label">Keterangan</label>
                        <div class="col-md-6">
                            <textarea class="form-control" required id="keterangan" name="keterangan" rows="6">{{ $datas[0]->keterangan }}</textarea>                            
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="subServiceId" class="col-md-4 control-label">Progres</label>
                      <div class="col-md-6">
                        <select class="form-control"  required id="progres" name="progres">
                            <option value="">Silakan Pilih</option>
                            @foreach($progres as $key => $value)
                                <option value="{{ $value->progresId }}"{{ $datas[0]->progresId == $value->progresId ? 'selected' : '' }}>{{ $value->progresNama }} - {{ $value->progresProsen}} %</option>
                            @endforeach                          
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="namaAkun" class="col-md-4 control-label">Nama Akun</label>                       
                        <div class="input-group col-md-6">
                          <input type="text" id="namaAkun" name="namaAkun" class="form-control" value="{{ $datas[0]->namaAkun }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="passwordAkun" class="col-md-4 control-label">Password Akun</label>                       
                        <div class="input-group col-md-6">
                          <input type="text" id="passwordAkun" name="passwordAkun" class="form-control" value="{{ $datas[0]->passwordAkun }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tglWawancara" class="col-md-4 control-label">Tanggal Wawancara</label>                       
                        <div class="input-group col-md-6">
                          <input type="date" id="tglWawancara" name="tglWawancara" class="form-control" value="{{ $datas[0]->tglWawancara }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tglMulaiMengerjakan" class="col-md-4 control-label">Tanggal Mulai Mengerjakan</label>                       
                        <div class="input-group col-md-6">
                          <input type="date" id="tglMulaiMengerjakan" name="tglMulaiMengerjakan" class="form-control" value="{{ $datas[0]->tglMulaiMengerjakan }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tglSelesaiMengerjakan" class="col-md-4 control-label">Tanggal Selesai Mengerjakan</label>                       
                        <div class="input-group col-md-6">
                          <input type="date" id="tglSelesaiMengerjakan" name="tglSelesaiMengerjakan" class="form-control" value="{{ $datas[0]->tglSelesaiMengerjakan }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tglImplementasi" class="col-md-4 control-label">Tanggal Implementasi</label>                       
                        <div class="input-group col-md-6">
                          <input type="date" id="tglImplementasi" name="tglImplementasi" class="form-control" value="{{ $datas[0]->tglImplementasi }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tglPelatihan" class="col-md-4 control-label">Tanggal Pelatihan</label>                       
                        <div class="input-group col-md-6">
                          <input type="date" id="tglPelatihan" name="tglPelatihan" class="form-control" value="{{ $datas[0]->tglPelatihan }}">
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
@endsection
