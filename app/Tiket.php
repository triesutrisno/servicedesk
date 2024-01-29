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
        'tiketStatus',
        'namaLengkap',
        'nikLengkap',
        'noHp',
        'flagForward',
        'flagFeedback',
        'remarkFeedback',
        'tiketSeverity',
        'tiketMaindays',
        'sort',
        'remark'
    ];

    public function layanan()
    {
        return $this->hasMany('App\Layanan','id','layananId');
    }

    public function service()
    {
        return $this->belongsTo('App\Service','serviceId');
    }

    public function subService()
    {
        return $this->belongsTo('App\Subservice','subServiceId');
    }

    public function tiketDetail(){
        return $this->hasOne('App\Tiketdetail','tiketId','tiketId');
    }

    public function userBy(){
        return $this->hasOne('App\Infouser','username','nikUser');
    }
}
