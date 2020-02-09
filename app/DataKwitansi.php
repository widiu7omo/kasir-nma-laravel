<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKwitansi extends Model
{
    //
    protected $fillable = [
        'no_berkas',
        'tanggal_pembayaran',
        'no_pembayaran',
        'no_spb',
        'nama_supir',
        'user_id',
        'data_timbangan_id',
        'master_harga_id',
        'data_spb_id'
    ];

    public function timbangan()
    {
        return $this->hasMany('App\DataTimbangan');
    }
}

