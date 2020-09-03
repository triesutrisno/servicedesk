@section('js')

<script type="text/javascript">

$(document).ready(function() {
    $(".users").select2();
});

</script>
@stop

@extends('layouts.app')

@section('content')

<form action="{{ route('service.update', $data->id) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('put') }}
<div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Edit Master Service</h4>
                      
                        <div class="form-group{{ $errors->has('ServiceName') ? ' has-error' : '' }}">
                            <label for="ServiceName" class="col-md-4 control-label">Nama Master Service</label>
                            <div class="col-md-6">
                                <input id="ServiceName" type="text" class="form-control" name="ServiceName" value="{{ $data->ServiceName }}" required>
                                @if ($errors->has('ServiceName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ServiceName') }}</strong>
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
                        <a href="{{route('service.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>
</form>
@endsection