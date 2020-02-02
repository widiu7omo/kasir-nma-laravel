<?php

namespace App\Http\Controllers;

use App\MasterHarga;
use Illuminate\Http\Request;

class MasterHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $masterHarga = MasterHarga::all();
        return view('harga.index', ['hargas' => $masterHarga]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('harga.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MasterHarga $model)
    {
        //
        $data_request = [
            'harga' => $request->harga,
            'tanggal' => $request->tanggal
        ];
        $model->create($data_request);
        return redirect()->route('harga.index')->withStatus(__('Harga berhasil ditambahkan'));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(MasterHarga $harga)
    {
        //
        return response()->json($harga);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, MasterHarga $harga)
    {
        //
        $data_request = [
            'harga' => $request->harga,
            'tanggal' => $request->tanggal
        ];
        $harga->update($data_request);
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterHarga $harga)
    {
        //
        $harga->delete();
        return redirect()->route('harga.index')->withStatus(__("Harga berhasil di hapus"));
    }
}
