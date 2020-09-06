<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nextnumber extends Model
{
    protected $table = "next_number";
    protected $primaryKey = "id";
    protected $fillable = [
        'tahun',
        'keterangan',
        'nextnumber',
        'status',
    ];
}
