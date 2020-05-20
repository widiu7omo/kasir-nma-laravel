<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataPetani;
use App\DataSpb;
use App\DataTimbangan;
use App\MasterHarga;
use App\MasterKorlap;
use Carbon\Carbon;
use ConsoleTVs\Invoices\Classes\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $start = "";
        $end = "";
        $assignEnd = "";
        $assignStart = "";
        $where = "";

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
            }])->where('tanggal_pembayaran', '>=', Carbon::now()->format('Y-m-d'))->where('tanggal_pembayaran', '<=', Carbon::now()->format('Y-m-d'))->get();
        if (isset($request->start) && isset($request->end)) {
            $where = 'tanggal_pembayaran';
            $start = $request->start;
            $end = $request->end;
            $assignStart = ">=";
            $assignEnd = "<=";
            $kwitansis = DataKwitansi::
            with(['user' => function ($query) {
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
                ->where($where, $assignStart, $start)->where($where, $assignEnd, $end)->get();
        }
//        return response()->json($kwitansis);
        return view('kwitansi.index', ['kwitansis' => $kwitansis]);
    }

    public function kosong(DataTimbangan $dataTimbangan)
    {
        $kosong = $dataTimbangan->where('no_ticket', 'like', "%null%")->orderBy('no_ticket', 'desc')->get();
        return response()->json(['status' => 'success', 'kosong' => $kosong]);
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
        if ($request->nik) {
            $petanis = $dataPetani->selectRaw('nik')->where('id', '=', $request->id)->get();
            return response()->json(['status' => 'success', 'petani' => $petanis]);
        }
        $petanis = $dataPetani->selectRaw('id,nama_petani text')->where('nama_petani', 'like', "%" . $request->petani . '%')->get();
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
        if (count($found_spb) > 0) {
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

    private function getDataPetani($require = 'id', $request)
    {
        $petani = [];
        if (is_numeric($request->supir)) {
            $petani = DataPetani::select('nama_petani', 'nik')->where(["id" => $request->supir])->get();
            $data_petani = $petani[0]->nama_petani;
            if ($require == 'id') {
                $data_petani = $request->supir;
            }
        } else {
            $data_petani = $request->supir;
            if ($require == 'id') {
                $petani = DataPetani::select('id')->where(["nama_petani" => $request->supir])->get();
                $data_petani = $petani[0]->id;
            }
        }
        return (object)['data_petani' => $data_petani, 'petani' => $petani];

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
                'tgl_timbangan' => Carbon::parse($request->tgl_timbangan)->locale('id'),
                'sub_total' => $harga_satuan * $total_berat,
                'potongan' => ($harga_satuan * $total_berat * 0.25) / 100
            ];
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
                $getPetani = $this->getDataPetani('nama', $request);
                $petani = $getPetani->petani;
                $nama_petani = $getPetani->data_petani;
                $data_petani = [
                    'nik' => $request->nik,
                    'nama_petani' => $nama_petani
                ];
                if (count($petani) > 0) {
                    //@TODO: change logic here. validate, only create if nik not same, but nik with - allowed
                    if ($request->nik != '-') {
                        if ($petani[0]->nik != $request->nik) {
                            $dataPetani->create($data_petani);
                        }
                    } else {
                        if ($petani[0]->nama_petani != $nama_petani) {
                            $dataPetani->create($data_petani);
                        }
                    }
                } else {
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
                    $check_spb = $dataSpb->select('range_spb')->get();
                    $request_spb = $request->no_spb;
                    $found_spb = [];
                    foreach ($check_spb as $check) {
                        $exploded_range = explode('-', $check->range_spb);
                        $first_range = (int)$exploded_range[0];
                        $second_range = (int)$exploded_range[1];
                        if ($request_spb >= $first_range && $request_spb <= $second_range) {
                            array_push($found_spb, $check);
                        }
                    }
                    $korlap = $masterKorlap->select('id')->where(['nama_korlap' => $request->pemilik_spb])->first();
                    $data_spb = [
                        'range_spb' => $request->no_spb . "-" . $request->no_spb,
                        'tanggal_pengambilan' => date('Y-m-d'),
                        'master_korlap_id' => $korlap->id
                    ];
                    if (count($found_spb) === 0) {
                        $dataSpb->create($data_spb);
                    }
                }
                $no_berkas = $dataKwitansi->select('no_berkas')->where(['no_berkas' => $request->no_berkas])->get();
                if (count($no_berkas) == 0) {
                    $getPetani = $this->getDataPetani('id', $request);
                    $timbangan = $dataTimbangan->select('id')->where(['no_ticket' => $request->no_tiket])->first();
                    $spb = $dataSpb->select('id')->where(['range_spb' => $request->no_spb . "-" . $request->no_spb])->first();
                    $data_to_store = [
                        'no_berkas' => $request->no_berkas,
                        'tanggal_pembayaran' => $request->tgl_pembayaran,
                        'no_pembayaran' => $request->no_tiket,
                        'no_spb' => $request->no_spb,
                        'data_petani_id' => $getPetani->data_petani,
                        'total_harga' => $request->total_pembayaran,
                        'user_id' => Auth::id(),
                        'data_timbangan_id' => $request->timbangan_id ?? $timbangan->id,
                        'master_harga_id' => $request->harga_id,
                        'data_spb_id' => $request->spb_id ?? $spb->id
                    ];
                    $dataKwitansi->create($data_to_store);
                    $dataTimbangan->where(['id' => $request->timbangan_id ?? $timbangan->id])->update(['status_pembayaran' => "sudah"]);
                    $getPetani = $this->getDataPetani('nama', $request);
                    $inv = new Invoice();
                    $inv->make("Kwitansi")
                        ->addItem($data_pemilik, $harga_satuan, $total_berat, $request->no_spb)
                        ->number($request->no_berkas)
                        ->with_pagination(true)
                        ->duplicate_header(true)
                        ->date(Carbon::parse($request->tgl_pembayaran)->locale('id'))
                        ->notes('Mohon periksa kembali sebelum meninggalkan kasir')
                        ->customer([
                            'name' => $getPetani->data_petani,
                            'nik' => $request->nik,
                            'id' => $request->no_kendaraan,
                            'no_ticket' => $request->no_tiket,
                            'prefix_name' => substr($getPetani->data_petani, 0, 3)])
                        ->template('print')
                        ->download("no_berkas-$request->no_berkas");
                    DB::commit();
                    return redirect()->route('kwitansi.index')->with('status', "Kwitansi berhasil di cetak");
                } else {
                    return redirect()->route('kwitansi.index')->with('status', "Kwitansi dengan nomor berkas $request->no_berkas sudah dicetak, tidak bisa dicetak lagi");
                }
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json(array('status' => 'error', 'message' => $exception->getMessage()));
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
