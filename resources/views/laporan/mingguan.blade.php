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
                                        <td>{{$spb->harga}}</td>
                                        <td>{{$spb->setelah_gradding}}</td>
                                        <td>{{$spb->total_harga}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
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

        $(document).ready(function () {
            let table = $('#rekap-table').dataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf'
                ]
            })
            let dateInput = $('input[name="dates"]');
            dateInput.daterangepicker();
            dateInput.on('apply.daterangepicker', function (ev, picker) {
                console.log(picker.startDate.format('YYYY-MM-DD'));
                console.log(picker.endDate.format('YYYY-MM-DD'));
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
