<?php

namespace App\Http\Controllers;

use App\DataPetani;
use http\Env\Response;
use Illuminate\Http\Request;

class DataPetaniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DataPetani $dataPetani
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DataPetani $dataPetani)
    {
        //
        $petanis = $dataPetani->all();
        return view('petani.index', ['petanis' => $petanis]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param DataPetani $dataPetani
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, DataPetani $dataPetani)
    {
        //
        $dataRequest = [
            'nik' => $request->nik,
            'nama_petani' => $request->petani
        ];
        $dataPetani->create($dataRequest);
        return response()->json(['status' => 'success']);
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
     * @param DataPetani $petani
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(DataPetani $petani)
    {
        //
        return response()->json($petani);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, DataPetani $petani)
    {
        //
        $data_request = [
            'nik' => $request->nik,
            'nama_petani' => $request->petani
        ];
        $petani->update($data_request);
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DataPetani $petani)
    {
        //
        $petani->delete();
        return redirect()->route('petani.index')->withStatus(__("Data Petani berhasil di hapus"));
    }
}
