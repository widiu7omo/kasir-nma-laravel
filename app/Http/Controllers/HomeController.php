<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataTimbangan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param DataTimbangan $dataTimbangan
     * @param DataKwitansi $dataKwitansi
     * @return \Illuminate\View\View
     */
    public function index(DataTimbangan $dataTimbangan, DataKwitansi $dataKwitansi)
    {
        $rangkuman_timbangan = (object)[
            'total' => $dataTimbangan->where(['tanggal_masuk' => date('Y-m-d')])->sum('setelah_gradding'),
            'belum_dibayar' => $dataTimbangan->where(['status_pembayaran' => 'belum', 'tanggal_masuk' => date('Y-m-d')])->sum('setelah_gradding'),
            'sudah_dibayar' => $dataTimbangan->where(['status_pembayaran' => 'sudah', 'tanggal_masuk' => date('Y-m-d')])->sum('setelah_gradding'),
            'spb_lunas' => $dataTimbangan->where(['status_pembayaran' => 'sudah', 'tanggal_masuk' => date('Y-m-d')])->sum('no_ticket'),
            'spb_pending' => $dataTimbangan->where(['status_pembayaran' => 'sudah', 'tanggal_masuk' => date('Y-m-d')])->sum('no_ticket')
        ];
        $rangkuman_kwitansi = (object)[
            'total' => $dataKwitansi->where(['tanggal_pembayaran' => date('Y-m-d')])->sum('total_harga')
        ];
        return view('home.index', ['timbangan' => $rangkuman_timbangan, 'kwitansi' => $rangkuman_kwitansi]);
    }
}
