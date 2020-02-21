@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'harian'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Laporan Harian Korlap</h5>
                        <p class="card-category">Hari ini {{date('d-m-Y')}}</p>
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
                        <h5 class="card-title">Data Tonase Koordinator Lapangan</h5>
                        <p class="card-category">Per hari</p>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div class="legend">
                            <i class="fa fa-circle text-primary"></i> Opened
                            <i class="fa fa-circle text-warning"></i> Read
                            <i class="fa fa-circle text-danger"></i> Deleted
                            <i class="fa fa-circle text-gray"></i> Unopened
                        </div>
                        <hr>
                        <div class="stats">
                            <i class="fa fa-calendar"></i> Number of emails sent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
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
                        enabled: false
                    },


                }
            });


        });
    </script>
@endpush
