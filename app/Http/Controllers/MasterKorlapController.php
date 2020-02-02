<?php

namespace App\Http\Controllers;

use App\MasterKorlap;
use Illuminate\Http\Request;

class MasterKorlapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $korlaps = MasterKorlap::all();
        return view('korlap.index', ['korlaps' => $korlaps]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
    public function store(Request $request,MasterKorlap $korlap)
    {
        //
        $data_request = [
            'nama_korlap'=>$request->korlap,
        ];
        $korlap->create($data_request);
        return response()->json(['status'=>'success']);
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
     * @param MasterKorlap $korlap
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(MasterKorlap $korlap)
    {
        //
        return response()->json($korlap);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param MasterKorlap $korlap
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, MasterKorlap $korlap)
    {
        //
        $data_request = [
            'nama_korlap'=>$request->korlap
        ];
        $korlap->update($data_request);
        return response()->json(['status'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MasterKorlap $korlap
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(MasterKorlap $korlap)
    {
        //
        $korlap->delete();
        return redirect()->route('korlap.index')->withStatus('Korlap berhasil di hapus');
    }
}
