@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Kritik Saran</h4>
                    <div class="row col-md-12">
                    <a href={{ url('saran/create') }} class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i>
                        Tambah Kritik Saran</a>
                    </div>
                    <br><br>
                    <div class="table-responsive">
                        {{ $dataTable->table(['id' => 'tableId', 'class' => 'table table-striped']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}" />
    <script type="text/javascript" src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />

    {{ $dataTable->scripts() }}
@endpush
