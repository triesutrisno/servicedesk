<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saran extends Model
{
    protected $table = "saran";
    protected $primaryKey = "saranId";
    protected $guarded = ['saranId'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
