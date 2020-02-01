@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'harga'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Harga Sawit</h5>
                        <p class="card-category">Daftar harga sawit per hari</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-bordered table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Harga</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>20 Februari 2020</td>
                                    <td>1700</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-history"></i> Updated 3 minutes ago
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
