{{-- {!! $dataGraphByStatus->keys() !!} --}}

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
            <a href="{{ route('tiket2.index', ['tgl_create' => date('m/d/Y') . ' - ' . date('m/d/Y')]) }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ route('tiket2.index', ['tgl_create' =>date('m/01/Y') .' - ' .Carbon\Carbon::now()->endOfMonth()->format('m/d/Y')]) }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ route('tiket2.index', ['tgl_create' => date('01/01/Y') . ' - ' . date('12/31/Y')]) }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ 'tiket2?tgl_update=' . date('01/01/Y') . ' - ' . date('12/31/Y') . '&status[]=7&status[]=8' }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ 'tiket2?tgl_update=' .date('m/01/Y') .' - ' .Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') .'&status[]=7&status[]=8' }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ 'tiket2?tgl_update=' . date('01/01/Y') . ' - ' . date('12/31/Y') . '&status[]=7&status[]=8' }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ 'tiket2?status[]=4&status[]=6&status[]=11' }}" style='color:#000000;text-decoration:none'>
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-clipboard-check text-warning icon-lg"
                                    style="width: 40px;height: 40px;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Tiket Dikerjakan</p>
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
            <a href="{{ 'tiket2?tgl_update=' . date('01/01/Y') . ' - ' . date('12/31/Y') . '&status[]=3&status[]=5&status[]=10' }}"
                style='color:#000000;text-decoration:none'>
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
            <a href="{{ 'tiket2?status[]=9' }}" style='color:#000000;text-decoration:none'>
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
            <a href="{{ 'tiket2?status[]=1' }}" style='color:#000000;text-decoration:none'>
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
            <center>
                <h3>Tiket masuk per bulan</h3>
                <center>
                    <canvas id="graph1" height="350"></canvas>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Service</h3>
                <center>
                    <canvas id="graphByService1" height="350"></canvas>
        </div>
    </div>
    <div class="row">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin">
            <center>
                <h3>Tiket Per Service</h3>
                <center>
                    <canvas id="graphByService2" height="150"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Status</h3>
                <center>
                    <canvas id="graphByStatus1" height="250"></canvas>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Status</h3>
                <center>
                    <canvas id="graphByStatus2" height="250"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Teknisi</h3>
                <center>
                    <canvas id="graphByTeknisi1" height="250"></canvas>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Teknisi</h3>
                <center>
                    <canvas id="graphByTeknisi2" height="250"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Unit</h3>
                <center>
                    <canvas id="graphByUnit1" height="250"></canvas>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin">
            <center>
                <h3>Tiket Per Unit</h3>
                <center>
                    <canvas id="graphByUnit2" height="250"></canvas>
        </div>
    </div>

    <div class="flex-row">
            <iframe src="https://dashboard.silog.co.id/public/dashboard/b9aeb033-a2b1-4d27-9c2b-c748abc84238" frameborder="0"
                width="100%" height="800" allowtransparency></iframe>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/chartjs-plugin-labels.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Graph 1
            var dataGraph1 = {
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
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(46, 192, 249, 0.2)',
                        'rgba(97, 80, 85, 0.2)',
                        'rgba(164, 74, 63, 0.2)',

                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(46, 192, 249, 1)',
                        'rgba(97, 80, 85, 1)',
                        'rgba(164, 74, 63, 1)',
                    ],
                    borderWidth: 1
                }]
            };
            var optionsGraph1 = {
                responsive: true,
                legend: {
                    position: 'bottom'
                },
                events: false,
                tooltips: {
                    enabled: false
                },
                plugins: {
                    labels: {
                        render: 'value'
                    }
                }

            };

            if ($("#graph1").length) {
                var graph1Canvas = $("#graph1").get(0).getContext("2d");
                var graph1 = new Chart(graph1Canvas, {
                    type: 'bar',
                    data: dataGraph1,
                    options: optionsGraph1
                });
            }


            // Graph ByStatus1
            const dataGraphByStatus1 = {
                labels: {!! $dataGraphByStatusPct->keys() !!},
                datasets: [{
                    label: 'Tiket Per Status',
                    data: {{ $dataGraphByStatusPct->flatten() }},
                    backgroundColor: [
                        '#ecf0f1',
                        '#7f8c8d',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#2ecc71',
                        '#9b59b6',
                        '#c0392b',
                        '#27ae60',
                        '#ecf0f1',
                        '#23CE6B',
                        '#272D2D',
                        '#226CE0'
                    ],
                }]
            };

            const optionsGraphByStatus1 = {
                type: 'pie',
                data: dataGraphByStatus1,
            };

            if ($("#graphByStatus1").length) {
                var graphByStatus1Canvas = $("#graphByStatus1").get(0).getContext("2d");
                var graphByStatus1 = new Chart(graphByStatus1Canvas, {
                    type: 'pie',
                    data: dataGraphByStatus1,
                    options: optionsGraphByStatus1
                });
            }

            // Graph ByStatus2
            var dataGraphByStatus2 = {
                labels: {!! $dataGraphByStatus->keys() !!},
                datasets: [{
                    label: 'Status',
                    data: {{ $dataGraphByStatus->flatten() }},
                    backgroundColor: [
                        '#ecf0f1',
                        '#7f8c8d',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#2ecc71',
                        '#9b59b6',
                        '#c0392b',
                        '#27ae60',
                        '#ecf0f1',
                        '#23CE6B',
                        '#272D2D',
                        '#226CE0'
                    ],
                    borderColor: [
                        '#ecf0f1',
                        '#7f8c8d',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#2ecc71',
                        '#9b59b6',
                        '#c0392b',
                        '#27ae60',
                        '#ecf0f1',
                        '#23CE6B',
                        '#272D2D',
                        '#226CE0'
                    ],
                    borderWidth: 1
                }]
            };
            var optionsGraphByStatus2 = {
                responsive: true,
                legend: {
                    position: 'top'
                },
                events: false,
                tooltips: {
                    enabled: false
                },
                scaleShowValues: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }]
                },
                plugins: {
                    labels: {
                        render: 'value'
                    }
                }

            };

            if ($("#graphByStatus2").length) {
                var graphByStatus2Canvas = $("#graphByStatus2").get(0).getContext("2d");
                var graphByStatus2 = new Chart(graphByStatus2Canvas, {
                    type: 'bar',
                    data: dataGraphByStatus2,
                    options: optionsGraphByStatus2
                });
            }

            // Graph ByService1
            const dataGraphByService1 = {
                labels: {!! $dataGraphByServicePct->keys() !!},
                datasets: [{
                    label: 'Tiket Per Service',
                    data: {{ $dataGraphByServicePct->flatten() }},
                    backgroundColor: [
                        '#9980FA',
                        '#7f8c8d',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#C4E538',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#23CE6B',
                        '#272D2D',
                        '#226CE0'
                    ],
                }]
            };

            const optionsGraphByService1 = {
                type: 'pie',
                data: dataGraphByService1,
            };

            if ($("#graphByService1").length) {
                var graphByService1Canvas = $("#graphByService1").get(0).getContext("2d");
                var graphByService1 = new Chart(graphByService1Canvas, {
                    type: 'pie',
                    data: dataGraphByService1,
                    options: optionsGraphByService1
                });
            }

            // Graph ByService2
            var dataGraphByService2 = {
                labels: {!! $dataGraphByService->keys() !!},
                datasets: [{
                    label: 'Service',
                    data: {{ $dataGraphByService->flatten() }},
                    backgroundColor: [
                        '#9980FA',
                        '#7f8c8d',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#C4E538',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#eb3b5a',
                        '#23CE6B',
                        '#272D2D',
                        '#226CE0'
                    ],
                    borderColor: [
                        '#ecf0f1',
                        '#7f8c8d',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#ecf0f1',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#eb3b5a',
                        '#23CE6B',
                        '#272D2D',
                        '#226CE0'
                    ],
                    borderWidth: 1
                }]
            };
            var optionsGraphByService2 = {
                responsive: true,
                legend: {
                    position: 'top'
                },
                events: false,
                tooltips: {
                    enabled: false
                },
                scaleShowValues: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }]
                },
                plugins: {
                    labels: {
                        render: 'value'
                    }
                }

            };

            if ($("#graphByService2").length) {
                var graphByService2Canvas = $("#graphByService2").get(0).getContext("2d");
                var graphByService2 = new Chart(graphByService2Canvas, {
                    type: 'bar',
                    data: dataGraphByService2,
                    options: optionsGraphByService2
                });
            }

            // Graph ByTeknisi1
            const dataGraphByTeknisi1 = {
                labels: {!! $dataGraphByTeknisiPct->keys() !!},
                datasets: [{
                    label: 'Tiket Per Teknisi',
                    data: {{ $dataGraphByTeknisiPct->flatten() }},
                    backgroundColor: [
                        '#9980FA',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#C4E538',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                    ],
                }]
            };

            const optionsGraphByTeknisi1 = {
                type: 'pie',
                data: dataGraphByTeknisi1,
            };

            if ($("#graphByTeknisi1").length) {
                var graphByTeknisi1Canvas = $("#graphByTeknisi1").get(0).getContext("2d");
                var graphByTeknisi1 = new Chart(graphByTeknisi1Canvas, {
                    type: 'pie',
                    data: dataGraphByTeknisi1,
                    options: optionsGraphByTeknisi1
                });
            }

            // Graph ByTeknisi2
            var dataGraphByTeknisi2 = {
                labels: {!! $dataGraphByTeknisi->keys() !!},
                datasets: [{
                    label: 'Teknisi',
                    data: {{ $dataGraphByTeknisi->flatten() }},
                    backgroundColor: [
                        '#9980FA',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#C4E538',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#eb3b5a'
                    ],
                    borderColor: [
                        '#ecf0f1',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#ecf0f1',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#eb3b5a'
                    ],
                    borderWidth: 1
                }]
            };
            var optionsGraphByTeknisi2 = {
                responsive: true,
                legend: {
                    position: 'top'
                },
                events: false,
                tooltips: {
                    enabled: false
                },
                scaleShowValues: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }]
                },
                plugins: {
                    labels: {
                        render: 'value'
                    }
                }

            };

            if ($("#graphByTeknisi2").length) {
                var graphByTeknisi2Canvas = $("#graphByTeknisi2").get(0).getContext("2d");
                var graphByTeknisi2 = new Chart(graphByTeknisi2Canvas, {
                    type: 'bar',
                    data: dataGraphByTeknisi2,
                    options: optionsGraphByTeknisi2
                });
            }

            // Graph ByUnit1
            const dataGraphByUnit1 = {
                labels: {!! $dataGraphByUnitPct->keys() !!},
                datasets: [{
                    label: 'Tiket Per Unit',
                    data: {{ $dataGraphByUnitPct->flatten() }},
                    backgroundColor: [
                        '#9980FA',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#C4E538',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                    ],
                }]
            };

            const optionsGraphByUnit1 = {
                type: 'pie',
                data: dataGraphByUnit1,
            };

            if ($("#graphByUnit1").length) {
                var graphByUnit1Canvas = $("#graphByUnit1").get(0).getContext("2d");
                var graphByUnit1 = new Chart(graphByUnit1Canvas, {
                    type: 'pie',
                    data: dataGraphByUnit1,
                    options: optionsGraphByUnit1
                });
            }

            // Graph ByUnit2
            var dataGraphByUnit2 = {
                labels: {!! $dataGraphByUnit->keys() !!},
                datasets: [{
                    label: 'Unit',
                    data: {{ $dataGraphByUnit->flatten() }},
                    backgroundColor: [
                        '#9980FA',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#C4E538',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#eb3b5a'
                    ],
                    borderColor: [
                        '#ecf0f1',
                        '#9b59b6',
                        '#f1c40f',
                        '#e74c3c',
                        '#f39c12',
                        '#c0392b',
                        '#9b59b6',
                        '#2ecc71',
                        '#27ae60',
                        '#ecf0f1',
                        '#a55eea',
                        '#2d98da',
                        '#fed330',
                        '#2bcbba',
                        '#fc5c65',
                        '#4b6584',
                        '#3867d6',
                        '#eb3b5a'
                    ],
                    borderWidth: 1
                }]
            };
            var optionsGraphByUnit2 = {
                responsive: true,
                legend: {
                    position: 'top'
                },
                events: false,
                tooltips: {
                    enabled: false
                },
                scaleShowValues: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }]
                },
                plugins: {
                    labels: {
                        render: 'value'
                    }
                }

            };

            if ($("#graphByUnit2").length) {
                var graphByUnit2Canvas = $("#graphByUnit2").get(0).getContext("2d");
                var graphByUnit2 = new Chart(graphByUnit2Canvas, {
                    type: 'bar',
                    data: dataGraphByUnit2,
                    options: optionsGraphByUnit2
                });
            }

        });
    </script>
@endsection
