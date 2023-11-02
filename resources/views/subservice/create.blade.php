@section('js')

<script type="text/javascript">
    $(document).ready(function () {
        $(".users").select2();
    });

</script>
@stop

@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('subservice.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header p-1" style="background: #000080;"></div>
                        <div class="card-body">
                            <h4 class="card-title">Tambah Sub Service</h4>


                            <div class="form-group">
                                <label for="ServiceIDf" class="col-md-4 control-label">Service</label>
                                <div class="col-md-6">
                                    <select class="form-control" required id="ServiceIDf" name="ServiceIDf">
                                        <option value="">Silakan Pilih</option>
                                        @foreach ($service as $key => $val)
                                        <option value="{{ $val->id }}">{{ $val->ServiceName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('ServiceSubName') ? ' has-error' : '' }}">
                                <label for="ServiceSubName" class="col-md-4 control-label">Nama Sub Service</label>
                                <div class="col-md-6">
                                    <input id="ServiceSubName" type="text" class="form-control" name="ServiceSubName"
                                        value="{{ old('ServiceSubName') }}" required>
                                    @if ($errors->has('ServiceSubName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ServiceSubName') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="id_unit" class="col-md-4 control-label">Unit</label>
                                <div class="col-md-6">
                                    <select class="form-control" required id="id_unit" name="id_unit">
                                        <!-- <option value="1">SYSTEM DEVELOPMENT</option>
                                        <option value="2">IT INFRASTRUCTURE MANAGEMENT</option> -->
                                        @foreach ($unit as $key => $val)
                                        <option value="{{ $val->id }}">{{ $val->nama_unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ServiceSubStatus" class="col-md-4 control-label">Status</label>
                                <div class="col-md-6">
                                    <select class="form-control" required id="ServiceSubStatus" name="ServiceSubStatus">
                                        <option value="1">Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="ServiceSubStatus" class="col-md-4 control-label">Standart
                                    Penyelesaian</label>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" placeholder="Standart Penyelesaian" aria-label="Standart Penyelesaian" aria-describedby="basic-addon2" name="StandartPenyelesaian">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Jam</span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            

                            <button type="submit" class="btn btn-primary" id="submit">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-danger">
                                Reset
                            </button>
                            <a href="{{ route('subservice.index') }}" class="btn btn-light pull-right">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
