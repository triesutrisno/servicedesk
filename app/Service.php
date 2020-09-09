<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'ticket_service';
    protected $primaryKey = "id";
    protected $fillable = ['id','ServiceName','id_layanan','min_eselon','keterangan','ServiceStatus'];


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
}
