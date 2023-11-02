@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tableTiket').DataTable({
          "iDisplayLength": 50
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
        <div class="card-header p-1" style="background: #000080;"></div>
            <div class="card-body">
                <h4 class="card-title">Kembalikan Tiket Ke Atasan</h4>                                  
                <form method="POST" action="{{ url('tugasku/kembalikeatasan') }}/{{ $datas[0]->tiketDetailId }}/{{ $datas[0]->tiketId }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="table-responsive-sm">
                        <table class="table-responsive">
                            <tr>
                                <td width="10%">Nomor</td>
                                <td width="2%">:</td>
                                <td>{{ $datas[0]->kode_tiket }}</td>
                                <td width="10%">Layanan</td>
                                <td width="2%">:</td>
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
                                    <textarea class="form-control" required id="keterangan" name="keterangan" rows="6"></textarea>   
                                </td>
                            </tr>
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
