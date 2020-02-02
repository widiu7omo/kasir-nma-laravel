@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'korlap'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Korlap</h5>
                        <p class="card-category">Daftar Koordinator Lapangan PMA</p>
                        <div class="text-right">
                            <a id="tambah-korlap" data-toggle="collapse" href="#collapseKorlap" role="button"
                               aria-expanded="false" aria-controls="collapseKorlap"
                               class="btn btn-sm btn-primary shadow-sm">Tambah Koordinator Lapangan</a>
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
                        <div class="collapse" id="collapseKorlap">
                            <div class="card card-body shadow-none">
                                <div class="form-group">
                                    <label for="nama-korlap" class="col-12">Nama Koordinator Lapangan
                                        <input type="text"
                                               class="form-control" name="korlap" aria-describedby="helpId"
                                               placeholder="Masukkan Nama"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered table" id="korlap-table">
                                <thead>
                                <tr>
                                    <th style="width:10%">Aksi</th>
                                    <th style="width:8%">No</th>
                                    <th>Nama Koordinator Lapangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($korlaps as $key => $korlap)
                                    <tr>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="nc-icon nc-ruler-pencil"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form class="form-action"
                                                          action="{{ route('korlap.destroy', $korlap) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item" id="btn-edit-korlap"
                                                           href="javascript:void(0)"
                                                           data-route="{{ route('korlap.edit', $korlap) }}">{{ __('Edit') }}</a>
                                                        <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __("Apakah anda yang menghapus data ini?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$korlap->nama_korlap}}</td>
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
        $('#korlap-table').dataTable();
        let formUri = "";
        $(document).on('click', '#btn-edit-korlap', function () {
            let route = $(this).data('route');
            formUri = $(this).parent().prop('action');
            console.log(formUri);

            $.ajax({
                url: route,
                dataType: "json",
                success: function (res) {
                    $('.collapse').addClass('show');
                    $('#tambah-korlap').text('Simpan');
                    $(this).data('mode', 'u');
                    $('[name="korlap"]').val(res.nama_korlap);
                },
                error: function (err) {
                    alert(err.statusText)
                }
            })
        })
        $('#tambah-korlap').on('click', function () {
            if (!$('.collapse').hasClass('show')) {
                $(this).data('mode', 'i');
                $(this).text('Simpan');
            } else {
                $(this).text('Tambah Koordinator Lapangan');
            }
            if ($(this).text() !== 'Simpan') {
                let url = "";
                let data = {korlap: $('[name="korlap"]').val()}
                if ($(this).data('mode') === 'i') {
                    url = "{{route('korlap.store')}}";

                } else {
                    url = formUri;
                    data._method = "PUT";
                    console.log(url)
                }
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    success: function (res) {
                        alert(res.status);
                        window.location.reload();
                    },
                    error: function (err) {
                        alert(err.statusText);
                    }
                })
            }
        })
    </script>
@endpush
