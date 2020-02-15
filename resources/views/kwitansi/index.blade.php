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
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-success"></button>
                                <span>Hari ini</span>
                                <button class="btn btn-sm btn-info"></button>
                                <span>Hari sebelumnya</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered display responsive nowrap table" id="kwitansi-table"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th class="sorting_asc_disabled"></th>
                                    <th style="width:10%">Nomor Berkas</th>
                                    <th style="width:8%">Tanggal Pembayaran</th>
                                    <th style="width:8%">Tanggal Timbangan</th>
                                    <th>No Pembayaran</th>
                                    <th>Nama Pengambil</th>
                                    <th>No Kendaraan</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Diterima</th>
                                    <th>Pemilik SPB</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($kwitansis ?? [] as $key => $kwitansi)
                                    <tr>
                                        <td class="text-center">
                                        </td>
                                        <td>{{$kwitansi->no_berkas}}</td>
                                        <td>{{$kwitansi->tanggal_pembayaran}}</td>
                                        <td>{{$kwitansi->timbangan->tanggal_masuk}}</td>
                                        <td>{{$kwitansi->no_pembayaran}}</td>
                                        <td>{{$kwitansi->petani->nama_petani}}</td>
                                        <td>{{$kwitansi->timbangan->no_kendaraan}}</td>
                                        <td>{{$kwitansi->timbangan->setelah_gradding}}</td>
                                        <td>{{$kwitansi->harga->harga}}</td>
                                        <td>{{$kwitansi->total_harga}}</td>
                                        <td>{{$kwitansi->total_harga}}</td>
                                        <td>{{$kwitansi->spb->korlap->nama_korlap}}</td>
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
        let table = $('#kwitansi-table').dataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf'
            ]
        })
    </script>
@endpush
