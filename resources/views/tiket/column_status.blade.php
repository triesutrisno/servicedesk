@switch($tiketStatus)
    @case(1)
        <label class="badge badge-warning">Baru</label>
    @break

    @case(2)
        <label class="badge badge-warning">Diapprove Atasan Unit</label>
    @break

    @case(3)
        <label class="badge badge-danger">Ditolak Atasan Unit</label>
    @break

    @case(4)
        <label class="badge badge-success">Disetujui</label>
    @break

    @case(5)
        <label class="badge badge-danger">Ditolak</label>
    @break

    @case(6)
        <label class="badge badge-info">Dikerjakan</label>
    @break

    @case(7)
        <label class="badge badge-primary">Selesai</label>
    @break

    @case(8)
        <label class="badge badge-dark">Close</label>
    @break

    @case(9)
        <label class="badge badge-warning">Pending</label>
    @break

    @case(10)
        <label class="badge badge-danger">Cancel</label>
    @break

    @case(11)
        <label class="badge badge-warning">Diforward</label>
    @break

    @default
@endswitch
