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
    $(document).ready(function () {
        $('#table').DataTable({
            "iDisplayLength": 20
        });
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            // other options
        });

        $('.pilihSetuju').click(function () {
            $('#tiketId').val($(this).attr('data-tiket_id'));
        });

        $('.pilihTeknisi').click(function () {
            $('#nikTeknisi').val($(this).attr('data_nik'));
            $('#namaTeknisi').val($(this).attr('data_nama'));
            $('#namaTeknisi2').text($(this).attr('data_nama'));
            $('#emailTeknisi').val($(this).attr('data_email'));
            $('#myModalTeknisi').modal('hide');
        });
    });

</script>
<script src="{{asset('bs4/js/bootstrap-confirmation.js')}}"></script>
@stop
@extends('layouts.app')

@section('content')
<div class="flex-row">
    <div class="form-group">
        <a href="{{ url('persetujuantiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i>
            Lihat Data</a>
        @if($data[0]->tiketStatus=='2' || $data[0]->tiketStatus=='11')
        <a href="{{ url('/persetujuantiket')}}/approve/{{ $data[0]->tiketId }}"
            class="btn btn-warning btn-rounded btn-fw" title="Setuju">
            <i class="fa fa-check-square icon-lg"></i> Setuju
        </a>
        <form action="{{ url('persetujuantiket/reject') }}/{{ $data[0]->tiketId }}" method="post" class="d-inline">
            @method('patch')
            @csrf
            <button class="btn btn-danger btn-rounded btn-fw" data-toggle="confirmation" data-singleton="true"
                data-title="Anda yakin mereject data ini ?">
                <i class="fa fa fa-times-rectangle-o icon-lg"></i> Tidak Setuju
            </button>
        </form>
        <a href="{{ url('/persetujuantiket')}}/forward/{{ $data[0]->tiketId }}" class="btn btn-info btn-rounded btn-fw">
            <i class="fa fa-share icon-lg"></i> Forward
        </a>
        @endif
        @if(session('infoUser')['PERUSAHAAN'] == 'H0000000')
        <a href="{{ url('/persetujuantiket')}}/requestApproval/{{ $data[0]->tiketId }}"
            class="btn btn-success btn-rounded btn-fw">
            <i class="fa fa-user icon-lg"></i> Request Approve
        </a>
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
                                @if($data[0]->file!="")
                                <a href="{{ url('/images/fileTiket') }}/{{$data[0]->file}}">Lampiran</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>UserBy</td>
                            <td>:</td>
                            <td>{{ $data[0]->name}} @if($data[0]->tiketEmail <> '') / <a
                                        href="mailto:{{ $data[0]->tiketEmail}}">{{ $data[0]->tiketEmail}}</a>@endif</td>
                            <td>No Hp</td>
                            <td>:</td>
                            <td>{{ $data[0]->noHp}}</td>
                        </tr>
                        <tr>
                            <td>Teknisi</td>
                            <td>:</td>
                            <td>{{ $data[0]->namaTeknisi }}</td>
                            <td>Status</td>
                            <td>:</td>
                            <td>
                                @if($data[0]->tiketStatus == '1')
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
                                @if($data[0]->progresProsen!="")

                                <label class="badge badge-success">{{ $data[0]->progresProsen }}%</label>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Severity</td>
                            <td>:</td>
                            <td>
                                @if($data[0]->tiketSeverity == '1')
                                Severity Level 1
                                @elseif($data[0]->tiketSeverity == '2')
                                Severity Level 2
                                @elseif($data[0]->tiketSeverity == '3')
                                Severity Level 3
                                @elseif($data[0]->tiketSeverity == '4')
                                Severity Level 4
                                @endif
                            </td>
                            <td>Maindays</td>
                            <td>:</td>
                            <td>{{ $data[0]->tiketMaindays}} @if($data[0]->tiketMaindays <> '') Hari @endif</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td colspan="4" style="white-space:pre">{{ $data[0]->tiketKeterangan}}</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#detailApproval" role="tab"
                            aria-controls="home" aria-selected="true">Detail Approval Tiket</a>
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
                        <table class="table-responsive">
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
                                        @if($data[0]->tiketTglApprove!="")
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
                                        @if($data[0]->tiketTglApproveService!="")
                                        {{ date('d-m-Y H:i', strtotime($data[0]->tiketTglApproveService)) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan Reject</td>
                                    <td>:</td>
                                    <td style="white-space:pre">
                                        {{$data[0]->reject_reason}}
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
                                @foreach($histori as $dtHistori)
                                <li>
                                    {{ $dtHistori->progresNama }} &nbsp;
                                    @if($dtHistori->progresProsen!="")
                                    <label class="badge badge-success">{{ $dtHistori->progresProsen }}%</label>
                                    @endif
                                    @if($dtHistori->file!="")
                                    [ <a href="{{ url('/images/fileSolusiTiket') }}/{{$dtHistori->file}}">Lampiran</a> ]
                                    @endif
                                    <a href="#"
                                        class="float-right">{{ date('d-m-Y H:i', strtotime($dtHistori->created_at)) }}</a>
                                    <p>{!! nl2br(e( $dtHistori->keterangan)) !!}</p>
                                    @if($dtHistori->tglRTL!="")
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
                                        @if($data[0]->tglWawancara!="")
                                        {{ date('d-m-Y H:i', strtotime($data[0]->tglWawancara)) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Mulai Mengerjakan</td>
                                    <td>:</td>
                                    <td>
                                        @if($data[0]->tglMulaiMengerjakan!="")
                                        {{ date('d-m-Y H:i', strtotime($data[0]->tglMulaiMengerjakan)) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Target Selesai</td>
                                    <td>:</td>
                                    <td>
                                        @if($data[0]->tglSelesaiMengerjakan!="")
                                        {{ date('d-m-Y H:i', strtotime($data[0]->tglSelesaiMengerjakan)) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Implementasi</td>
                                    <td>:</td>
                                    <td>
                                        @if($data[0]->tglImplementasi!="")
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
<div class="modal fade bd-example-modal-lg" id="myModalApprove" tabindex="-1" role="dialog"
    aria-labelledby="myModalApprove" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Persetujuan Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('persetujuantiket/approve') }}" method="post">
                @method('patch')
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tiketNikAtasanService" class="col-md-4 control-label">Teknisi</label>
                        <div class="input-group col-md-6">
                            <input type="text" name="nikTeknisi" id="nikTeknisi" class="form-control" required>
                            <input type="hidden" name="emailTeknisi" id="emailTeknisi" readonly="true"
                                class="form-control" required>
                            <input type="hidden" name="tiketId" id="tiketId" readonly="true" class="form-control"
                                required>
                            <input type="hidden" name="namaTeknisi" id="namaTeknisi" readonly="true"
                                class="form-control" required>
                            <a href="#" data-toggle="modal" data-target="#myModalTeknisi" style="text-decoration:none">
                                <div class="input-group-append bg-primary border-primary">
                                    <span class="input-group-text bg-transparent">
                                        <i class="fa fa-search text-white"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6" id="namaTeknisi2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <input type="submit" name="Setuju" value="Setuju" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalTeknisi" tabindex="-1" role="dialog" aria-labelledby="myModalTeknisi"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: #fff;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cari Teknisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>NAMA</th>
                                            <th>JABATAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $nik = $nikLama = "";
                                        foreach($dtAtasanService as $data){
                                        $nik = $data['NIK'];
                                        if($nik!=$nikLama){
                                        @endphp
                                        <tr class="pilihTeknisi" data_nik="{{ $data['NIK'] }}"
                                            data_nama="{{ $data['NAMA'] }}" data_email="{{ $data['EMAIL'] }}">
                                            <td><a href="#" style="text-decoration:none">{{$data['NIK']}}</a></td>
                                            <td>{{$data['NAMA']}}</td>
                                            <td>{{$data['URAIAN_JAB']}}</td>
                                        </tr>
                                        @php
                                        $nikLama = $data['NIK'];
                                        }
                                        }
                                        @endphp
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
