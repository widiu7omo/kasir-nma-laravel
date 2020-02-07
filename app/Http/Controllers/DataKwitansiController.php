<?php

namespace App\Http\Controllers;

use App\DataKwitansi;
use App\DataTimbangan;
use Carbon\Carbon;
use ConsoleTVs\Invoices\Classes\Invoice;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('kwitansi.create');
    }

    public function generate()
    {

        $invoice = Invoice::make("Kwitansi")
            ->addItem('Test Item', 10.25, 2, 1412)
            ->addItem('Test Item 2', 5, 2, 923)
            ->addItem('Test Item 3', 15.55, 5, 42)
            ->addItem('Test Item 4', 1.25, 1, 923)
            ->addItem('Test Item 5', 3.12, 1, 3142)
            ->addItem('Test Item 6', 6.41, 3, 452)
            ->addItem('Test Item 7', 2.86, 1, 1526)
            ->number(4021)
            ->with_pagination(true)
            ->duplicate_header(true)
            ->due_date(Carbon::now()->addMonths(1))
            ->notes('Lrem ipsum dolor sit amet, consectetur adipiscing elit.')
            ->customer([
                'name' => 'Èrik Campobadal Forés',
                'id' => '12345678A',
                'phone' => '+34 123 456 789',
                'location' => 'C / Unknown Street 1st',
                'zip' => '08241',
                'city' => 'Manresa',
                'country' => 'Spain',
            ])
            ->download('demo');
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
