<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataPetani;
use App\DataSpb;
use App\DataTimbangan;
use App\Http\Requests\KwitansiRequest;
use App\MasterHarga;
use Carbon\Carbon;
use ConsoleTVs\Invoices\Classes\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;
use Symfony\Component\VarDumper\Cloner\Data;

class DataKwitansiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //
        $kwitansis = DataKwitansi::
        with(
            ['user' => function ($query) {
                return $query->select('id', 'name');
            }])
            ->with(['timbangan' => function ($query) {
                return $query->select('id', 'setelah_gradding', 'no_kendaraan', 'tanggal_masuk');
            }])
            ->with(['harga' => function ($query) {
                return $query->select('id', 'harga');
            }])
            ->with(['spb' => function ($query) {
                return $query->with(['korlap' => function ($query) {
                    return $query->select('id', 'nama_korlap');
                }]);
            }])
            ->get();
//        return response()->json($kwitansis);
        return view('kwitansi.index', ['kwitansis' => $kwitansis]);
    }

    public function detail(Request $request, DataKwitansi $dataKwitansi)
    {
        $spb = $dataKwitansi->select('*')->where(['no_spb' => $request->spb])->get();
        return response()->json(['status' => 'success', 'spb' => $spb]);
    }

    public function tiket(Request $request, DataTimbangan $timbangan)
    {
        $noTickets = $timbangan->select('*')->where(['no_ticket' => $request->no_ticket])->get();
        return response()->json(['status' => 'success', 'tickets' => $noTickets]);
    }

    public function harga(Request $request, MasterHarga $masterHarga)
    {
        $hargaByTanggal = $masterHarga->select(['id', 'harga'])->where(['tanggal' => $request->tanggal])->get();
        return response()->json(['status' => 'success', 'harga' => $hargaByTanggal]);
    }

    public function detailPetani(Request $request, DataPetani $dataPetani)
    {
        $petanis = $dataPetani->select('nama_petani')->where(['nik' => $request->nik])->get();
        return response()->json(['status' => 'success', 'petani' => $petanis]);
    }

    public function spb(Request $request, DataSpb $dataSpb)
    {
        $spb = $dataSpb->with(['korlap' => function ($query) {
            $query->select('id', 'nama_korlap');
        }])->without('tanggal_pengambilan')->get();
        $request_spb = (int)$request->spb;
        $found_spb = [];
        $result_spb = [];
        foreach ($spb as $key => $sp) {
            $exploded_range = explode('-', $sp->range_spb);
            $first_range = (int)$exploded_range[0];
            $second_range = (int)$exploded_range[1];
            if ($request_spb >= $first_range && $request_spb <= $second_range) {
                array_push($found_spb, $sp);
            }
        }
        if (count($found_spb) == 1) {
            $result_spb = $found_spb;
        }
        return response()->json(['status' => 'success', 'spb' => $result_spb]);
    }

    public function detailTimbangan(Request $request, DataTimbangan $dataTimbangan)
    {
        $detail_timbangan = $dataTimbangan->with('kwitansi')->where(['no_ticket' => $request->_ticket])->get();
        return response()->json(['status' => 'success', 'detail' => $detail_timbangan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DataKwitansi $dataKwitansi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(DataKwitansi $dataKwitansi)
    {
        //
        $last_berkas = $dataKwitansi->select('no_berkas')->where(['tanggal_pembayaran' => date('Y-m-d')])->latest()->first();
        if (isset($last_berkas->no_berkas)) {
            $explode_no_berkas = explode('/', $last_berkas->no_berkas);
            $count_berkas = $explode_no_berkas[0];
            $next_count = sprintf("%04s", (int)$count_berkas + 1);
            $explode_no_berkas[0] = $next_count;
            $new_id = implode("/", $explode_no_berkas);
            $last_berkas->no_berkas = $new_id;
        }
        return view('kwitansi.create', ['last_berkas' => $last_berkas]);
    }

    public function generate(KwitansiRequest $request, DataTimbangan $dataTimbangan, DataKwitansi $dataKwitansi)
    {
//        return response()->json($request);
        $data_timbangan = [
            "no_ticket"=>$request->no_ticket,
            "tanggal_masuk"=>$request->tanggal_masuk,
            "no_kendaraan"=>$request->no_kendaraan,
            "pelanggan"=>$request->pelanggan,
            "tandan"=>"",
            "first_weight"=>$request->first_weight,
            "second_weight"=>$request->second_weight,
            "netto_weight"=>$request->netto_weight,
            "potongan_gradding"=>$request->potongan_gradding,
            "setelah_gradding"=>$request->setelah_gradding,
        ];
        $dataTimbangan->create($data_timbangan);
        $berkas_past = $dataKwitansi->select('no_berkas')->where(['no_berkas' => $request->no_berkas, 'no_pembayaran' => $request->no_tiket])->latest()->first();
//        if (!(isset($berkas_past->no_berkas)) or $berkas_past->no_berkas != $request->no_berkas) {
//            $exharga = explode(' ', $request->harga_satuan);
//            $extotal_berat = explode(' ', $request->setelah_grading);
//            $harga_satuan = $exharga[1];
//            $total_berat = $extotal_berat[0];
//            $data_pemilik = (object)[
//                'pemilik' => $request->pemilik_spb,
//                'first_w' => $request->first_weight,
//                'second_w' => $request->second_weight,
//                'netto_w' => $request->netto_weight,
//                'gradding' => $request->potongan_grading,
//                'after_gradding' => $request->setelah_grading,
//                'tgl_timbangan' => Carbon::parse($request->tgl_timbangan)->locale('id')
//            ];
//            $inv = new Invoice();
//            $inv->make("Kwitansi")
//                ->addItem($data_pemilik, $harga_satuan, $total_berat, $request->no_spb)
//                ->number($request->no_berkas)
//                ->with_pagination(true)
//                ->duplicate_header(true)
//                ->date(Carbon::parse($request->tgl_pembayaran)->locale('id'))
//                ->notes('Mohon periksa kembali sebelum meninggalkan kasir')
//                ->customer([
//                    'name' => $request->supir,
//                    'id' => $request->no_kendaraan,
//                    'no_ticket' => $request->no_tiket,
//                ])
//                ->template('print')
//                ->show("no_berkas-$request->no_berkas");
//            $data_to_store = [
//                'no_berkas' => $request->no_berkas,
//                'tanggal_pembayaran' => $request->tgl_pembayaran,
//                'no_pembayaran' => $request->no_tiket,
//                'no_spb' => $request->no_spb,
//                'nama_supir' => $request->supir,
//                'total_harga' => $request->total_pembayaran,
//                'user_id' => Auth::id(),
//                'data_timbangan_id' => $request->timbangan_id,
//                'master_harga_id' => $request->harga_id,
//                'data_spb_id' => $request->spb_id
//            ];
//            $dataKwitansi->create($data_to_store);
//            $dataTimbangan->where(['id' => $request->timbangan_id])->update(['status_pembayaran' => "sudah"]);
//        } else {
//            return redirect()->route('kwitansi.index')->withSuccess("Kwitansi dengan nomor berkas $request->no_berkas sudah dicetak, tidak bisa dicetak lagi");
//        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
