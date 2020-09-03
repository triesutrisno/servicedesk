<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Masterlayanan extends Model
{
    protected $table = 'm_layanan';
    
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
}
