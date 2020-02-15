<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataPetani;
use App\DataSpb;
use App\DataTimbangan;
use App\Http\Requests\KwitansiRequest;
use App\MasterHarga;
use App\MasterKorlap;
use Carbon\Carbon;
use ConsoleTVs\Invoices\Classes\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->with(['petani' => function ($query) {
                return $query->select('id', 'nama_petani');
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

    public function generate(Request $request, DataTimbangan $dataTimbangan, DataKwitansi $dataKwitansi, DataPetani $dataPetani, DataSpb $dataSpb, MasterKorlap $masterKorlap)
    {
        $berkas_past = $dataKwitansi->select('no_berkas')->where(['no_berkas' => $request->no_berkas, 'no_pembayaran' => $request->no_tiket])->latest()->first();
        if (!(isset($berkas_past->no_berkas)) or $berkas_past->no_berkas != $request->no_berkas) {
            $exharga = explode(' ', $request->harga_satuan);
            $extotal_berat = explode(' ', $request->setelah_grading);
            $harga_satuan = $exharga[1];
            $total_berat = $extotal_berat[0];
            $data_pemilik = (object)[
                'pemilik' => $request->pemilik_spb,
                'first_w' => $request->first_weight,
                'second_w' => $request->second_weight,
                'netto_w' => $request->netto_weight,
                'gradding' => $request->potongan_grading,
                'after_gradding' => $request->setelah_grading,
                'tgl_timbangan' => Carbon::parse($request->tgl_timbangan)->locale('id')
            ];
            $inv = new Invoice();
            $inv->make("Kwitansi")
                ->addItem($data_pemilik, $harga_satuan, $total_berat, $request->no_spb)
                ->number($request->no_berkas)
                ->with_pagination(true)
                ->duplicate_header(true)
                ->date(Carbon::parse($request->tgl_pembayaran)->locale('id'))
                ->notes('Mohon periksa kembali sebelum meninggalkan kasir')
                ->customer([
                    'name' => $request->supir,
                    'nik' => $request->nik,
                    'id' => $request->no_kendaraan,
                    'no_ticket' => $request->no_tiket,
                ])
                ->template('print')
                ->download("no_berkas-$request->no_berkas");
            DB::beginTransaction();
            try {
                $no_ticket = $dataTimbangan->select('no_ticket')->where(['no_ticket' => $request->no_tiket])->get();
                if (count($no_ticket) == 0) {
                    $data_timbangan = [
                        "no_ticket" => $request->no_tiket,
                        "tanggal_masuk" => $request->tgl_timbangan,
                        "no_kendaraan" => $request->no_kendaraan,
                        "pelanggan" => "PT. Nabati Mas Asri",
                        "tandan" => "0",
                        "first_weight" => $request->first_weight,
                        "second_weight" => $request->second_weight,
                        "netto_weight" => $request->netto_weight,
                        "potongan_gradding" => $request->potongan_grading,
                        "setelah_gradding" => $request->setelah_grading,
                        "status_pembayaran" => "sudah"
                    ];
                    $dataTimbangan->create($data_timbangan);
                }
                $nik = $dataPetani->select('nik')->where(['nik' => $request->nik])->get();
                if (count($nik) == 0) {
                    $data_petani = [
                        'nik' => $request->nik,
                        'nama_petani' => $request->supir
                    ];
                    $dataPetani->create($data_petani);
                }
                $nama_korlap = $masterKorlap->select('nama_korlap')->where(['nama_korlap' => $request->pemilik_spb])->get();
                if (count($nama_korlap) == 0) {
                    $data_korlap = [
                        'nama_korlap' => $request->pemilik_spb
                    ];
                    $masterKorlap->create($data_korlap);
                }
                $spb = $dataSpb->select('range_spb')->where(['range_spb' => $request->no_spb . "-" . $request->no_spb])->get();
                if (count($spb) == 0) {
                    $korlap = $masterKorlap->select('id')->where(['nama_korlap' => $request->pemilik_spb])->first();
                    $data_spb = [
                        'range_spb' => $request->no_spb . "-" . $request->no_spb,
                        'tanggal_pengambilan' => date('Y-m-d'),
                        'master_korlap_id' => $korlap->id
                    ];
                    $dataSpb->create($data_spb);
                }
                $no_berkas = $dataKwitansi->select('no_berkas')->where(['no_berkas' => $request->no_berkas])->get();
                if (count($no_berkas) == 0) {
                    $petani = $dataPetani->select('id')->where(['nik' => $request->nik])->first();
                    $timbangan = $dataTimbangan->select('id')->where(['no_ticket' => $request->no_tiket])->first();
                    $spb = $dataSpb->select('id')->where(['range_spb' => $request->no_spb . "-" . $request->no_spb])->first();
                    $data_to_store = [
                        'no_berkas' => $request->no_berkas,
                        'tanggal_pembayaran' => $request->tgl_pembayaran,
                        'no_pembayaran' => $request->no_tiket,
                        'no_spb' => $request->no_spb,
                        'data_petani_id' => $petani->id,
                        'total_harga' => $request->total_pembayaran,
                        'user_id' => Auth::id(),
                        'data_timbangan_id' => $request->timbangan_id ?? $timbangan->id,
                        'master_harga_id' => $request->harga_id,
                        'data_spb_id' => $request->spb_id ?? $spb->id
                    ];

                    $dataKwitansi->create($data_to_store);
                    $dataTimbangan->where(['id' => $request->timbangan_id ?? $timbangan->id])->update(['status_pembayaran' => "sudah"]);
                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json(array('status' => 'error', 'message' => $exception));
            }
        } else {
            return redirect()->route('kwitansi.index')->with('status', "Kwitansi dengan nomor berkas $request->no_berkas sudah dicetak, tidak bisa dicetak lagi");
        }
//        return redirect()->route('kwitansi.index')->withSuccess("Kwitansi dengan nomor berkas $request->no_berkas sudah dicetak, tidak bisa dicetak lagi");

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
     * @param DataKwitansi $kwitansi
     * @param DataTimbangan $dataTimbangan
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(DataKwitansi $kwitansi, DataTimbangan $dataTimbangan)
    {
        $dataTimbangan->where(['id' => $kwitansi->data_timbangan_id])->update(['status_pembayaran' => 'belum']);
        $kwitansi->delete();
        return redirect()->route('kwitansi.index')->with('status', "Kwitansi berhasil dihapus");
    }
}
