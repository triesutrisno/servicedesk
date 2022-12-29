@extends('layouts.app')

@php
    $statusOpt = [
        1 => 'Baru',
        2 => 'Approve Atasan',
        3 => 'Ditolak Atasan',
        4 => 'Approve Ka.Unit Service',
        5 => 'Ditolak Ka.Unit Service',
        6 => 'Dikerjakan',
        7 => 'Selesai',
        8 => 'Close',
        9 => 'Pending',
        10 => 'Cancel',
        11 => 'Diforward',
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Tiket</h4>
                    <div class="col-lg-12 alert alert-warning">
                        <form action="{{ url('/tiket2') }}" method="get">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-small">Nomer : </label>
                                    <input type="text" name="nomer" class="form-control" autocomplete="off"
                                        value="{{ $param->get('nomer') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-small">Nama : </label>
                                    <input type="text" name="nama" class="form-control" autocomplete="off"
                                        value="{{ $param->get('nama') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-small">Status : </label>
                                    <select id="status" name="status[]" class="form-control js-example basic-multiple"
                                        multiple="multiple" style="width: 100%">
                                        @foreach ($statusOpt as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-small">Tgl Create : </label>
                                    <input type="text" name="tgl_create" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-small">Tgl Update : </label>
                                    <input type="text" name="tgl_update" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-small">Jenis : </label>
                                    <select id="jenis" name="jenis[]" class="form-control js-example basic-multiple"
                                        multiple="multiple" style="width: 100%">
                                        @foreach ($param->get('jenis_opt') as $key => $jenis)
                                            <option value="{{ $key }}">{{ $jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <br />
                                    <button type="submit" class="btn btn-success mr-2">Cari</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="table-responsive">
                        {{ $dataTable->table(['id' => 'tableId', 'class' => 'table table-striped']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-html5-2.3.3/b-print-2.3.3/sp-2.1.0/sl-1.5.0/datatables.min.css" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.13.1/b-2.3.3/b-colvis-2.3.3/b-html5-2.3.3/b-print-2.3.3/sp-2.1.0/sl-1.5.0/datatables.min.js">
    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{ $dataTable->scripts() }}

    <script>
        var tgl_create_dp = $('input[name="tgl_create"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        tgl_create_dp.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        tgl_create_dp.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        var tgl_update_dp = $('input[name="tgl_update"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        tgl_update_dp.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        tgl_update_dp.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        let searchParams = new URLSearchParams(window.location.search);

        let tgl_create_param = searchParams.get('tgl_create');
        if (tgl_create_param) {
            tgl_create_dp.val(tgl_create_param);
            tgl_create_param = tgl_create_param.split('-');
            tgl_create_dp.data('daterangepicker').setStartDate(tgl_create_param[0]);
            tgl_create_dp.data('daterangepicker').setEndDate(tgl_create_param[1]);
        }

        let tgl_update_param = searchParams.get('tgl_update');
        if (tgl_update_param) {
            tgl_update_dp.val(tgl_update_param);
            tgl_update_param = tgl_update_param.split('-');
            tgl_update_dp.data('daterangepicker').setStartDate(tgl_update_param[0]);
            tgl_update_dp.data('daterangepicker').setEndDate(tgl_update_param[1]);
        }

        var statusParam = searchParams.getAll('status[]');
        $('#status').val(statusParam);
        $('#status').select2();

        var jenisParam = searchParams.getAll('jenis[]');
        $('#jenis').val(jenisParam);
        $('#jenis').select2();
    </script>
@endpush
