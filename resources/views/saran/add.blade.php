@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header p-1" style="background: #000080;"></div>
                <div class="card-body">
                    <h4 class="card-title">Tambah Kritik / Saran</h4>
                    <form method="POST" action="{{ url('saran/create') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('uraian') ? ' has-error' : '' }}">
                            <label for="uraian" class="col-md-4 control-label">Uraian</label>
                            <div class="col-md-6">
                                <textarea id="uraian" class="form-control" required name="uraian" rows="10"></textarea>
                                @if ($errors->has('uraian'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('uraian') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success mr-2" id="simpan">Simpan</button>
                                <button type='reset' class="btn btn-light">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
