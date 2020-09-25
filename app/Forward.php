<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forward extends Model
{
    protected $table = "tiket_forward";
    protected $primaryKey = "forwardId";
    protected $fillable = [
        'tiketId',
        'tiketDetailId',
        'nik'
    ];
}
