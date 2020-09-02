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
      <a href="{{ url('tiket/create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-mail-reply-all"></i> Kembali</a>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Data Tiket</h4>
                <div class="row">
                    @php
                    foreach($service as $key =>$val){
                    if($key%4 == '0'){
                        $warna = 'text-danger';
                    }elseif($key%4 == '1'){
                        $warna = 'text-warning';
                    }elseif($key%4 == '2'){
                        $warna = 'text-success';
                    }elseif($key%4 == '3'){
                        $warna = 'text-info';
                    }
                    @endphp 
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                            <div class="card card-statistics" style='background:#f2f8f9'>
                                <a href="{{ url("/tiket/create") }}/{{ $val->id_layanan }}/{{ $val->id }}" class="row" style='color:#000000;text-decoration:none'>
                                <div class="card-body">
                                  <div class="clearfix">
                                    <div class="float-left">
                                      <i class="{{ $val->gambar }} {{ $warna }} icon-lg"></i>
                                    </div>
                                    <div class="float-right">
                                      <p class="mb-0 text-right">{{ $val->ServiceName }}</p>
                                    </div>
                                  </div>
                                  <p class="text-muted mt-3 mb-0">
                                    <!--<i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>-->
                                    {{ $val->keterangan }}
                                  </p>
                                </div>
                                </a>
                            </div>
                        </div>
                    @php
                    }
                    @endphp 

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
