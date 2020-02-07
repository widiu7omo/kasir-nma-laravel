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
        'setelah_gradding'
    ];

    public function getNoTickets()
    {
        return $this->no_ticket;
    }

}
