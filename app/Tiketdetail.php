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
        'tglPelatihan',
        'tglRTL'
    ];

    public function tiket()
    {
        return $this->hasMany('App\Tiket', 'tiketId', 'tiketId');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'nikTeknisi', 'username');
    }

    public function progres()
    {
        return $this->belongsTo(Progres::class, 'progresId', 'progresId');
    }
}
