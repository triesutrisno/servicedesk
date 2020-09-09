<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Histori extends Model
{
    protected $table = "tb_histori";
    protected $primaryKey = "historiId";
    protected $fillable = [
        'keterangan',
        'progresId',
        'tiketDetailId',
        'tglRTL'
    ];
}
