@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'petani'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Petani</h5>
                        <p class="card-category">Daftar Data Petani sebagai Pelanggan</p>
                        <div class="text-right">
                            <a id="tambah-petani" data-toggle="collapse" href="#collapsepetani" role="button"
                               aria-expanded="false" aria-controls="collapsepetani"
                               class="btn btn-sm btn-primary shadow-sm">Tambah Data Petani</a>
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
                        <div class="collapse" id="collapsepetani">
                            <div class="card card-body shadow-none">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="nama-petani" class="col-12">NIK Petani
                                                <input type="number" required
                                                       class="form-control" name="nik" aria-describedby="helpId"
                                                       placeholder="Masukkan NIK"/>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="nama-petani" class="col-12">Nama Petani
                                                <input type="text" required
                                                       class="form-control" name="petani" aria-describedby="helpId"
                                                       placeholder="Masukkan Nama"/>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered table" id="petani-table">
                                <thead>
                                <tr>
                                    <th style="width:10%">Aksi</th>
                                    <th style="width:8%">No</th>
                                    <th>NIK</th>
                                    <th>Nama Petani</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($petanis as $key => $petani)
                                    <tr>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="nc-icon nc-ruler-pencil"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form class="form-action"
                                                          action="{{ route('petani.destroy', $petani) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item" id="btn-edit-petani"
                                                           href="javascript:void(0)"
                                                           data-route="{{ route('petani.edit', $petani) }}">{{ __('Edit') }}</a>
                                                        <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __("Apakah anda yang menghapus data ini?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$petani->nik}}</td>
                                        <td>{{$petani->nama_petani}}</td>
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
        $('#petani-table').dataTable();
        let formUri = "";
        $(document).on('click', '#btn-edit-petani', function () {
            let route = $(this).data('route');
            formUri = $(this).parent().prop('action');
            console.log(formUri);

            $.ajax({
                url: route,
                dataType: "json",
                success: function (res) {
                    $('.collapse').addClass('show');
                    $('#tambah-petani').text('Simpan');
                    $(this).data('mode', 'u');
                    $('[name="petani"]').val(res.nama_petani);
                    $('[name="nik"]').val(res.nik);
                },
                error: function (err) {
                    alert(err.statusText)
                }
            })
        })
        $('#tambah-petani').on('click', function () {
            if (!$('.collapse').hasClass('show')) {
                $(this).data('mode', 'i');
                $(this).text('Simpan');
            } else {
                $(this).text('Tambah Koordinator Lapangan');
            }
            if ($(this).text() !== 'Simpan') {
                let url = "";
                let data = {
                    petani: $('[name="petani"]').val(),
                    nik: $('[name="nik"]').val()
                }
                if ($(this).data('mode') === 'i') {
                    url = "{{route('petani.store')}}";

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
