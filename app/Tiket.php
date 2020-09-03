<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = "tiket";
    protected $primaryKey = "tiketId";
    protected $fillable = [
        'kode_tiket',
        'comp',
        'unit',
        'biro',
        'nikUser',
        'layananId',
        'serviceId',
        'subServiceId',
        'tiketKeterangan',
        'file',
        'tiketApprove',
        'tiketTglApprove',
        'tiketNikAtasan',
        'tiketApproveService',
        'tiketTglApproveService',
        'tiketNikAtasanService',
        'tiketPrioritas',
        'tiketStatus'
    ];
    
    public function layanan()
    {
        return $this->hasMany('App\Layanan','id','layananId');
    }
    
    public function service()
    {
        return $this->hasMany('App\Service','id','serviceId');
    }
    
    public function subService()
    {
        return $this->hasMany('App\Subservice','id','subServiceId');
    }
}
