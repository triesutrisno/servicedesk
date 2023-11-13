@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tableTiket').DataTable({
          "iDisplayLength": 50
        });

        $('.pilihAtasanService').click( function(){
            $('#tiketNikAtasanService').val($(this).attr('data_nik'));
            $('#namaAtasanService').text($(this).attr('data_nama'));
            $('#myModalAtasanService').modal('hide');
        });

        $('#tiketFile').bind('change', function() {
            //this.files[0].size gets the size of your file.
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            //alert(this.files[0].size/1024);
            var filesize = parseFloat(this.files[0].size / 1024 / 1024).toFixed(2); // dalam KB
            var filetype = $(this).val().split('.').pop().toLowerCase();
            //if($.inArray(filetype, ['xlsx', 'xls', 'docx', 'doc'] == -1){

            //}
            //alert(filesize);
            if(filesize > 2){
                alert('Maaf size file yang anda masukan melebihi kapasitas. Size file maximal 2Mb');
                $("#simpan").removeClass('btn-success');
                $("#simpan").addClass('btn-secondary');
                $("#simpan").prop('disabled', true);
            }else{
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
      <a href="{{ url('tiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-mail-reply-all"></i> Kembali</a>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-header p-1" style="background: #000080;"></div>
            <div class="card-body">
                <h4 class="card-title">Ubah Data Tiket</h4>
                <form method="POST" action="{{ url('tiket/edit') }}/{{ $tiket[0]['tiketId'] }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="flagFeedback" id="flagFeedback" value="{{ $tiket[0]['flagFeedback'] !="" ? $tiket[0]['flagFeedback']+1 : '' }}" class="form-control">
                    <input type="hidden" name="serviceId" id="serviceId" value="{{ $tiket[0]['serviceId'] }}" class="form-control">
                    <div class="form-group{{ $errors->has('kode_tiket') ? ' has-error' : '' }}">
                        <label for="kode_tiket" class="col-md-4 control-label">Nomor Tiket</label>
                        <div class="col-md-6">
                            {{ $tiket[0]['kode_tiket'] }}
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketLayanan" class="col-md-4 control-label">Atasan</label>
                      <div class="col-md-6">
                        <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i>
                        {{ session('infoUser')['AL_NAMA'] }} ({{ session('infoUser')['AL_NIK'] }})
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketLayanan" class="col-md-4 control-label">Layanan</label>
                      <div class="col-md-6">
                        <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i>
                        {{ $tiket[0]['layanan'][0]['nama_layanan'] }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketService" class="col-md-4 control-label">Service</label>
                      <div class="col-md-6">
                        <i class="fa fa-angle-double-right text-danger mr-1" aria-hidden="true"></i>
                        {{ $tiket[0]['service']['ServiceName'] }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="subServiceId" class="col-md-4 control-label">Sub Service</label>
                      <div class="col-md-6">
                        <select class="form-control"  required id="subServiceId" name="subServiceId">
                            <option value="">Silakan Pilih</option>
                            @foreach($subService as $key => $val)
                                <option value="{{ $val->id }}" {{ $tiket[0]['subServiceId'] == $val->id ? 'selected' : '' }}>{{ $val->ServiceSubName }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tiketPrioritas" class="col-md-4 control-label">Prioritas</label>
                      <div class="col-md-6">
                          <select class="form-control" required id="tiketPrioritas" name="tiketPrioritas">
                            <option value="">Silakan Pilih</option>
                            <option value="1"{{ $tiket[0]['tiketPrioritas']=='1' ? 'selected' : '' }}>Biasa</option>
                            <option value="2"{{ $tiket[0]['tiketPrioritas']=='2' ? 'selected' : '' }}>Segera</option>
                            <option value="3"{{ $tiket[0]['tiketPrioritas']=='3' ? 'selected' : '' }}>Prioritas dan Penting</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="tiketNikAtasanService" class="col-md-4 control-label">Tujuan</label>
                        <div class="input-group col-md-6">
                            <input type="text" name="tiketNikAtasanService" id="tiketNikAtasanService" value="{{ $tiket[0]['tiketNikAtasanService'] }}" class="form-control" required>
                            <a href="#" data-toggle="modal" data-target="#myModalAtasanService" style="text-decoration:none">
                            <div class="input-group-append bg-primary border-primary">
                                <span class="input-group-text bg-transparent">
                                    <i class="fa fa-search text-white"></i>
                                </span>
                            </div>
                            </a>

                        </div>
                        <div class="col-md-6" id="namaAtasanService"></div>
                    </div>
                    <div class="form-group">
                        <label for="tiketKeterangan" class="col-md-4 control-label">Keterangan</label>
                        <div class="col-md-6">
                            <textarea class="form-control" required id="tiketKeterangan" name="tiketKeterangan" rows="6">{{ trim($tiket[0]['tiketKeterangan']) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="tiketFile" name="tiketFile">
                            <label class="custom-file-label" for="customFile">Pilih File, size maximal 2Mb</label>
                        </div>
                    </div>
                    @if($tiket[0]['serviceId']=='5')
                    <div class="form-group">
                        <label for="namaLengkap" class="col-md-4 control-label">Nama Lengkap User Yang Diminta</label>
                        <div class="input-group col-md-6">
                            <input type="text" name="namaLengkap" id="namaLengkap" class="form-control" value="{{ $tiket[0]['namaLengkap'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nikLengkap" class="col-md-4 control-label">NIK Lengkap User Yang Diminta</label>
                        <div class="input-group col-md-6">
                            <input type="text" name="nikLengkap" id="nikLengkap" class="form-control" value="{{ $tiket[0]['namaLengkap'] }}">
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <button type="submit" class="btn btn-success mr-2" id="simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="myModalAtasanService" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" >
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
                                            @foreach($dtAtasanService as $data)
                                            <tr class="pilihAtasanService" data_nik="{{ $data['NIK'] }}" data_nama="{{ $data['NAMA'] }}">
                                                <td><a href="#" style="text-decoration:none">{{$data['NIK']}}</a></td>
                                                <td>{{$data['NAMA']}}</td>
                                                <td>{{$data['URAIAN_JAB']}}</td>
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
