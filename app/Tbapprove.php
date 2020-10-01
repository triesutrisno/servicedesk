<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbapprove extends Model
{
    protected $table = "tb_approve";
    protected $primaryKey = "appId";
    protected $fillable = [
        'tiketId',
        'kunci',
        'idTelegram',
        'nikTeknisi',
        'aktif_sampai',
        'flag'
    ];
}
