@section('js')

<script type="text/javascript">

$(document).ready(function() {
    $(".users").select2();
});

</script>
@stop

@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('mprogress.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
<div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Tambah Master Progress</h4>

                        <div class="form-group{{ $errors->has('progresNama') ? ' has-error' : '' }}">
                            <label for="progresNama" class="col-md-4 control-label">Nama Master Progress</label>
                            <div class="col-md-6">
                                <input id="progresNama" type="text" class="form-control" name="progresNama"  required>
                                @if ($errors->has('progresNama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('progresNama') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kode_biro') ? ' has-error' : '' }}">
                            <label for="progresProsen" class="col-md-4 control-label">Prosentase</label>
                            <div class="col-md-6">
                                <input id="progresProsen" type="text" class="form-control" name="progresProsen"  required>
                                @if ($errors->has('progresProsen'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('progresProsen') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="progresStatus" class="col-md-4 control-label">Status</label>
                        <div class="col-md-6">
                            <select class="form-control" required id="progresStatus" name="progresStatus">
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
                        <a href="{{route('masterlayanan.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>
</form>
@endsection