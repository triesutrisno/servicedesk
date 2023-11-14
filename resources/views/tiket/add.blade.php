@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableTiket').DataTable({
                "iDisplayLength": 50
            });

            $('.pilihAtasanService').click(function() {
                $('#tiketNikAtasanService').val($(this).attr('data_nik'));
                $('#namaAtasanService').text($(this).attr('data_nama'));
                $('#tiketEmailAtasanService').val($(this).attr('data_email'));
                $('#myModalAtasanService').modal('hide');
            });

            $('#tiketFile').bind('change', function() {
                //this.files[0].size gets the size of your file.
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                // alert(this.files[0].size);
                var filesize = parseFloat(this.files[0].size / 1024).toFixed(2); // dalam KB
                // alert(filesize)
                var filetype = $(this).val().split('.').pop().toLowerCase();
                //if($.inArray(filetype, ['xlsx', 'xls', 'docx', 'doc'] == -1){

                //}
                //alert(filesize);
                if (filesize >= 1000) {
                    alert('Maaf size file yang anda masukan melebihi kapasitas. Size file maximal 1Mb');
                    $("#simpan").removeClass('btn-success');
                    $("#simpan").addClass('btn-secondary');
                    $("#simpan").prop('disabled', true);
                } else {
                    $("#simpan").removeClass('btn-secondary');
                    $("#simpan").addClass('btn-success');
                    $("#simpan").prop('disabled', false);
                }
            });
        });
    </script>
@stop
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="form-group">
            <a href="{{ url('tiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
            <a href="{{ url('tiket/create') }}/{{ $id_layanan }}" class="btn btn-primary btn-rounded btn-fw"><i
                    class="fa fa-mail-reply-all"></i> Kembali</a>
        </div>
    </div>
    <br />

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="jumbotron">
                <h1 class="display-4">Notice!</h1>
                <p class="lead">Pastikan minta approve ke atasan untuk setiap tiket.
                    Tiket yang tidak diapprove lebih 3 hari akan dihapus dan tidak dikerjakan</p>
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Data Tiket</h4>
                    <form method="POST" action="{{ url('tiket/create') }}/{{ $id_layanan }}/{{ $id_service }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('kode_tiket') ? ' has-error' : '' }}">
                            <label for="kode_tiket" class="col-md-4 control-label">Nomor Tiket</label>
                            <div class="col-md-6">
                                <input id="kode_tiket" type="text" class="form-control" required name="kode_tiket"
                                    value="{{ $kode }}" readonly="">
                                @if ($errors->has('kode_tiket'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kode_tiket') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tiketLayanan" class="col-md-4 control-label">Atasan</label>
                            <div class="col-md-6">
                                <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i>
                                {{ session('infoUser')['AL_NAMA'] }} @if (session('infoUser')['AL_NIK'] != '')
                                    ({{ session('infoUser')['AL_NIK'] }})
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tiketLayanan" class="col-md-4 control-label">Layanan</label>
                            <div class="col-md-6">
                                <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i>
                                {{ $service[0]['layanan'][0]['nama_layanan'] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tiketService" class="col-md-4 control-label">Service</label>
                            <div class="col-md-6">
                                <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i>
                                {{ $service[0]['ServiceName'] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subServiceId" class="col-md-4 control-label">Sub Service</label>
                            <div class="col-md-6">
                                <select class="form-control" required id="subServiceId" name="subServiceId">
                                    <option value="">Silakan Pilih</option>
                                    @foreach ($subService as $key => $val)
                                        <option value="{{ $val->id }}">{{ $val->ServiceSubName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tiketPrioritas" class="col-md-4 control-label">Prioritas</label>
                            <div class="col-md-6">
                                <select class="form-control" required id="tiketPrioritas" name="tiketPrioritas">
                                    <option value="">Silakan Pilih</option>
                                    <option value="1">Biasa</option>
                                    <option value="2">Segera</option>
                                    <option value="3">Prioritas dan Penting</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="tiketNikAtasanService" class="col-md-4 control-label">Tujuan</label>
                            <div class="input-group col-md-6">
                                <input type="text" name="tiketNikAtasanService" id="tiketNikAtasanService"
                                    class="form-control @error('tiketNikAtasanService') is-invalid @enderror"
                                    readonly="true">
                                <input type="hidden" name="tiketEmailAtasanService" id="tiketEmailAtasanService"
                                    class="form-control" required readonly="true">
                                <a href="#" data-toggle="modal" data-target="#myModalAtasanService"
                                    style="text-decoration:none">
                                    <div class="input-group-append bg-primary border-primary">
                                        <span class="input-group-text bg-transparent">
                                            <i class="fa fa-search text-white"></i>
                                        </span>
                                    </div>
                                </a>
                                @error('tiketNikAtasanService')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="col-md-6" id="namaAtasanService"></div>
                        </div> --}}
                        <div class="form-group {{ $errors->has('tiketKeterangan') ? 'has-error' : '' }}">
                            <label for="tiketKeterangan" class="col-md-4 control-label">Keterangan</label>
                            <div class="col-md-6">
                                <textarea class="form-control" required id="tiketKeterangan" name="tiketKeterangan" rows="6"></textarea>
                                @if ($errors->has('tiketKeterangan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tiketKeterangan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="noHp" class="col-md-4 control-label">No HP</label>
                            <div class="input-group col-md-6">
                                <input type="text" name="noHp" id="noHp" class="form-control"
                                    value="{{ session('infoUser')['TELPON'] }}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="tiketFile" name="tiketFile">
                                <label class="custom-file-label" for="customFile">Pilih File, size maximal 2Mb</label>
                            </div>
                        </div>
                        @if ($id_service == '5')
                            <div class="form-group">
                                <label for="namaLengkap" class="col-md-4 control-label">Nama Lengkap User Yang
                                    Diminta</label>
                                <div class="input-group col-md-6">
                                    <input type="text" name="namaLengkap" id="namaLengkap" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nikLengkap" class="col-md-4 control-label">NIK Lengkap User Yang
                                    Diminta</label>
                                <div class="input-group col-md-6">
                                    <input type="text" name="nikLengkap" id="nikLengkap" class="form-control">
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="btn btn-success mr-2" id="simpan">Simpan</button>
                            <button type='reset' class="btn btn-light">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="myModalAtasanService" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background: #fff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cari Tujuan</h5>
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
                                            @foreach ($dtAtasanService as $data)
                                                <tr class="pilihAtasanService" data_nik="{{ $data['NIK'] }}"
                                                    data_nama="{{ $data['NAMA'] }}" data_email="{{ $data['EMAIL'] }}">
                                                    <td><a href="#"
                                                            style="text-decoration:none">{{ $data['NIK'] }}</a></td>
                                                    <td>{{ $data['NAMA'] }}</td>
                                                    <td>{{ $data['URAIAN_JAB'] }}</td>
                                                </tr>
                                            @endforeach
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
