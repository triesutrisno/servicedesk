
@push('styles')
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }

        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }

        ul.timeline>li {
            margin: 20px 0;
            padding-left: 20px;
        }

        ul.timeline>li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
    </style>
@endpush
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable({
                "iDisplayLength": 20
            });
            $('[data-toggle=confirmation]').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                // other options
            });

            $('[data-name=btn_reject]').on('click', function(e) {
                e.preventDefault();
                var id_tiket = $(this).attr("data-id");
                Swal.fire({
                    title: 'Keterangan Reject',
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Reject',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((reason) => {
                    if (reason.value) {
                        fetch('/approvetiket/reject/' + id_tiket, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'url': '/payment',
                                    "X-CSRF-Token": document.querySelector(
                                        'input[name=_token]').value
                                },
                                body: JSON.stringify({
                                    'id_tiket': id_tiket,
                                    'reject_reason': reason.value
                                })
                            })
                            .then(response => {
                                Swal.fire({
                                    title: "Success",
                                    text: "berhasil reject!",
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    location.reload();
                                })
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: "Error",
                                    text: error
                                })
                            })

                    }
                })
            });
        });
    </script>
    <script src="{{ asset('bs4/js/bootstrap-confirmation.js') }}"></script>
@stop
@extends('layouts.app')

