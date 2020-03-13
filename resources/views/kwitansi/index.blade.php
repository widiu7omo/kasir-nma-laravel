@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'kwitansi'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">Kwitansi</h5>
                                <p class="card-category">Data Kwitansi yang telah dilakukan pembayaran</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{route("kwitansi.create")}}" class="btn btn btn-primary">Buat Kwitansi</a>
                            </div>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-sm btn-success"></button>
                                    <span>Hari ini</span><br>
                                    <button class="btn btn-sm btn-info"></button>
                                    <span>Hari sebelumnya</span>
                                </div>
                                <div class="form-group">
                                    <label for="">Pilih Tanggal Pembayaran</label>
                                    <input type="text" name="dates" id="dates" class="form-control"
                                           placeholder="Range Tanggal"
                                           aria-describedby="helpId">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered display responsive nowrap table" id="kwitansi-table"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th class="sorting_asc_disabled">No</th>
                                    <th style="width:10%">Nomor Berkas</th>
                                    <th style="width:8%">Tanggal Pembayaran</th>
                                    <th style="width:8%">Tanggal Timbangan</th>
                                    <th>No Pembayaran</th>
                                    <th>Nama Pengambil</th>
                                    <th>No Kendaraan</th>
                                    <th>Pemilik SPB</th>
                                    <th>Harga</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Jumlah</th>
                                    <th>Potongan</th>
                                    <th>Diterima</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($kwitansis ?? [] as $key => $kwitansi)
                                    <tr>
                                        <td class="text-center">
                                            {{$key+1}}
                                        </td>
                                        <td>{{$kwitansi->no_berkas}}</td>
                                        <td class="{{$kwitansi->tanggal_pembayaran == date('Y')}}">{{$kwitansi->tanggal_pembayaran}}</td>
                                        <td>{{$kwitansi->timbangan->tanggal_masuk}}</td>
                                        <td>{{$kwitansi->no_pembayaran}}</td>
                                        <td>{{$kwitansi->petani->nama_petani}}</td>
                                        <td>{{$kwitansi->timbangan->no_kendaraan}}</td>
                                        <td>{{$kwitansi->spb->korlap->nama_korlap}}</td>
                                        <td>{{$kwitansi->harga->harga}}</td>
                                        <td>{{$kwitansi->timbangan->setelah_gradding}}</td>
                                        <td>{{$kwitansi->total_harga}}</td>
                                        @if(strtolower(substr($kwitansi->petani->nama_petani,0,3)) == 'cv.' || strtolower(substr($kwitansi->petani->nama_petani,0,3)) == 'pt.')
                                            <td>(Rp. {{round(get_potongan($kwitansi->total_harga,0.25),0,PHP_ROUND_HALF_DOWN)}}) 0.25 %</td>
                                            <td>Rp. {{round(get_total_with_potongan($kwitansi->total_harga,get_potongan($kwitansi->total_harga,0.25)))}}</td>
                                        @else
                                            <td>0 %</td>
                                            <td>{{$kwitansi->total_harga}}</td>
                                        @endif
                                        <td>
                                            <form class="form-action"
                                                  action="{{ route('kwitansi.destroy', $kwitansi) }}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirm('{{ __("Apakah anda yakin menghapus Kwitansi ini?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="sorting_asc_disabled"></th>
                                    <th style="width:10%"></th>
                                    <th style="width:8%"></th>
                                    <th style="width:8%"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function convertToRupiah(angka) {
            var rupiah = '';
            var angkarev = angka.toString().split('').reverse().join('');
            for (var i = 0; i < angkarev.length; i++) if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
            return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
        }

        let dateInput = $('input[name="dates"]');
        dateInput.daterangepicker();
        dateInput.on('apply.daterangepicker', function (ev, picker) {
            let start = picker.startDate.format('YYYY-MM-DD');
            let end = picker.endDate.format('YYYY-MM-DD');
            window.location.href = "{{route('kwitansi.index')}}?start=" + start + "&end=" + end
        });
        let table = $('#kwitansi-table').dataTable({
            responsive: true,
            "pageLength": 100,
            dom: 'Bfrtip',
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\Rp.,]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                totalKG = api.column(9).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b)
                }, 0)
                totalKGbyPage = api.column(9, {page: 'current'}).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b)
                }, 0)
                // Total over all pages
                total = api
                    .column(10)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                totalAll = api
                    .column(12)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(10, {page: 'current'})
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                pageTotalAll = api
                    .column(12, {page: 'current'})
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(10).footer()).html(
                    "Total Rupiah : " + convertToRupiah(pageTotal)
                );
                $(api.column(12).footer()).html(
                    "Total Rupiah : " + convertToRupiah(pageTotalAll)
                );
                $(api.column(9).footer()).html(
                    "Total KG : " + totalKGbyPage
                );
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-sm btn-default',
                    autoFilter: true,
                    footer: true,
                    title: "Laporan Kwitansi Harian tanggal {{isset($_GET['start'])?$_GET['start'].' sampai '.$_GET['end']:date('d-m-Y')}}",
                    exportOptions: {
                        columns: 'th:not(:last-child)',
                        format: {
                            body: function (data, row, column, node) {
                                // Strip $ from salary column to make it numeric
                                let newData = data;
                                let splitData = [];
                                switch (column) {
                                    case 8 :
                                        if (data !== "") {
                                            newData = convertToRupiah(data);
                                        }
                                        break;
                                    case 10:
                                        splitData = data.split(" ");
                                        newData = convertToRupiah(splitData[1]);
                                        break;
                                    case 12:
                                        splitData = data.split(" ");
                                        newData = convertToRupiah(splitData[1]);
                                        break;
                                }
                                return newData;
                            }
                        }
                    },
                    customize: function (xlsx) {
                        let sheet = xlsx.xl.worksheets['sheet1.xml'];
                        // jQuery selector to add a border
                        $('row c[r*="2"]', sheet).attr({'s': '42'});
                        $('row:last', sheet).attr({'s': '25'});
                        $('row c[r*="10"]', sheet).attr('s', "63");
                        $('row c[r*="11"]', sheet).attr('s', "63");
                    },
                    // action: function (e, dt, button, config) {
                    //     let copyDt = dt;
                    //     let rows = copyDt.rows({filter: 'applied'});
                    //     let total_kg = 0;
                    //     let total_bayar = 0;
                    //     let total_splited;
                    //     rows.data().each(function (item, index) {
                    //         total_kg = total_kg + parseInt(item[9]);
                    //         total_splited = item[10].split(' ');
                    //         total_bayar = total_bayar + parseInt(total_splited[1]);
                    //     });
                    //     copyDt.row.add(["TOTAL", "", "", "", "", "", "", "", "", total_kg, "Rp. " + total_bayar, "Rp. " + total_bayar, ""]).draw(false);
                    //     $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, copyDt, button, config);
                    //     dt.rows(':last').remove().draw();
                    // }

                }
            ]
        })
    </script>
@endpush
