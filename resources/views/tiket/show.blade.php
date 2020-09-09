
@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 20
    });
    
    $('.hapusKategori').click(function(){
            var jawab = confirm("Anda yakin akan menghapus data ini ?");
            if (jawab === true) {
//            kita set hapus false untuk mencegah duplicate request
                var hapus = false;
                if (!hapus) {
                    hapus = true;
                    //$.post('hapus.php', {id: $(this).attr('data-id')},
                    var idne = $(this).attr('data-id');
                    var _token = $('input[name="_token"]').val();
                    //alert(idne);
                    $.ajax({
                        url : "{{ url('/tiket/delete') }}/"+idne,
                        method : "POST",
                        data : {_token:_token},
                        success : function(result){
                            //alert(result);
                            //$('#'+dependent).html(result);
                            location.reload();
                        }
                    })
                    hapus = false;
                }
            } else {
                return false;
            }
            
        });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="form-group">
      <a href="{{ url('tiket') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-book"></i> Lihat Data</a>
      <a href="{{ url('tiket/create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Tambah Data</a>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Tiket</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="10%">Kode Tiket</td>
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
                            <td>UserBy</td>
                            <td>:</td>
                            <td>{{ $data[0]->name}}</td>
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
                                    <label class="badge badge-danger">Cancle</label>
                                @endif
                                &nbsp;
                                @if($data[0]->progresProsen!="")
                                    
                                    <label class="badge badge-success">{{ $data[0]->progresProsen }}%</label>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td colspan="4">{{ $data[0]->tiketKeterangan}}</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#detailApproval" role="tab" aria-controls="home" aria-selected="true">Detail Approval Tiket</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Histori Tiket</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!--Start Detail Approval Tiket -->
                    <div class="tab-pane fade show active" id="detailApproval" role="tabpanel" aria-labelledby="home-tab">
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
                            </tbody>
                        </table>
                    </div>
                    <!--End Detail Approval Tiket -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection