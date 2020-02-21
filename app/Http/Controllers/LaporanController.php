<?php

namespace App\Http\Controllers;

use App\MasterKorlap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $subpage
     * @return \Illuminate\Http\Response
     */
    public function index(string $subpage, Request $request)
    {
        $data = [];
        if (view()->exists("laporan.{$subpage}")) {
            switch ($subpage) {
                case 'harian':
                    break;
                case 'mingguan':
                    //Grafik
                    Carbon::setLocale('id');
                    $days = Carbon::getDays();
                    array_shift($days);
                    array_push($days, 'Sunday');
                    $dataBySPB = MasterKorlap::all();
                    $dataChartSpbByKorlap = [];
                    $colorKorlap = ['#6bd098', '#f17e5d', '#fcc468', '#51CACF'];
                    $start = Carbon::now()->locale('id');
                    $end = Carbon::now()->locale('id');
                    if (isset($request->start) && isset($request->end)) {
                        $start = Carbon::parse($request->start);
                        $end = Carbon::parse($request->end);
                        $startDayName = $start->locale('id')->translatedFormat('l');
                        $endDayName = $end->locale('id')->translatedFormat('l');
                        $diffDay = $start->diffInDays($end);
                        $days = [];
                        $incrementDate = $start;
                        for ($i = 0; $i <= $diffDay; $i++) {
                            if ($i == 0) {
                                array_push($days, $startDayName);
                            } elseif ($i == $diffDay) {
                                array_push($days, $endDayName);
                            } else {
                                array_push($days, $incrementDate->addDay()->locale('id')->translatedFormat('D - d'));
                            }
                        }
                    }
                    foreach ($dataBySPB as $key => $bySpb) {
                        $data_days = [];
                        $start = Carbon::parse($request->start ?? $start);
                        foreach ($days as $i => $day) {
                            $current_date = $start;
                            if ($i == 0) {
                                $day_date = $current_date->format('Y-m-d');
                            } else {
                                $day_date = $current_date->addDay()->format('Y-m-d');
                            }

                            $berat_by_korlap = DB::table('data_timbangans')->join('data_kwitansis', 'data_kwitansis.data_timbangan_id', '=', 'data_timbangans.id')
                                ->join('data_spbs', 'data_kwitansis.data_spb_id', '=', 'data_spbs.id')
                                ->rightJoin('master_korlaps', 'data_spbs.master_korlap_id', '=', 'master_korlaps.id')
                                ->selectRaw('if(sum(setelah_gradding) is NULL,0,sum(setelah_gradding)) total')
                                ->where(['tanggal_pembayaran' => $day_date, 'master_korlaps.id' => $bySpb->id])
                                ->get('total');

                            array_push($data_days, $berat_by_korlap[0]->total);
                        }
                        $single_spb_chart = [
                            "label" => $bySpb->nama_korlap,
                            "fill" => false,
                            "borderColor" => $colorKorlap[$key] ?? '#000',
                            "backgroundColor" => 'transparent',
                            "pointBorderColor" => $colorKorlap[$key] ?? '#000',
                            "pointRadius" => 4,
                            "pointHoverRadius" => 4,
                            "borderWidth" => 3,
                            "pointBorderWidth" => 8,
                            "data" => $data_days
                        ];
                        array_push($dataChartSpbByKorlap, $single_spb_chart);
                    }
                    //Table
                    $spbs = [];
                    if ($request->mode == 'harian' ?? null) {
                        $spbs = DB::table('data_timbangans')->join('data_kwitansis', 'data_kwitansis.data_timbangan_id', '=', 'data_timbangans.id')
                            ->join('data_spbs', 'data_kwitansis.data_spb_id', '=', 'data_spbs.id')
                            ->join('master_hargas', 'master_hargas.id', '=', 'data_kwitansis.master_harga_id')
                            ->join('master_korlaps', 'data_spbs.master_korlap_id', '=', 'master_korlaps.id')
                            ->join('data_petanis', 'data_kwitansis.data_petani_id', '=', 'data_petanis.id')
                            ->selectRaw('tanggal_pembayaran,no_pembayaran,no_kendaraan,tanggal_masuk tgl_spb,no_spb,nama_petani penerima,nama_korlap,harga,setelah_gradding,total_harga')
                            ->where(['tanggal_pembayaran' => $request->start])
                            ->get();
                    }
                    else{
                        $spbs = DB::table('data_timbangans')->join('data_kwitansis', 'data_kwitansis.data_timbangan_id', '=', 'data_timbangans.id')
                            ->join('data_spbs', 'data_kwitansis.data_spb_id', '=', 'data_spbs.id')
                            ->join('master_hargas', 'master_hargas.id', '=', 'data_kwitansis.master_harga_id')
                            ->join('master_korlaps', 'data_spbs.master_korlap_id', '=', 'master_korlaps.id')
                            ->join('data_petanis', 'data_kwitansis.data_petani_id', '=', 'data_petanis.id')
                            ->selectRaw('tanggal_pembayaran,no_pembayaran,no_kendaraan,tanggal_masuk tgl_spb,no_spb,nama_petani penerima,nama_korlap,harga,setelah_gradding,total_harga')
                            ->whereBetween('tanggal_pembayaran',[$request->start,$request->end])
                            ->get();
                    }
                    $data = (object)[
                        'days' => "'" . implode("','", $days) . "'",
                        'data' => json_encode($dataChartSpbByKorlap),
                        'start' => Carbon::parse($request->start ?? date('Y-m-d'))->translatedFormat('jS F Y'),
                        'end' => Carbon::parse($request->end ?? date('Y-m-d'))->translatedFormat('jS F Y'),
                        'rekap_sbps' => $spbs
                    ];
                    break;
                case 'bulanan':
                    break;
                default:
                    abort(404);
            }
            return view("laporan.{$subpage}", ['data' => $data]);
        }
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
