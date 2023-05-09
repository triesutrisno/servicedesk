<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'm_unit';


    public function atasanUnit()
    {
        return $this->belongsTo(User::class, 'nik_atasan_service', 'username');
    }
}
