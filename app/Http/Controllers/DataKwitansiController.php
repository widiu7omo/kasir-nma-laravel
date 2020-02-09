<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataSpb;
use App\DataTimbangan;
use App\MasterHarga;
use Carbon\Carbon;
use ConsoleTVs\Invoices\Classes\Invoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use Symfony\Component\VarDumper\Cloner\Data;

class DataKwitansiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //
        $kwitansis = DataKwitansi::all();
        return view('kwitansi.index', ['kwitansis' => $kwitansis]);
    }

    public function tiket(Request $request, DataTimbangan $timbangan)
    {
        $noTickets = $timbangan->select('*')->where(['no_ticket' => $request->no_ticket])->get();
        return response()->json(['status' => 'success', 'tickets' => $noTickets]);
    }

    public function harga(Request $request, MasterHarga $masterHarga)
    {
        $hargaByTanggal = $masterHarga->select('harga')->where(['tanggal' => $request->tanggal])->first();
        return response()->json(['status' => 'success', 'harga' => $hargaByTanggal]);
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
        return view('kwitansi.create', ['last_berkas' => $last_berkas]);
    }

    public function generate(Request $request, DataTimbangan $dataTimbangan)
    {
//        return response()->json($request);
        $exharga = explode(' ', $request->harga_satuan);
        $extotal_berat = explode(' ', $request->setelah_grading);
        $harga_satuan = $exharga[1];
        $total_berat = $extotal_berat[0];
        $data_pemilik = (object)[
            'pemilik' => $request->pemilik_spb,
            'first_w'=>$request->first_weight,
            'second_w'=>$request->second_weight,
            'netto_w'=>$request->netto_weight,
            'gradding'=>$request->potongan_grading,
            'after_gradding'=>$request->setelah_grading
        ];
        $inv = new Invoice();
        $inv->make("Kwitansi")
            ->addItem($data_pemilik, $harga_satuan, $total_berat, $request->no_spb)
            ->number($request->no_berkas)
            ->with_pagination(true)
            ->duplicate_header(true)
            ->date(Carbon::parse($request->tgl_pembayaran))
            ->notes('Mohon periksa kembali sebelum meninggalkan kasir')
            ->customer([
                'name' => $request->supir,
                'id' => $request->no_kendaraan,
            ])
            ->template('print')
            ->download("no_berkas-$request->no_berkas");

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
