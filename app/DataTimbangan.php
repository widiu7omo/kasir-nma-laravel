<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataTimbangan extends Model
{
    //
    protected $fillable = [
        'no_ticket',
        'tanggal_masuk',
        'no_kendaraan',
        'pelanggan',
        'tandan',
        'first_weight',
        'second_weight',
        'netto_weight',
        'potongan_gradding',
        'setelah_gradding',
        'status_pembayaran'
    ];
    public $timestamps = true;

    public function getNoTickets()
    {
        return $this->no_ticket;
    }

    public function kwitansi()
    {
        return $this->belongsTo('App\DataKwitansi');
    }
}
