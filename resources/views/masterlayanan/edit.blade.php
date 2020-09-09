@section('js')

<script type="text/javascript">

$(document).ready(function() {
    $(".users").select2();
});

</script>
@stop

@extends('layouts.app')

@section('content')

<form action="{{ route('masterlayanan.update', $data->id) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('put') }}
<div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Edit Master Layanan</h4>
                      
                      <div class="form-group{{ $errors->has('kode_layanan') ? ' has-error' : '' }}">
                            <label for="kode_layanan" class="col-md-4 control-label">Kode Layanan</label>
                            <div class="col-md-6">
                                <input id="kode_layanan" type="text" class="form-control" name="kode_layanan" value="{{ $data->kode_layanan }}" required>
                                @if ($errors->has('kode_layanan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kode_layanan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('nama_layanan') ? ' has-error' : '' }}">
                            <label for="nama_layanan" class="col-md-4 control-label">Nama Master Layanan</label>
                            <div class="col-md-6">
                                <input id="nama_layanan" type="text" class="form-control" name="nama_layanan"  value="{{ $data->nama_layanan }}" required>
                                @if ($errors->has('nama_layanan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama_layanan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kode_biro') ? ' has-error' : '' }}">
                            <label for="kode_biro" class="col-md-4 control-label">Kode Biro</label>
                            <div class="col-md-6">
                                <input id="kode_biro" type="text" class="form-control" name="kode_biro" value="{{ $data->kode_biro }}" required>
                                @if ($errors->has('kode_biro'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kode_biro') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }}">
                            <label for="keterangan" class="col-md-4 control-label">Keterangan</label>
                            <div class="col-md-6">
                                <input id="keterangan" type="text" class="form-control" name="keterangan"  value="{{ $data->keterangan }}" required>
                                @if ($errors->has('keterangan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('keterangan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                        <label for="status_layanan" class="col-md-4 control-label">Status</label>
                        <div class="col-md-6">
                            <select class="form-control" required id="status_layanan" name="status_layanan">
                              <option value="1">Aktif</option>
                              <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>



                        <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }} " style="margin-bottom: 20px;">
                            
                            <div class="col-md-6">
                            
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">
                                    Ubah
                        </button>
                        <button type="reset" class="btn btn-danger">
                                    Reset
                        </button>
                        <a href="{{route('masterlayanan.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>
</form>
@endsection