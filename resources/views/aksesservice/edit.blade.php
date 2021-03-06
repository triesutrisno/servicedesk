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
                      <h4 class="card-title">Edit Master masterlayanan</h4>
                      
                        <div class="form-group{{ $errors->has('masterlayananName') ? ' has-error' : '' }}">
                            <label for="masterlayananName" class="col-md-4 control-label">Nama Master masterlayanan</label>
                            <div class="col-md-6">
                                <input id="masterlayananName" type="text" class="form-control" name="masterlayananName" value="{{ $data->nama_layanan }}" required>
                                @if ($errors->has('masterlayananName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('masterlayananName') }}</strong>
                                    </span>
                                @endif
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