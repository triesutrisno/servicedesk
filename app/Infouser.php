<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infouser extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = [
        'name','username', 'email', 'password','level','idTelegram',
    ];
}
