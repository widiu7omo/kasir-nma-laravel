@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'laporan'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Laporan Rekapitulasi Korlap
                            </h5>
                            <p class="card-category">Data tanggal {{$data->start}} sampai {{$data->end}}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Tanggal</label>
                            <input type="text" name="dates" id="dates" class="form-control" placeholder=""
                                   aria-describedby="helpId">
                            <small id="helpId" class="text-muted">Tentukan range tanggal</small>
                        </div>
                    </div>
                    <div class="card-body ">
                        <canvas id=chartHours width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-clock-o"></i>Hari ini
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Rekapitulasi Transaksi</h5>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table-bordered display responsive nowrap table" width="100%" id="rekap-table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>No Pembayaran</th>
                                    <th>No Kendaraan</th>
                                    <th>Tanggal SPB</th>
                                    <th>No SPB</th>
                                    <th>Pemilik SPB</th>
                                    <th>Penerima</th>
                                    <th>Harga</th>
                                    <th>Sum of KG</th>
                                    <th>Potongan</th>
                                    <th>Diterima (Rp)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data->rekap_sbps ?? [] as $index=> $spb)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$spb->tanggal_pembayaran}}</td>
                                        <td>{{$spb->no_pembayaran}}</td>
                                        <td>{{$spb->no_kendaraan}}</td>
                                        <td>{{$spb->tgl_spb}}</td>
                                        <td>{{$spb->no_spb}}</td>
                                        <td>{{$spb->nama_korlap}}</td>
                                        <td>{{$spb->penerima}}</td>
                                        @if($spb->is_custom_price)
                                            <td>{{$spb->custom_price}}</td>
                                        @else
                                            <td>{{$spb->harga}}</td>
                                        @endif
                                        <td>{{$spb->setelah_gradding}}</td>
                                        @if(strtolower(substr($spb->penerima,0,3)) == 'cv.' || strtolower(substr($spb->penerima,0,3)) == 'pt.')
                                            <td>
                                                (Rp. {{round(get_potongan($spb->total_harga,0.25),0,PHP_ROUND_HALF_DOWN)}}
                                                ) 0.25 %
                                            </td>
                                            <td>
                                                Rp. {{round(get_total_with_potongan($spb->total_harga,get_potongan($spb->total_harga,0.25)))}}</td>
                                        @else
                                            <td>0 %</td>
                                            <td>{{$spb->total_harga}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                @if(count($data->rekap_sbps)>0)
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
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
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer ">
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

        $(document).ready(function () {
            let table = $('#rekap-table').dataTable({
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
                        .column(11)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(11, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    $(api.column(11).footer()).html(
                        "Total Rupiah : " + convertToRupiah(pageTotal)
                    );
                    $(api.column(9).footer()).html(
                        "Total KG : " + totalKGbyPage
                    );
                },
                buttons: [
                    {
                        extend: 'excel',
                        autoFilter: true,
                        footer: true,
                        title: "Rekapitulasi Harian tanggal {{isset($_GET['start'])?$_GET['start'].' sampai '.$_GET['end']:date('d-m-Y')}}",
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    // Strip $ from salary column to make it numeric
                                    let newData = data;
                                    let splitData = [];
                                    switch (column) {
                                        case 8 :
                                            if (data !== "") {
                                                // newData = convertToRupiah(data);
                                                newData = data;
                                            }
                                            break;
                                        case 11:
                                            splitData = data.split(" ");
                                            // newData = convertToRupiah(splitData[1]);
                                            newData = parseInt(splitData[1]).toLocaleString('id-ID')
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
                        //     let rows = dt.rows({ filter : 'applied'});
                        //     let total_kg = 0;
                        //     let total_bayar = 0;
                        //     let total_splited;
                        //     rows.data().each(function (item, index) {
                        //         total_kg = total_kg + parseInt(item[9]);
                        //         total_splited = item[10].split(' ');
                        //         total_bayar = total_bayar + parseInt(total_splited[1]);
                        //     });
                        //     dt.row.add(["TOTAL", "", "", "", "", "", "", "", "", total_kg, "Rp. " + total_bayar]).draw(false);
                        //     $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                        //     dt.rows(':last').remove().draw();
                        // }
                    }
                ]
            })
            let dateInput = $('input[name="dates"]');
            dateInput.daterangepicker();
            dateInput.on('apply.daterangepicker', function (ev, picker) {
                let start = picker.startDate.format('YYYY-MM-DD');
                let end = picker.endDate.format('YYYY-MM-DD');
                window.location.href = "{{route('laporan.index','mingguan')}}?start=" + start + "&end=" + end
            });

            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            chartColor = "#FFFFFF";

            ctx = document.getElementById('chartHours').getContext("2d");

            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [{!!$data->days !!}],
                    datasets: {!! $data->data !!}
                },
                options: {
                    legend: {
                        display: true
                    },

                    tooltips: {
                        enabled: true
                    },


                }
            });


        });
    </script>
@endpush
