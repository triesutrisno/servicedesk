@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tableTiket').DataTable({
          "iDisplayLength": 50
        });
        
        $('#tiketFile').bind('change', function() {
            //this.files[0].size gets the size of your file.
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            //alert(this.files[0].size/1024);
            var filesize = parseFloat(this.files[0].size / 1024 ).toFixed(2); // dalam KB
            var filetype = $(this).val().split('.').pop().toLowerCase();
            //if($.inArray(filetype, ['xlsx', 'xls', 'docx', 'doc'] == -1){
            
            //}
            //alert(filesize);
            if(filesize > 1000){
                alert('Maaf size file yang anda masukan melebihi kapasitas. Size file maximal 1Mb');
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
      <a href="{{ url('tugasku') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
    </div>
</div>
<br />
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Data Solusi Tiket</h4>                                  
                <form method="POST" action="{{ url('tugasku/solusi') }}/{{ $datas[0]->tiketDetailId }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="table-responsive-sm">
                        <table class="table-responsive">
                            <tr>
                                <td width="18%">Nomor</td>
                                <td width="1%">:</td>
                                <td>{{ $datas[0]->kode_tiket }}</td>
                                <td width="16%">Layanan</td>
                                <td width="1%">:</td>
                                <td>{{ $datas[0]->nama_layanan }}</td>
                            </tr>
                            <tr>
                                <td>Service</td>
                                <td>:</td>
                                <td>{{ $datas[0]->ServiceName }}</td>
                                <td>Sub Service</td>
                                <td>:</td>
                                <td>{{ $datas[0]->ServiceSubName }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Buat</td>
                                <td>:</td>
                                <td>{{ date('d-m-Y H:i', strtotime($datas[0]->created_at)) }}</td>
                                <td>File</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->file!="")
                                        <a href="{{ url('/images/fileTiket') }}/{{$datas[0]->file}}">Lampiran</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Prioritas</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->tiketPrioritas=='1')
                                        Biasa
                                    @elseif($datas[0]->tiketPrioritas=='2')
                                        Segera
                                    @elseif($datas[0]->tiketPrioritas=='3')
                                        Prioritas dan Penting
                                    @else

                                    @endif
                                </td>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if($datas[0]->tiketStatus == '1')
                                        <label class="badge badge-warning">open</label>
                                    @elseif($datas[0]->tiketStatus == '2')
                                        <label class="badge badge-warning">Diapprove Atasan Unit</label>
                                    @elseif($datas[0]->tiketStatus == '3')
                                        <label class="badge badge-danger">Ditolak Atasan Unit</label>
                                    @elseif($datas[0]->tiketStatus == '4')
                                        <label class="badge badge-success">Disetujui</label>
                                    @elseif($datas[0]->tiketStatus == '5')
                                        <label class="badge badge-danger">Ditolak</label>
                                    @elseif($datas[0]->tiketStatus == '6')
                                        <label class="badge badge-info">Dikerjakan</label>
                                    @elseif($datas[0]->tiketStatus == '7')
                                        <label class="badge badge-primary">Selesai</label>
                                    @elseif($datas[0]->tiketStatus == '8')
                                        <label class="badge badge-dark">Close</label>
                                    @elseif($datas[0]->tiketStatus == '9')
                                        <label class="badge badge-warning">Pending</label>
                                    @elseif($datas[0]->tiketStatus == '10')
                                        <label class="badge badge-danger">Cancel</label>
                                    @elseif($datas[0]->tiketStatus == '11')
                                        <label class="badge badge-warning">Forward</label>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Teknisi</td>
                                <td>:</td>
                                <td>{{ $datas[0]->namaTeknisi }}</td>
                                <td>Atasan Teknisi</td>
                                <td>:</td>
                                <td>{{ session('infoUser')['AL_NAMA'] }}</td>
                            </tr>
                            <tr>
                                <td>Deskripsi Tiket</td>
                                <td>:</td>
                                <td colspan="4" class="datae">{{ $datas[0]->tiketKeterangan}}</td>
                            </tr>
                            <tr>
                                <td>Keterangan *</td>
                                <td>:</td>
                                <td colspan="4" class="datae">
                                    <textarea class="form-control" required id="keterangan" name="keterangan" rows="6">{{ $datas[0]->keterangan }}</textarea>  
                                </td>
                            </tr>  
                            <tr>
                                <td>Lampiran</td>
                                <td>:</td>
                                <td colspan="4" class="datae">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="tiketFile" name="tiketFile">
                                        <label class="custom-file-label" for="customFile">Pilih File, size maximal 1Mb</label>
                                    </div>
                                </td>
                            </tr>                            
                            <tr>
                                <td>Nama Akun</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="namaAkun" name="namaAkun" class="form-control" value="{{ $datas[0]->namaAkun }}">
                                </td>
                                <td>Password Akun</td>
                                <td>:</td>
                                <td>
                                    <input type="text" id="passwordAkun" name="passwordAkun" class="form-control" value="{{ $datas[0]->passwordAkun }}">
                                </td>
                            </tr>                           
                            <tr>
                                <td>Progres *</td>
                                <td>:</td>
                                <td>
                                    <select class="form-control"  required id="progres" name="progres">
                                        <option value="">Silakan Pilih</option>
                                        @foreach($progres as $key => $value)
                                            <option value="{{ $value->progresId }}"{{ $datas[0]->progresId == $value->progresId ? 'selected' : '' }}>{{ $value->progresNama }} - {{ $value->progresProsen}} %</option>
                                        @endforeach                          
                                    </select>
                                </td>
                                <td>Tanggal Wawancara</td>
                                <td>:</td>
                                <td>
                                    <input type="date" id="tglWawancara" name="tglWawancara" class="form-control" value="{{ $datas[0]->tglWawancara }}">
                                </td>
                            </tr>                          
                            <tr>
                                <td>Tanggal Mulai Mengerjakan</td>
                                <td>:</td>
                                <td>
                                    <input type="date" id="tglMulaiMengerjakan" name="tglMulaiMengerjakan" class="form-control" value="{{ $datas[0]->tglMulaiMengerjakan }}">
                                </td>
                                <td>Tanggal RTL</td>
                                <td>:</td>
                                <td>
                                    <input type="date" id="tglRTL" name="tglRTL" class="form-control" value="{{ $datas[0]->tglRTL }}">
                                </td>
                            </tr>                           
                            <tr>
                                <td>Tanggal Implementasi</td>
                                <td>:</td>
                                <td>
                                    <input type="date" id="tglImplementasi" name="tglImplementasi" class="form-control" value="{{ $datas[0]->tglImplementasi }}">
                                </td>
                                <td>Tanggal Pelatihan</td>
                                <td>:</td>
                                <td>
                                    <input type="date" id="tglPelatihan" name="tglPelatihan" class="form-control" value="{{ $datas[0]->tglPelatihan }}">
                                </td>
                            </tr>
                            @if($datas[0]->tiketDetailStatus=='1')                            
                            <tr>
                                <td>Tanggal Target Selesai *</td>
                                <td>:</td>
                                <td>
                                    <input type="date" id="tglSelesaiMengerjakan" name="tglSelesaiMengerjakan" class="form-control" required value="{{ $datas[0]->tglSelesaiMengerjakan }}">                                    
                                </td>
                                @if($datas[0]->serviceId=='19') 
                                    <td>Maindays *</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" id="tiketMaindays" name="tiketMaindays" class="form-control" required value="{{ $datas[0]->tiketMaindays }}">
                                    </td>
                                @else
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                @endif
                            </tr>
                            @else
                            <tr>
                                @if($datas[0]->serviceId=='19')
                                    <td>Maindays *</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" id="tiketMaindays" name="tiketMaindays" class="form-control" required value="{{ $datas[0]->tiketMaindays }}">
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                @else
                                    
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>                                    
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                @endif
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success mr-2">Simpan</button>
                        <button type='reset' class="btn btn-light">Reset</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
@endsection
