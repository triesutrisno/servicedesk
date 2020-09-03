<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'm_layanan';
    protected $fillable = ['id', 'kode_layanan', 'nama_layanan', 'kode_biro', 'comp', 'status_layanan'];
}
