@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'timbangan'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Data timbangan</h5>
                        <p class="card-category">Data timbangan PTP</p>
                        <div class="text-right">
                            <a data-toggle="collapse" href="#collapseTimbangan" role="button"
                               aria-expanded="false" aria-controls="collapseTimbangan"
                               class="btn btn-sm btn-primary shadow-sm" id="import-excel">Import data Excel
                                Timbangan</a>
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
                        <div class="collapse" id="collapseTimbangan">
                            <div class="card card-body shadow-none">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="tanggal" class="col-md-6 col-sm-12">Tanggal Import Data Excel
                                            <input type="text" autocomplete="off" required
                                                   class="form-control datepicker-dropdown" name="tanggal"
                                                   aria-describedby="helpId"
                                                   placeholder="Tanggal pengambilan"/>
                                        </label>
                                        <label for="filepond" class="col-xs-12 col-md-6">Upload File Excel
                                            <input type="file" name="file" class="filepond">
                                        </label>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered table" id="spb-table">
                                <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th style="width:10%">Nomor Tiket</th>
                                    <th style="width:8%">Tanggal Masuk</th>
                                    <th>Nomor Kendaraan</th>
                                    <th>Pelanggan</th>
                                    <th>Tandan</th>
                                    <th>1st Weight</th>
                                    <th>2nd Weight</th>
                                    <th>Netto Weight</th>
                                    <th>Potongan Gradding</th>
                                    <th>Setelah Grading</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($timbangans as $key => $timbangan)
                                    <tr>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="nc-icon nc-ruler-pencil"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form class="form-action"
                                                          action="{{ route('spb.destroy', $timbangan) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="dropdown-item" id="btn-edit-spb"
                                                           data-route="{{ route('spb.edit', $timbangan) }}">{{ __('Edit') }}</a>
                                                        <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __("Apakah anda yakin menghapus SPB ini?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$timbangan->no_ticket}}</td>
                                        <td>{{$timbangan->tanggal_masuk}}</td>
                                        <td>{{$timbangan->no_kendaraan}}</td>
                                        <td>{{$timbangan->pelanggan}}</td>
                                        <td>{{$timbangan->tandan}}</td>
                                        <td>{{$timbangan->first_weight}}</td>
                                        <td>{{$timbangan->second_weight}}</td>
                                        <td>{{$timbangan->netto_weight}}</td>
                                        <td>{{$timbangan->potongan_gradding}}</td>
                                        <td>{{$timbangan->setelah_gradding}}</td>
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
        FilePond.setOptions({
            server: {
                url: '/filepond/api',
                process: '/process',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
        FilePond.parse(document.body);
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
