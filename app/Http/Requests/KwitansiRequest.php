<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KwitansiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "no_berkas" => ['required'],
            "tgl_pembayaran" => ['required'],
            "tgl_timbangan" => ['required'],
            "no_tiket" => ['required'],
            "nik" => ['required'],
            "no_spb" => ['required'],
            "no_kendaraan" => ['required'],
            "supir" => ['required'],
            "pemilik_spb" => ['required'],
            "first_weight" => ['required'],
            "second_weight" => ['required'],
            "netto_weight" => ['required'],
            "potongan_grading" => ['required'],
            "setelah_grading" => ['required'],
            "total_berat" => ['required'],
            "harga_satuan" => ['required'],
            "total_pembayaran" => ['required'],
        ];
    }
}
