@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#tableTiket').DataTable({
      "iDisplayLength": 50
    });
} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="form-group">
      <a href="{{ url('tiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
      <a href="{{ url('tiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-mail-reply-all"></i> Kembali</a>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ubah Data Tiket</h4>                                  
                <form method="POST" action="{{ url('tiket/edit') }}/{{ $tiket[0]['tiketId'] }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('kode_tiket') ? ' has-error' : '' }}">
                        <label for="kode_tiket" class="col-md-4 control-label">No Tiket</label>
                        <div class="col-md-6">
                            {{ $tiket[0]['kode_tiket'] }}                           
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketLayanan" class="col-md-4 control-label">Atasan</label>
                      <div class="col-md-6">
                        <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                        {{ session('infoUser')['AL_NAMA'] }} ({{ session('infoUser')['AL_NIK'] }})
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketLayanan" class="col-md-4 control-label">Layanan</label>
                      <div class="col-md-6">
                        <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                        {{ $tiket[0]['layanan'][0]['nama_layanan'] }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketService" class="col-md-4 control-label">Service</label>
                      <div class="col-md-6">
                        <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i> 
                        {{ $tiket[0]['service'][0]['ServiceName'] }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="subServiceId" class="col-md-4 control-label">Sub Service</label>
                      <div class="col-md-6">
                        <select class="form-control"  required id="subServiceId" name="subServiceId">
                            <option value="">Silakan Pilih</option>
                            @foreach($subService as $key => $val)
                                <option value="{{ $val->id }}" {{ $tiket[0]['subServiceId'] == $val->id ? 'selected' : '' }}>{{ $val->ServiceSubName }}</option>
                            @endforeach                          
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketPrioritas" class="col-md-4 control-label">Prioritas</label>
                      <div class="col-md-6">
                          <select class="form-control" required id="tiketPrioritas" name="tiketPrioritas">
                            <option value="">Silakan Pilih</option>
                            <option value="1"{{ $tiket[0]['tiketPrioritas']=='1' ? 'selected' : '' }}>Biasa</option>
                            <option value="2"{{ $tiket[0]['tiketPrioritas']=='2' ? 'selected' : '' }}>Segera</option>
                            <option value="3"{{ $tiket[0]['tiketPrioritas']=='3' ? 'selected' : '' }}>Prioritas dan Penting</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="tiketKeterangan" class="col-md-4 control-label">Keterangan</label>
                        <div class="col-md-6">
                            <textarea class="form-control" required id="tiketKeterangan" name="tiketKeterangan" rows="6">{{ trim($tiket[0]['tiketKeterangan']) }}</textarea>                            
                        </div>
                    </div>
                    <!--
                    div class="form-group">
                        <label for="tiketFile" class="col-md-4 control-label">File upload</label>                       
                        <div class="input-group col-md-6">
                          <input type="file" id="tiketFile" name="tiketFile" class="file-upload-default">
                        </div>
                    </div>
                    -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-success mr-2">Simpan</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
@endsection
