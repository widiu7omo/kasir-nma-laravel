<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTimbangan;
use Symfony\Component\VarDumper\Cloner\Data;

class DataTimbanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $timbangans = DataTimbangan::all();
        if(isset($_GET['ajax'])){
            return json_encode($timbangans);
        }
        return view('timbangan.index',['timbangans'=>$timbangans]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request,DataTimbangan $dataTimbangan)
    {
        //
        $dataTimbangan->insert($request->payload);
        return response()->json(['status'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function currentData(Request $request,DataTimbangan $dataTimbangan){
        $timbangans = $dataTimbangan->select('no_ticket')->where(['tanggal_masuk'=>$request->date])->get();
        return response()->json(['status'=>'success','timbangans'=>$timbangans]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataTimbangan $timbangan)
    {
        //
        $timbangan->delete();
        return redirect()->route('timbangan.index')->withStatus('Berhasil hapus data timbangan');
    }
}
