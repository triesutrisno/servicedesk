<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tiketdetail extends Model
{
    protected $table = "tiket_detail";
    protected $primaryKey = "tiketDetailId";
    protected $fillable = [
        'tiketId',
        'nikTeknisi',
        'keterangan',
        'tiketDetailStatus',
        'namaAkun',
        'passwordAkun',
        'tglWawancara',
        'tglMulaiMengerjakan',
        'tglSelesaiMengerjakan',
        'tglImplementasi',
        'tglPelatihan'
    ];
}
