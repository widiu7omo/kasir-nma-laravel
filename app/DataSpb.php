<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSpb extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['tanggal_pengambilan', 'range_spb', 'master_korlap_id'];

    public function korlap()
    {
        return $this->belongsTo('App\MasterKorlap', 'master_korlap_id');
    }

}
