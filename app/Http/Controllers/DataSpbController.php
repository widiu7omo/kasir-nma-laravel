<?php

namespace App\Http\Controllers;

use App\DataSpb;
use App\MasterKorlap;
use Illuminate\Http\Request;

class DataSpbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        //
        $dataspb = DataSpb::with('korlap')->get();
        $korlaps = MasterKorlap::all();
        return view('spb.index', ['dataspb' => $dataspb, 'korlaps' => $korlaps]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, DataSpb $spb)
    {
        //
        $data_request = [
            'range_spb' => $request->range,
            'tanggal_pengambilan' => $request->tanggal,
            'master_korlap_id' => $request->id
        ];
        $spb->create($data_request);
        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(DataSpb $spb)
    {
        //
        return response()->json($spb);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, DataSpb $spb)
    {
        //
        $data_request = [
            'range_spb' => $request->range,
            'tanggal_pengambilan' => $request->tanggal,
            'master_korlap_id' => $request->id
        ];
        $spb->update($data_request);
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DataSpb $spb)
    {
        //
        $spb->delete();
        return redirect()->route('spb.index')->withSuccess('Spb berhasil dihapus');
    }
}
