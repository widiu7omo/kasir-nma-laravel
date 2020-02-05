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
                                <h5 class="card-title">Buat Kwitansi</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{route('kwitansi.index')}}" class="btn btn-sm btn-primary">Kembali</a>
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
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="no_berkas"></label>
                                            <input type="text" name="no_berkas" id="no_berkas" class="form-control"
                                                   placeholder="Nomor Berkas" aria-describedby="helpId" readonly>
                                            <small id="helpId" class="text-muted">Otomatis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="no_tiket"></label>
                                            <input type="text" name="no_tiket" id="no_tiket" class="form-control"
                                                   placeholder="Nomor Tiket"
                                                   aria-describedby="helpId">
                                            <small id="helpId" class="text-muted">Masukkan Nomor Tiket</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="tgl_pembayaran"></label>
                                            <input type="text" name="tgl_pembayaran" id="tgl_pembayaran"
                                                   class="form-control" placeholder="Tanggal Pembayaran"
                                                   aria-describedby="helpId" readonly>
                                            <small id="helpId" class="text-muted">Otomatis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="no_spb"></label>
                                            <input type="text" name="no_spb" id="no_spb" class="form-control"
                                                   placeholder="No. SPB"
                                                   aria-describedby="helpId">
                                            <small id="helpId" class="text-muted">Masukkan Nomor SPB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="supir"></label>
                                    <input type="text" name="supir" id="supir" class="form-control"
                                           placeholder="Nama Sopir"
                                           aria-describedby="helpId">
                                    <small id="helpId" class="text-muted">Masukkan nama pengambil (Sopir)</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="no_kendaraan"></label>
                                            <input type="text" name="no_kendaraan" id="no_kendaraan"
                                                   class="form-control"
                                                   placeholder="Nomor Kendaraan"
                                                   aria-describedby="helpId" readonly>
                                            <small id="helpId" class="text-muted">Otomatis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="pemilik_spb"></label>
                                            <input type="text" name="pemilik_spb" id="pemilik_spb" class="form-control"
                                                   placeholder="Pemilik SPB" readonly
                                                   aria-describedby="helpId">
                                            <small id="helpId" class="text-muted">Otomatis</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="total_berat"></label>
                                            <input type="text" name="total_berat" id="total_berat" class="form-control"
                                                   placeholder="Total Berat (KG)"
                                                   aria-describedby="helpId" readonly>
                                            <small id="helpId" class="text-muted">Otomatis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="harga_satuan"></label>
                                            <input type="text" name="harga_satuan" id="harga_satuan"
                                                   class="form-control"
                                                   placeholder="Harga"
                                                   aria-describedby="helpId" readonly>
                                            <small id="helpId" class="text-muted">Otomatis</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="jumlah_total"></label>
                                    <input type="text" name="jumlah_total" id="jumlah_total" class="form-control"
                                           placeholder="Jumlah Total"
                                           aria-describedby="helpId" readonly>
                                    <small id="helpId" class="text-muted">Otomatis</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="{{route('kwitansi.generate')}}" class="btn btn-primary">Cetak Kwitansi</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
