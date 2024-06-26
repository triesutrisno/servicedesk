<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subservice extends Model
{
    protected $table = 'ticket_service_sub';
    protected $fillable = ['id', 'ServiceSubName', 'ServiceIDf', 'ServiceSubStatus', 'id_unit'];


    /**
     * Method One To One
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Method One To Many
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function service()
    {
        return $this->hasMany('App\Service', 'id', 'ServiceIDf');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }
}
