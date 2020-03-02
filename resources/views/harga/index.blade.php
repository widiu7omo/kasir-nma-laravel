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
                        <div class="text-right">
                            <a data-toggle="collapse" href="#collapseHarga" role="button"
                               aria-expanded="false" aria-controls="collapseHarga"
                               class="btn btn-sm btn-primary shadow-sm" id="tambah-harga">Tambah Harga
                                Sawit</a>
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
                        <div class="collapse" id="collapseHarga">
                            <div class="card card-body shadow-none">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="tanggal" class="col-md-6 col-sm-12">Tanggal
                                            <input type="text" autocomplete="off" required
                                                   class="form-control datepicker-dropdown" name="tanggal"
                                                   aria-describedby="helpId"
                                                   placeholder="Tanggal Input"/>
                                        </label>
                                        <label for="harga" class="col-md-6 col-sm-12">Harga Sawit
                                            <input type="text" required
                                                   class="form-control" name="harga" aria-describedby="helpId"
                                                   placeholder="Masukkan Harga Sawit"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered table" id="harga-table">
                                <thead>
                                <tr>
                                    <th style="width:10%">Aksi</th>
                                    <th style="width:8%">No</th>
                                    <th>Tanggal</th>
                                    <th>Harga</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hargas as $key => $hg)
                                    <tr>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="nc-icon nc-ruler-pencil"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form class="form-action" action="{{ route('harga.destroy', $hg) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item" id="btn-edit-harga"
                                                           data-route="{{ route('harga.edit', $hg) }}">{{ __('Edit') }}</a>
                                                        <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __("Apakah anda yakin menghapus harga ini?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$hg->tanggal}}</td>
                                        <td>Rp.{{$hg->harga}}</td>
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
        $('#harga-table').dataTable();
        $('.datepicker-dropdown').datepicker({
            format: "yyyy-mm-dd",
            language: "id",
            autoclose: true
        });
        let formUri = "";
        $(document).on('click', '#btn-edit-harga', function () {
            let route = $(this).data('route');
            formUri = $(this).parent().prop('action');
            console.log(formUri);
            $.ajax({
                url: route,
                dataType: "json",
                success: function (res) {
                    $('.collapse').addClass('show');
                    $('#tambah-harga').text('Simpan');
                    $(this).data('mode', 'u');
                    $('[name="tanggal"]').val(res.tanggal);
                    $('[name="harga"]').val(res.harga);
                },
                error: function (err) {
                    alert(err.statusText)
                }
            })
        })
        $('#tambah-harga').on('click', function () {
            if (!$('.collapse').hasClass('show')) {
                $(this).data('mode', 'i');
                $(this).text('Simpan');
            } else {
                $(this).text('Tambah Harga Sawit');
            }
            if ($(this).text() !== 'Simpan') {
                let url = "";
                let data = {
                    tanggal: $('[name="tanggal"]').val(),
                    harga: $('[name="harga"]').val()
                }
                if ($(this).data('mode') === 'i') {
                    url = "{{route('harga.store')}}";
                } else {
                    url = formUri;
                    console.log(url);
                    data._method = "PUT"
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
