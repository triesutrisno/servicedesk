<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    protected $table = "m_progres";
    protected $primaryKey = "progresId";
    protected $fillable = [
        'progresNama',
        'progresProsen',
        'progresStatus',
    ];
}
