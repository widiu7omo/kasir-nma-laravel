<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterHarga extends Model
{
    //
    protected $fillable = ['harga','tanggal'];
    public $timestamps = false;
}
