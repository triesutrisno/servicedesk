@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 50
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="flex-row">
    <div class="form-group text-center">
        <a href="{{ url('tiket/create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Open Ticket</a>
        
        <a href="{{ url('tiket') }}" class="btn btn-danger btn-rounded btn-fw"><i class="fa fa-book"></i> Close Ticket</a>
    </div>
</div>
<div class="row">
    <!-- Hari ini -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics" >
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-book text-success icon-lg" style="width: 40px;height: 40px;"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Masuk  Hari Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('created_at', '>=', date('Y-m-d'))->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang masuk dihari ini
                </p>
            </div>
        </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/2') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-receipt text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Selesai Hari Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('updated_at', '>=', date('Y-m-d'))->whereIn('tiketStatus',['7','10'])->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Sudah yang sudah tangani hari ini
                </p>
            </div>
        </div>
        </a>
    </div>            
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/3') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-account-location text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Close Hari Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('updated_at', '>=', date('Y-m-d'))->whereIn('tiketStatus',['3','5','8','10'])->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-account mr-1" aria-hidden="true"></i> Tiket close oleh user bulan ini
                </p>
            </div>
        </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/4') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-poll-box text-danger icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Tiket Open Hari ini</p>
                    <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('created_at', '>=', date('Y-m-d'))->where('tiketStatus','<',7)->count()}}</h3>
                    </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Tiket yang belum ditangani hari ini
              </p>
            </div>
        </div>
        </a>
    </div>
    <!-- Bulan ini -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/5') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-cloud-upload text-warning icon-lg" style="width: 40px;height: 40px;"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Masuk Bulan Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{ $tikets->where('created_at', '>=', date('Y-m-01'))->count() }}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang masuk bulan ini
                </p>
            </div>
        </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/6') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-clipboard-arrow-down text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Selesai Bulan Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('updated_at', '>=', date('Y-m-01'))->whereIn('tiketStatus',['7','10'])->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Sudah ditangani bulan ini
                </p>
            </div>
        </div>
        </a>
    </div>            
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/7') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-close-circle text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Close Bulan Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('updated_at', '>=', date('Y-m-01'))->whereIn('tiketStatus',['3','5','8','10'])->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-account mr-1" aria-hidden="true"></i> Tiket close oleh user bulan ini
                </p>
            </div>
        </div
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/8') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-houzz-box text-success icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Tiket Open Bulan Ini</p>
                    <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('created_at', '>=', date('Y-m-01'))->where('tiketStatus','<',7)->count()}}</h3>
                    </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Tiket yang belum ditangani bulan ini
              </p>
            </div>
        </div>
        </a>
    </div>  
    <!-- Tahun ini -->
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/9') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-database-plus text-info icon-lg" style="width: 40px;height: 40px;"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Masuk Tahun Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{ $tikets->where('created_at', '>=', date('Y-01-01'))->count() }}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang masuk tahun ini
                </p>
            </div>
        </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/10') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-receipt text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Selesai Tahun Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('updated_at', '>=', date('Y-01-01'))->whereIn('tiketStatus',['7','10'])->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Sudah ditangani tahun ini
                </p>
            </div>
        </div>
        </a>
    </div>            
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/11') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-account-location text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Tiket Close Tahun Ini</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('updated_at', '>=', date('Y-01-01'))->whereIn('tiketStatus',['3','5','8','10'])->count()}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-account mr-1" aria-hidden="true"></i> Tiket close oleh user tahun ini
                </p>
            </div>
        </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <a href="{{ url('/home/detail/12') }}" style='color:#000000;text-decoration:none'>
        <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-poll-box text-warning icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Tiket Open Tahun</p>
                    <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$tikets->where('created_at', '>=', date('Y-01-01'))->where('tiketStatus','<',7)->count()}}</h3>
                    </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Tiket yang belum ditangani tahun ini
              </p>
            </div>
        </div>
        </a>
    </div>
</div>

@endsection
