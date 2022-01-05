<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userlevel extends Model
{
    protected $table = "userLevel";
    protected $primaryKey = "id";
    protected $fillable = [
        'nik',
        'level',
        'status'
    ];
}
