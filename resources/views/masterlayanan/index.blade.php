@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        $('#table').DataTable({
            "iDisplayLength": 50
        });

    });

</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-2">
        <a href="{{ route('masterlayanan.create') }}" class="btn btn-primary btn-rounded btn-fw"><i
                class="fa fa-plus"></i> Tambah Master Layanan</a>
    </div>
    <div class="col-lg-12">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">
            {{ Session::get('message') }}</div>
        @endif
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header p-1" style="background: #000080;"></div>
            <div class="card-body">
                <h4 class="card-title">Data Master Layanan</h4>

                <div class="table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Kode Layanan
                                </th>
                                <th>
                                    Nama Layanan
                                </th>
                                <th>
                                    Kode Biro
                                </th>
                                <th>Status
                                </th>

                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            <tr>
                                <td class="py-1">

                                    {{$loop->iteration}}
                                </td>
                                <td class="py-1">

                                    {{$data->kode_layanan}}
                                </td>
                                <td class="py-1">
                                    {{$data->nama_layanan}}
                                </td>
                                <td class="py-1">
                                    {{$data->kode_biro}}
                                </td>
                                <td>
                                    @if($data->status_layanan == '1')
                                    <label class="badge badge-success">Aktif</label>
                                    @elseif($data->status_layanan == '0')
                                    <label class="badge badge-dark">Tidak Aktif</label>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-success dropdown-toggle btn-sm"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start"
                                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                            <a class="dropdown-item" href="{{route('masterlayanan.edit', $data->id)}}">
                                                Edit </a>
                                            <form action="{{ route('masterlayanan.destroy', $data->id) }}"
                                                class="pull-left" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button class="dropdown-item"
                                                    onclick="return confirm('Anda yakin ingin menghapus data ini?')">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{--  {!! $datas->links() !!} --}}
            </div>
        </div>
    </div>
</div>
@endsection
