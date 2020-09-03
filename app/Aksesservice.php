<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aksesservice extends Model
{
	protected $table = 'ticket_service_sub';
    protected $primaryKey = "id";
    protected $fillable = ['id','nama_layanan'];


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
    
    public function layanan()
    {
        return $this->hasMany('App\Layanan','id','id_layanan');
    }

    public function service()
    {
        return $this->hasMany('App\Service','id','ServiceIDf');
    }
    
    public function subService()
    {
        return $this->hasMany('App\Subservice','id','subServiceId');
    }
}
