@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'timbangan'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">Import data timbangan</h5>
                        <div class="row">
                            <label for="file-pond" class="col-md-12 col-sm-12">Upload Data Excel disini
                                <input type="file"
                                       accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                       class="filepond" name="filepond" id="file-pond">
                            </label>
                            <label for="tgl_import" class="col-md-6 col-sm-12">Pilih tanggal import data
                                <input type="text" name="tgl_import" id="tgl_import"
                                       class="form-control datepicker-dropdown" placeholder="Tanggal Import"
                                       aria-describedby="helpId">
                                <small id="helpId" class="text-muted">Tanggal Import data Timbangan</small>
                            </label>

                            <label for="select-customer" class="col-md-6 col-sm-12">Pilih Customer
                                <select class="form-control" name="select-customer" id="select-customer">
                                    <option>--Pilih customer--</option>
                                </select>
                                <button class="btn btn-primary shadow-sm mt-3 float-right" id="import-excel">Import data
                                    Excel
                                    Timbangan
                                </button>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Data timbangan</h5>
                        <p class="card-category">Data timbangan PTP</p>
                        <div class="text-right">
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
                            <div class="col-md-12 d-flex justify-content-between">
                                <div class="form-group">
                                    <label for="">Pilih Tanggal Timbangan</label>
                                    <input type="text" name="dates" id="dates" class="form-control"
                                           placeholder="Range Tanggal"
                                           aria-describedby="helpId">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered display responsive nowrap table" id="spb-table" width="100%">
                                <thead>
                                <tr>
                                    <th class="sorting_asc_disabled"></th>
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
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($timbangans as $key => $timbangan)
                                    <tr class="{{$timbangan->status_pembayaran === "belum"?"bg-warning":"bg-success"}}">
                                        <td class="text-center">
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
                                        <td>
                                            <form class="form-action"
                                                  action="{{ route('timbangan.destroy', $timbangan) }}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirm('{{ __("Apakah anda yakin menghapus SPB ini?") }}') ? this.parentElement.submit() : ''">
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
    <script src="{{asset('paper')}}/vendors/xlsx/xlsx.full.min.js"></script>
    <script>
        let dateInput = $('input[name="dates"]');
        dateInput.daterangepicker();
        dateInput.on('apply.daterangepicker', function (ev, picker) {
            let start = picker.startDate.format('YYYY-MM-DD');
            let end = picker.endDate.format('YYYY-MM-DD');
            window.location.href = "{{route('timbangan.index')}}?start=" + start + "&end=" + end
        });
        let dataWeWant = [];

        function getKeyByValue(object, value) {
            return Object.keys(object).filter(item => {
                let itemLowerCase = item.toLocaleLowerCase();
                let filterWhiteSpace = itemLowerCase.replace(/\s/g, '');
                return filterWhiteSpace === value
            });
        }

        function handleExcel(file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, {type: 'array'});
                let sheetFirst = workbook.SheetNames[0];
                let dataSheet = workbook.Sheets[sheetFirst];
                let sheetToJson = XLSX.utils.sheet_to_json(dataSheet, {raw: true});
                //delete header
                sheetToJson.splice(0, 1);
                const pattern = ["no.", "date/time", "vehicles", "customers", "tandan", "1st", "2nd", "netto", "pot.", "setelah"];
                let keyHeader = pattern.map(item => getKeyByValue(sheetToJson[0], item));
                //convert date
                let newJsonSheet = sheetToJson.map(item => {
                    item[keyHeader[1]] = convertDateExcel(item[keyHeader[1]]);
                    return item;
                });
                //delete footer total
                newJsonSheet.splice(sheetToJson.length - 1, 1);
                let customers = [];
                //convert to object json
                dataWeWant = newJsonSheet.map(item => {
                    customers.push(item[keyHeader[3]]);
                    return {
                        no_ticket: item[keyHeader[0]],
                        tanggal_masuk: item[keyHeader[1]],
                        no_kendaraan: item[keyHeader[2]],
                        pelanggan: item[keyHeader[3]],
                        tandan: item[keyHeader[4]],
                        first_weight: item[keyHeader[5]],
                        second_weight: item[keyHeader[6]],
                        netto_weight: item[keyHeader[7]],
                        potongan_gradding: item[keyHeader[8]],
                        setelah_gradding: item[keyHeader[9]]
                    }
                })
                let uniqueCustomers = customers.filter(distinct);
                let htmlCustomers = uniqueCustomers.map(item => {
                    return "<option value='" + item + "'>" + item + "</option>";
                });
                $('#select-customer').html("<option value=''>Pilih Customer</option>" + htmlCustomers.join(""));
            }
            reader.readAsArrayBuffer(file);
        }

        function distinct(value, index, self) {
            return self.indexOf(value) === index;
        }

        function convertDateExcel(dateNumber) {
            let utc_value = Math.floor(dateNumber - 25569) * 86400;
            let date_info = new Date(utc_value * 1000);
            let month = parseInt(date_info.getMonth()) + 1;
            return date_info.getFullYear() + "-" + month + "-" + date_info.getDate();
        }

        function importToServer(filterData) {
            if (filterData.length === 0) {
                alert("Tidak ada data yang perlu di import pada tanggal tersebut");
                return;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('timbangan.store')}}",
                method: "POST",
                data: {
                    payload: filterData
                },
                success: function (res) {
                    alert("Excel Berhasil di import");
                    window.location.reload();
                },
                error: function (err) {
                    if (err.responseJSON.exception === "Illuminate\\Database\\QueryException") {
                        alert("Tidak bisa mengimport, Data sudah ada");
                    }

                }
            })
        }

        function importExcel(tgl_import) {
            let selectedPelanggan = $('#select-customer').val();
            if (selectedPelanggan !== "") {
                const filterData = dataWeWant.filter(function (value) {
                    return value.pelanggan === selectedPelanggan;
                });
                $('#import-excel').text("Sedang Menyimpan...").addClass('disabled');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('timbangan.currentData')}}",
                    method: "POST",
                    data: {
                        date: tgl_import
                    },
                    dataType: 'json',
                    success: function ({status, timbangans}) {
                        //filter based on current data
                        timbangans.forEach(function (timbangan) {
                            let index = filterData.findIndex(function (item) {
                                return item.no_ticket === timbangan.no_ticket
                            });
                            if (index > -1) {
                                filterData.splice(index, 1)
                            }
                        })
                        // console.log(filterData);
                        importToServer(filterData)
                    },
                    error: function (err) {
                        if (err.responseJSON.exception === "Illuminate\\Database\\QueryException") {
                            alert("Tidak bisa mengimport, Data sudah ada");
                        }

                    }
                })
            } else {
                alert("Pilih Customer terlebih dahulu sebelum import");
            }
        }

        $('#import-excel').on('click', function () {
            let tgl_import = $('#tgl_import').val();
            if (tgl_import === '') {
                alert('Tanggal Import tidak boleh kosong');
                return true;
            }
            importExcel(tgl_import)
        });
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.setOptions({
            acceptedFileTypes: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
            server: {
                url: '/api',
                process: '/process',
                revert: '/process',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
        const pond = FilePond.create(document.querySelector('input#file-pond'));
        pond.on('processfilestart', function (file) {
            handleExcel(file.file);
        });
        $('#spb-table').dataTable({
            responsive: true
        });
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