@section('content')
    <div class="flex-row">
        <div class="form-group">
            <a href="{{ url('approvetiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat
                Data</a>
            @if ($data[0]->tiketStatus == '1')
                <form action="{{ url('approvetiket/approve') }}/{{ $data[0]->tiketId }}" method="post" class="d-inline">
                    @method('patch')
                    @csrf
                    <button class="btn btn-info btn-rounded btn-fw" data-toggle="confirmation" data-singleton="true"
                        data-title="Anda yakin approve data ini ?">
                        <i class="fa fa-check-square icon-lg"></i> Approve
                    </button>
                </form>
                <form action="{{ url('approvetiket/reject') }}/{{ $data[0]->tiketId }}" method="post" class="d-inline">
                    @method('patch')
                    @csrf
                    <button class="btn btn-danger btn-rounded btn-fw" data-name="btn_reject" data-id="{{ $data[0]->tiketId }}">
                        <i class="fa fa fa-times-rectangle-o icon-lg"></i> Reject
                    </button>
                </form>
            @endif
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-header p-1" style="background: #000080;"></div>
                <div class="card-body">
                    <h4 class="card-title">Data Tiket</h4>
                    <table class="table-responsive">
                        <tbody>
                            <tr>
                                <td width="10%">Nomor</td>
                                <td width="2%">:</td>
                                <td>{{ $data[0]->kode_tiket }}</td>
                                <td width="10%">Layanan</td>
                                <td width="2%">:</td>
                                <td>{{ $data[0]->nama_layanan }}</td>
                            </tr>
                            <tr>
                                <td>Service</td>
                                <td>:</td>
                                <td>{{ $data[0]->ServiceName }}</td>
                                <td>Sub Service</td>
                                <td>:</td>
                                <td>{{ $data[0]->ServiceSubName }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Buat</td>
                                <td>:</td>
                                <td>{{ date('d-m-Y H:i', strtotime($data[0]->created_at)) }}</td>
                                <td>File</td>
                                <td>:</td>
                                <td>
                                    @if ($data[0]->file != '')
                                        <a href="{{ url('/images/fileTiket') }}/{{ $data[0]->file }}">Lampiran</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>UserBy</td>
                                <td>:</td>
                                <td>{{ $data[0]->name }}</td>
                                <td>No Hp</td>
                                <td>:</td>
                                <td>{{ $data[0]->noHp }}</td>
                            </tr>
                            <tr>
                                <td>Teknisi</td>
                                <td>:</td>
                                <td>{{ $data[0]->namaTeknisi }}</td>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($data[0]->tiketStatus == '1')
                                        <label class="badge badge-warning">open</label>
                                    @elseif($data[0]->tiketStatus == '2')
                                        <label class="badge badge-warning">Diapprove Atasan Unit</label>
                                    @elseif($data[0]->tiketStatus == '3')
                                        <label class="badge badge-danger">Ditolak Atasan Unit</label>
                                    @elseif($data[0]->tiketStatus == '4')
                                        <label class="badge badge-success">Disetujui</label>
                                    @elseif($data[0]->tiketStatus == '5')
                                        <label class="badge badge-danger">Ditolak</label>
                                    @elseif($data[0]->tiketStatus == '6')
                                        <label class="badge badge-info">Dikerjakan</label>
                                    @elseif($data[0]->tiketStatus == '7')
                                        <label class="badge badge-primary">Selesai</label>
                                    @elseif($data[0]->tiketStatus == '8')
                                        <label class="badge badge-dark">Close</label>
                                    @elseif($data[0]->tiketStatus == '9')
                                        <label class="badge badge-warning">Pending</label>
                                    @elseif($data[0]->tiketStatus == '10')
                                        <label class="badge badge-danger">Cancel</label>
                                    @elseif($data[0]->tiketStatus == '11')
                                        <label class="badge badge-warning">Forward</label>
                                    @endif
                                    &nbsp;
                                    @if ($data[0]->progresProsen != '')
                                        <label class="badge badge-success">{{ $data[0]->progresProsen }}%</label>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td colspan="4" style="white-space:pre">{{ $data[0]->tiketKeterangan }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#detailApproval"
                                role="tab" aria-controls="home" aria-selected="true">Detail Approval Tiket</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">Histori Tiket</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#lain-lain" role="tab"
                                aria-controls="lain-lain" aria-selected="false">Lain - Lain</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!--Start Detail Approval Tiket -->
                        <div class="tab-pane fade show active" id="detailApproval" role="tabpanel"
                            aria-labelledby="home-tab">
                            <p>&nbsp;</p>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="10%">Nama Atasan</td>
                                        <td width="2%">:</td>
                                        <td>{{ $data[0]->namaAtasan }} - {{ $data[0]->tiketNikAtasan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Approve</td>
                                        <td>:</td>
                                        <td>{{ $data[0]->tiketApprove }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Approve</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data[0]->tiketTglApprove != '')
                                                {{ date('d-m-Y H:i', strtotime($data[0]->tiketTglApprove)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="10%">PIC Unit Tujuan</td>
                                        <td width="2%">:</td>
                                        <td>{{ $data[0]->namaPIC }} - {{ $data[0]->tiketNikAtasanService }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Approve Unit Tujuan</td>
                                        <td>:</td>
                                        <td>{{ $data[0]->tiketApproveService }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Approve Unit Tujuan</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data[0]->tiketTglApproveService != '')
                                                {{ date('d-m-Y H:i', strtotime($data[0]->tiketTglApproveService)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan Reject</td>
                                        <td>:</td>
                                        <td>
                                            {{ $data[0]->reject_reason }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--End Detail Approval Tiket -->
                        <!--Start Histori Tiket -->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <p>&nbsp;</p>
                            <div class="col-md-6">
                                <!--<h4>Latest News</h4>-->
                                <ul class="timeline">
                                    @foreach ($histori as $dtHistori)
                                        <li>
                                            <a href="#">
                                                {{ $dtHistori->progresNama }} &nbsp;
                                                @if ($dtHistori->progresProsen != '')
                                                    <label
                                                        class="badge badge-success">{{ $dtHistori->progresProsen }}%</label>
                                                @endif
                                            </a>
                                            <a href="#"
                                                class="float-right">{{ date('d-m-Y H:i', strtotime($dtHistori->created_at)) }}</a>
                                            <p>{{ $dtHistori->keterangan }}</p>
                                            @if ($dtHistori->tglRTL != '')
                                                Tgl RTL : {{ date('d-m-Y H:i', strtotime($dtHistori->tglRTL)) }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!--End Histori Tiket -->
                        <!--Start Lain-Lain -->
                        <div class="tab-pane fade" id="lain-lain" role="tabpanel" aria-labelledby="profile-tab">
                            <p>&nbsp;</p>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="10%">Nama Akun</td>
                                        <td width="2%">:</td>
                                        <td>{{ $data[0]->namaAkun }}</td>
                                    </tr>
                                    <tr>
                                        <td>Password Akun</td>
                                        <td>:</td>
                                        <td>{{ $data[0]->passwordAkun }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Wawancara</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data[0]->tglWawancara != '')
                                                {{ date('d-m-Y H:i', strtotime($data[0]->tglWawancara)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Mulai Mengerjakan</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data[0]->tglMulaiMengerjakan != '')
                                                {{ date('d-m-Y H:i', strtotime($data[0]->tglMulaiMengerjakan)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Target Selesai</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data[0]->tglSelesaiMengerjakan != '')
                                                {{ date('d-m-Y H:i', strtotime($data[0]->tglSelesaiMengerjakan)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Implementasi</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data[0]->tglImplementasi != '')
                                                {{ date('d-m-Y H:i', strtotime($data[0]->tglImplementasi)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nama Lengkap User Yang Diminta</td>
                                        <td>:</td>
                                        <td>{{ $data[0]->namaLengkap }}</td>
                                    </tr>

                                    <tr>
                                        <td>NIK Lengkap User Yang Diminta</td>
                                        <td>:</td>
                                        <td>{{ $data[0]->nikLengkap }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--End Lain-Lain -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
