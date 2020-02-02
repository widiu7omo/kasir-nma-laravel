<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterKorlap extends Model
{
    //
    protected $fillable = ['nama_korlap'];

    public function spbs()
    {
        return $this->hasMany('App\DataSpb');
    }
}
