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
        'total_harga',
        'nama_supir',
        'user_id',
        'data_timbangan_id',
        'master_harga_id',
        'data_spb_id',
        'data_petani_id',
        'custom_price',
        'is_custom_price'
    ];
    protected $casts = [
        'is_custom_price' => 'boolean'
    ];

    public function timbangan()
    {
        return $this->belongsTo('App\DataTimbangan', 'data_timbangan_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function harga()
    {
        return $this->belongsTo('App\MasterHarga', 'master_harga_id');
    }

    public function spb()
    {
        return $this->belongsTo('App\DataSpb', 'data_spb_id');
    }

    public function petani()
    {
        return $this->belongsTo('App\DataPetani', 'data_petani_id');
    }
}

