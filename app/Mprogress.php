<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mprogress extends Model
{
    protected $table = 'm_progres';
    
    protected $fillable = ['progresId','progresNama','progresProsen','progresStatus'];


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
  
    public function mprogress()
    {
        return $this->hasMany('App\Mprogress','id','progresId');
    }
}
