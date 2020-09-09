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
        'tiketEmail',
        'layananId',
        'serviceId',
        'subServiceId',
        'tiketKeterangan',
        'file',
        'tiketApprove',
        'tiketTglApprove',
        'tiketNikAtasan',
        'tiketEmailAtasan',
        'tiketApproveService',
        'tiketTglApproveService',
        'tiketNikAtasanService',
        'tiketEmailAtasanService',
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
    
    public function tiketDetail(){
        return $this->hasOne('App\Tiketdetail','tiketId','tiketId');
    }
}
