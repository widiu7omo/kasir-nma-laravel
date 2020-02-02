@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'spb'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Pemilik SPB</h5>
                        <p class="card-category">Daftar Pemilik SPB</p>
                        <div class="text-right">
                            <a data-toggle="collapse" href="#collapseSpb" role="button"
                               aria-expanded="false" aria-controls="collapseSpb"
                               class="btn btn-sm btn-primary shadow-sm" id="tambah-spb">Tambah Pemilik SPB</a>
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
                        <div class="collapse" id="collapseSpb">
                            <div class="card card-body shadow-none">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="tanggal" class="col-md-6 col-sm-12">Tanggal pengambilan SPB
                                            <input type="text" autocomplete="off" required
                                                   class="form-control datepicker-dropdown" name="tanggal"
                                                   aria-describedby="helpId"
                                                   placeholder="Tanggal pengambilan"/>
                                        </label>
                                        <label for="range1" class="col-xs-6">Range Nomor SPB yang diambil
                                            <input type="number" required
                                                   class="form-control" name="range1" aria-describedby="helpId"
                                                   placeholder="Mulai angka"/>
                                        </label>
                                        <label for="range1" class="col-xs-6 ml-3">&nbsp;
                                            <input type="number" required
                                                   class="form-control" name="range2" aria-describedby="helpId"
                                                   placeholder="Sampai angka"/>
                                        </label>
                                        <label for="korlap" class="col-md-6 col-sm-12">Daftar Koordinator Lapangan
                                            <select class="form-control" name="korlap" id="" aria-describedby="helpId"
                                                    required>
                                                <option>--Pilih Koordinator--</option>
                                                @foreach($korlaps as $korlap)
                                                    <option value="{{$korlap->id}}">{{$korlap->nama_korlap}}</option>
                                                @endforeach
                                            </select>
                                            <small id="helpId" class="form-text text-muted">Klik untuk memilih
                                                koordinator lapangan</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered table" id="spb-table">
                                <thead>
                                <tr>
                                    <th style="width:10%">Aksi</th>
                                    <th style="width:8%">No</th>
                                    <th>Tanggal pengambilan</th>
                                    <th>Nama Pemilik SPB</th>
                                    <th>Range SPB</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataspb as $key => $spb)
                                    <tr>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="nc-icon nc-ruler-pencil"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form class="form-action" action="{{ route('spb.destroy', $spb) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item" id="btn-edit-spb"
                                                           data-route="{{ route('spb.edit', $spb) }}">{{ __('Edit') }}</a>
                                                        <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __("Apakah anda yakin menghapus SPB ini?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$spb->tanggal_pengambilan}}</td>
                                        <td>{{$spb->korlap->nama_korlap}}</td>
                                        <td>{{$spb->range_spb}}</td>
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
        $('#spb-table').dataTable();
        $('.datepicker-dropdown').datepicker({
            format: "yyyy-mm-dd",
            language: "id",
            mode: "datetimepicker",
            autoclose: true
        });
        let formUri = "";
        $(document).on('click', '#btn-edit-spb', function () {
            let route = $(this).data('route');
            formUri = $(this).parent().prop('action');
            console.log(formUri);
            $.ajax({
                url: route,
                dataType: "json",
                success: function (res) {
                    $('.collapse').addClass('show');
                    $('#tambah-spb').text('Simpan');
                    let range = res.range_spb.split('-');
                    $(this).data('mode', 'u');
                    $('[name="tanggal"]').val(res.tanggal_pengambilan);
                    $('[name="range1"]').val(range[0]);
                    $('[name="range2"]').val(range[1]);
                    $('[name="korlap"]').children().map(function (index, item) {
                        if ($(item).val() == res.master_korlap_id) {
                            $(item).prop('selected', true);
                        }
                    });
                },
                error: function (err) {
                    alert(err.statusText)
                }
            })
        })
        $('#tambah-spb').on('click', function () {
            if (!$('.collapse').hasClass('show')) {
                $(this).data('mode', 'i');
                $(this).text('Simpan');
            } else {
                $(this).text('Tambah Pemilik SPB');
            }
            if ($(this).text() !== 'Simpan') {
                let url = "";
                let range1 = $('[name="range1"]').val();
                let range2 = $('[name="range2"]').val();
                let data = {
                    tanggal: $('[name="tanggal"]').val(),
                    range: range1 + "-" + range2,
                    id: $('[name="korlap"]').val()
                }
                if ($(this).data('mode') === 'i') {
                    url = "{{route('spb.store')}}";
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
