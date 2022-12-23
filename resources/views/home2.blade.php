{{-- {{dd($dataGraph1->flatten())}} --}}


@extends('layouts.app')

@section('content')
    <div class="flex-row">
        <div class="form-group text-center">
            <a href="{{ url('tiket/create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Open
                Ticket</a>

            <a href="{{ url('tiket') }}" class="btn btn-danger btn-rounded btn-fw"><i class="fa fa-book"></i> Close Ticket</a>
        </div>
    </div>
    <div class="row">
        <!-- First Row -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin ">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-book text-success icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Masuk Hari Ini</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketMasukHariIni, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang dibuat hari ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin ">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-book text-success icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Masuk Bulan Ini</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketMasukBulanIni, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang dibuat bulan ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin ">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-book text-success icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Masuk Tahun Ini</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketMasukTahunIni, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang dibuat tahun ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Second Row -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-receipt text-success icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Close Hari Ini</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketCloseHariIni, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket dengan status close, selesai, cancel
                            hari ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-receipt text-success icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Close Bulan Ini</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketCloseBulanIni, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket dengan status close, selesai, cancel
                            bulan ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-receipt text-success icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Close Tahun Ini</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketCloseTahunIni, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket dengan status close, selesai,
                            cancel tahun ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Third Row -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-clipboard-check text-warning icon-lg"
                                    style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Open</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketOpen, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket masih belum selesai hingga saat ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-close-box text-danger icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Cancel / Reject</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketCancelReject, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang dicancel dan direject hingga
                            saat ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-pause-circle text-primary icon-lg"
                                    style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Pending</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketPending, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang pending karena berbagai hal
                            hingga saat ini
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Fourth Row -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 grid-margin">
            <a href="{{ url('/home/detail/1') }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-timer-sand text-warning icon-lg" style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Belum Approve</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ number_format($tiketBlmApprove, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Tiket yang belum juga diapprove oleh
                            atasan peminta hingga saat ini
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center><h3>Tiket masuk per bulan</h3><center>
            <canvas id="graph1" height="250"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center><h3>Tiket Per Status</h3><center>
            <canvas id="graphByStatus" height="250"></canvas>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = {
                labels: {{ $dataGraph1->keys() }},
                datasets: [{
                    label: 'Bulan',
                    data: {{ $dataGraph1->flatten() }},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            };
            var options = {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Bar Chart'
                    }
                }
            };

            if ($("#graph1").length) {
                var graph1Canvas = $("#graph1").get(0).getContext("2d");
                var graph1 = new Chart(graph1Canvas, {
                    type: 'bar',
                    data: data,
                    options: options
                });
            }

        });
    </script>
@endsection
