@section('js')

<script type="text/javascript">

$(document).ready(function() {
    $(".users").select2();
});

</script>
@stop

@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('service.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
<div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Tambah Master Service</h4>

                      <div class="form-group">
                        <label for="id_layanan" class="col-md-4 control-label">Layanan</label>
                            <div class="col-md-6">
                                <select class="form-control"  required id="id_layanan" name="id_layanan">
                                <option value="">Silakan Pilih</option>
                            @foreach($layanan as $key => $val)
                                <option value="{{ $val->id }}">{{ $val->nama_layanan }}</option>
                            @endforeach
                                </select>
                            </div>
                      </div>    

                        <div class="form-group{{ $errors->has('ServiceName') ? ' has-error' : '' }}">
                            <label for="ServiceName" class="col-md-4 control-label">Nama Master Service</label>
                            <div class="col-md-6">
                                <input id="ServiceName" type="text" class="form-control" name="ServiceName" value="{{ old('ServiceName') }}" required>
                                @if ($errors->has('ServiceName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ServiceName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                    <div class="form-group">
                        <label for="min_eselon" class="col-md-4 control-label">Eselon</label>
                        <div class="col-md-6">
                            <select class="form-control" required id="min_eselon" name="min_eselon">
                              <option value="">Silakan Pilih</option>
                              <option value="3">Eselon 3</option>
                              <option value="4">Eselon 4</option>

                            </select>
                        </div>
                    </div>



                        <div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }}">
                            <label for="keterangan" class="col-md-4 control-label">Keterangan</label>
                            <div class="col-md-6">
                                <input id="keterangan" type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" required>
                                @if ($errors->has('keterangan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('keterangan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                    <div class="form-group">
                        <label for="ServiceStatus" class="col-md-4 control-label">Status</label>
                        <div class="col-md-6">
                            <select class="form-control" required id="ServiceStatus" name="ServiceStatus">
                              <option value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                        <button type="submit" class="btn btn-primary" id="submit">
                                    Submit
                        </button>
                        <button type="reset" class="btn btn-danger">
                                    Reset
                        </button>
                        <a href="{{route('service.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>
</form>
@endsection