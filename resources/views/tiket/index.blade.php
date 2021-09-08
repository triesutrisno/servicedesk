
@section('js')
<script src="{{asset('bs4/js/bootstrap-confirmation.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.4/umd/popper.min.js" integrity="sha512-eUQ9hGdLjBjY3F41CScH3UX+4JDSI9zXeroz7hJ+RteoCaY+GP/LDoM8AO+Pt+DRFw3nXqsjh9Zsts8hnYv8/A==" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //$('#table thead th').each( function () {
    //    var title = $(this).text();
    //    $(this).html( '<input type="text" placeholder="'+title+'" />' );
    //} );
    var table = $('#table').DataTable({
        //"iDisplayLength": 20,
        "bSort" : false
        "order": [[ 11, "asc" ]],        
        "paging"  : false,
        "info"    : false,
        "bFilter": false,
        //"oLanguage": {
        //    "sSearch": "Nomer : "
        //}
    });
    
    //var table = $('#example').DataTable();
  
    table.column( 11 ).visible( false );
    
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        // other options
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
<div class="flex-row">
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
              <form action="{{ url('/tiket')}}" method="post">
              @csrf
              <div class="row alert alert-warning">
                    <div class="col-sm-12 col-md-3">
                        <label class="text-small">Nomer : </label>
                        <input type="text" name="nomer" class="form-control" value="{{ $param['nomer'] }}" autocomplete="off">
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <label class="text-small">Nama : </label>
                        <input type="text" name="nama" class="form-control" value="{{ $param['nama'] }}" autocomplete="off">
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <label class="text-small">Status : </label>
                        <select name="status" class="form-control">
                            <option value="">Silakan Pilih</option>
                            <option value="1" {{ $param['status']=='1' ? 'selected' : '' }}>Open</option>
                            <option value="2"{{ $param['status']=='2' ? 'selected' : '' }}>Diapprove Atasan Unit</option>
                            <option value="3"{{ $param['status']=='3' ? 'selected' : '' }}>Ditolak Atasan Unit</option>
                            <option value="4"{{ $param['status']=='4' ? 'selected' : '' }}>Disetujui</option>
                            <option value="5"{{ $param['status']=='5' ? 'selected' : '' }}>Ditolak</option>
                            <option value="6"{{ $param['status']=='6' ? 'selected' : '' }}>Dikerjakan</option>
                            <option value="7"{{ $param['status']=='7' ? 'selected' : '' }}>Selesai</option>
                            <option value="8"{{ $param['status']=='8' ? 'selected' : '' }}>Close</option>
                            <option value="9"{{ $param['status']=='9' ? 'selected' : '' }}>Pending</option>
                            <option value="10"{{ $param['status']=='10' ? 'selected' : '' }}>Cancel</option>
                            <option value="11"{{ $param['status']=='11' ? 'selected' : '' }}>Diforward</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <br />
                        <button type="submit" class="btn btn-success mr-2">Cari</button>
                    </div>
              </div>
              </form>
              @if (session('pesan'))
                    @if (session('kode')=='99')
                        <div class="alert alert-success">
                            <i class="fa  fa-info-circle bigger-120 blue"></i>
                            {{ session('pesan') }}
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fa  fa fa-times-rectangle bigger-120 red"></i>
                            {{ session('pesan') }}
                        </div>
                    @endif
               @endif
              @csrf
              <div class="table-responsive">
                <table class="table table-striped" id="table">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <!--<th>No</th>-->
                      <th>Status</th>
                      <th>Progres</th>
                      <th>UserBy</th>
                      <th>Nomer</th>                      
                      <th>Layanan</th>
                      <th>Service</th>
                      <th>Subservice</th>
                      <th>Keterangan</th>
                      <th>Tgl Buat</th>
                      <th>Prioritas</th>
                      <th>KodeStatus</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($datas as $data)
                  
                  
                    <tr>                        
                      <td>
                          @csrf                          
                          <a href="{{ url('/tiket')}}/detail/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-primary" title="Detail">
                              <i class="fa fa-search icon-lg"></i>
                          </a>
                          @if($data->tiketStatus=='1')
                          <a href="{{ url('/tiket')}}/edit/{{ $data->tiketId }}" class="btn btn-icons btn-inverse-warning" title="Ubah">
                              <i class="fa fa-edit icon-lg"></i>
                          </a> 
                          <a class="btn btn-icons btn-inverse-info hapusKategori" href="#" data-id="{{ $data->tiketId }}" title="Hapus">
                              <i class="fa fa-trash-o icon-lg"></i>
                          </a>
                          @endif
                          @if($data->tiketStatus<>'8')
                          <form action="{{ url('/tiket')}}/close/{{ $data->tiketId }}" method="post" class="d-inline">
                              @method('post')
                              @csrf
                              <button class="btn btn-icons btn-inverse-danger" data-toggle="confirmation" data-singleton="true" data-title="Anda yakin close data ini ?">
                                  <i class="fa fa-power-off icon-lg"></i>
                              </button>
                          </form>
                          @endif
                      </td>
                        <td>
                            @if($data->tiketStatus == '1')
                                <label class="badge badge-warning">open</label>
                            @elseif($data->tiketStatus == '2')
                                <label class="badge badge-warning">Diapprove Atasan Unit</label>
                            @elseif($data->tiketStatus == '3')
                                <label class="badge badge-danger">Ditolak Atasan Unit</label>
                            @elseif($data->tiketStatus == '4')
                                <label class="badge badge-success">Disetujui</label>
                            @elseif($data->tiketStatus == '5')
                                <label class="badge badge-danger">Ditolak</label>
                            @elseif($data->tiketStatus == '6')
                                <label class="badge badge-info">Dikerjakan</label>
                            @elseif($data->tiketStatus == '7')
                                <label class="badge badge-primary">Selesai</label>
                            @elseif($data->tiketStatus == '8')
                                <label class="badge badge-dark">Close</label>
                            @elseif($data->tiketStatus == '9')
                                <label class="badge badge-warning">Pending</label>
                            @elseif($data->tiketStatus == '10')
                                <label class="badge badge-danger">Cancel</label>
                            @elseif($data->tiketStatus == '11')
                                <label class="badge badge-warning">Diforward</label>
                            @endif
                        </td>
                        <td>
                            @if($data->progresProsen!="")
                                {{ $data->progresProsen }} %
                            @endif
                        </td> 
                        <td>{{ $data->name}}</td>
                        <!--<td align="center">{{$loop->iteration}}</td>-->
                        <td class="py-1">{{$data->kode_tiket }}</td>
                        <td>{{ $data->nama_layanan }}</td>
                        <td>{{ $data->ServiceName }}</td>
                        <td>{{ $data->ServiceSubName }}</td>
                        <td>{{ $data->tiketKeterangan}}</td>
                        <td>{{ date('d-m-Y H:i', strtotime($data->created_at)) }}</td>  
                        <td>
                          @if($data->tiketPrioritas == '1')
                              Biasa
                          @elseif($data->tiketPrioritas == '2')
                              Segera
                          @elseif($data->tiketPrioritas == '3')
                              Prioritas dan Penting
                          @else

                          @endif
                        </td>
                        <td>{{ $data->tiketStatus }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>                  
                <div class="pull-left">
                    Showing
                    {{ $datas->firstItem() }} to {{ $datas->lastItem() }} of {{ $datas->total() }} entries
                </div>
                <div class="pull-right">                        
                    {{ $datas->links() }}
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection