<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataTimbangan;
use App\MasterKorlap;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            'belum_dibayar' => $dataTimbangan->where(['status_pembayaran' => 'belum', 'tanggal_masuk' => date('Y-m-d')])->sum('setelah_gradding'),
            'sudah_dibayar' => $dataTimbangan->where(['status_pembayaran' => 'sudah', 'tanggal_masuk' => date('Y-m-d')])->sum('setelah_gradding'),
            'spb_lunas' => DB::table('data_kwitansis')->join('data_timbangans', 'data_timbangan_id', '=', 'data_timbangans.id')->where(['tanggal_pembayaran' => date('Y-m-d'), 'status_pembayaran' => 'sudah'])->count('*'),
            'spb_pending' => DB::table('data_timbangans')->where(['status_pembayaran' => 'belum'])->count('*'),
            'total_timbangan' => DB::table('data_kwitansis')->join('data_timbangans', 'data_timbangan_id', '=', 'data_timbangans.id')->where(['tanggal_pembayaran' => date('Y-m-d')])->sum('setelah_gradding')
        ];
        $rangkuman_kwitansi = (object)[
            'total_dibayar' => $dataKwitansi->where(['tanggal_pembayaran' => date('Y-m-d')])->count('*'),
        ];
        //Grafik
        Carbon::setLocale('id');
        $dataBySPB = MasterKorlap::whereIn('nama_korlap', ["Ginting", "Poniman", "Bu Eny"])->get();
        $korlaps = [];
        $data_days = [];
        foreach ($dataBySPB as $key => $bySpb) {
            $start = Carbon::now()->format('Y-m-d');

            $berat_by_korlap = DB::table('data_timbangans')->join('data_kwitansis', 'data_kwitansis.data_timbangan_id', '=', 'data_timbangans.id')
                ->join('data_spbs', 'data_kwitansis.data_spb_id', '=', 'data_spbs.id')
                ->rightJoin('master_korlaps', 'data_spbs.master_korlap_id', '=', 'master_korlaps.id')
                ->selectRaw('if(sum(setelah_gradding) is NULL,0,sum(setelah_gradding)) total')
                ->where(['tanggal_pembayaran' => $start, 'master_korlaps.id' => $bySpb->id])
                ->get('total');
            array_push($korlaps, $bySpb->nama_korlap);
            array_push($data_days, $berat_by_korlap[0]->total);
        }
        return view('home.index', ['timbangan' => $rangkuman_timbangan, 'kwitansi' => $rangkuman_kwitansi, 'chart' => (object)['data' => json_encode($data_days), 'labels' => json_encode($korlaps)]]);
    }
}
