@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'kwitansi'
])
@section('content')
    <div class="content">
        <form action="{{route('kwitansi.generate')}}" method="POST">
            @csrf
            <input type="hidden" name="timbangan_id">
            <input type="hidden" name="harga_id">
            <input type="hidden" name="spb_id">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header ">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Buat Kwitansi</h5>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button name="mode" value="manual" id="input-manual-button"
                                            class="btn btn-sm btn-danger" data-manual="false">Input Manual
                                    </button>
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
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="no_berkas"></label>
                                        <input type="text" name="no_berkas" id="no_berkas" class="form-control"
                                               placeholder="Nomor Berkas" aria-describedby="helpId" readonly
                                               value="{{$last_berkas->no_berkas??"0001/".date('d/m/Y')."/TBS"}}">
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="tgl_pembayaran"></label>
                                        <input type="text" name="tgl_pembayaran" id="tgl_pembayaran"
                                               class="form-control" placeholder="Tanggal Pembayaran"
                                               aria-describedby="helpId" readonly value="{{date('Y-m-d')}}">
                                        <small id="helpId" class="text-muted">Tanggal Pembayaran (Otomatis)</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="tgl_timbangan"></label>
                                        <input type="text" name="tgl_timbangan" id="tgl_timbangan"
                                               class="form-control datepicker-dropdown" placeholder="Tanggal Timbangan"
                                               aria-describedby="helpId" readonly value="" required>
                                        <small id="helpId" class="text-muted">Tanggal Masuk Timbangan (Otomatis)</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="no_tiket">Masukkan Nomor Tiket</label>
                                        <input required type="text" class="form-control" name="no_tiket" id="no_tiket"
                                               placeholder="Nomor Tiket" aria-label="Nomor Tiket"
                                               aria-describedby="ticketEmpty" autocomplete="off">
                                        <small id="no_tiket_desc" class="text-muted">(Centang kosong jika nomor tiket
                                            tidak ada)</small>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input tiketEmpty" type="checkbox">
                                                KOSONG
                                                <span class="form-check-sign">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
{{--                                NIK--}}
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="supir">Masukkan nama pengambil</label>
                                        <select class="custom-select" name="supir" id="supir">
                                        </select>
                                        <small id="helpId" class="text-muted"></small>
                                    </div>
                                </div>
{{--                                <div class="col-md-4 col-xs-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="nik">Masukkan NIK Pengambil</label>--}}
{{--                                        <input type="text" name="nik" id="nik" class="form-control"--}}
{{--                                               placeholder="NIK Pengambil"--}}
{{--                                               aria-describedby="helpId" autocomplete="off" required>--}}
{{--                                        <small id="helpId" class="text-muted"></small>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="no_spb">Masukkan Nomor SPB</label>
                                        <input type="number" name="no_spb" id="no_spb" class="form-control"
                                               placeholder="No. SPB"
                                               aria-describedby="helpId" autocomplete="off" required>
                                        <small id="no_spb_desc" class="text-muted"></small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="no_kendaraan"></label>
                                        <input type="text" name="no_kendaraan" id="no_kendaraan"
                                               class="form-control"
                                               placeholder="Nomor Kendaraan"
                                               aria-describedby="helpId" readonly required>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
{{--                                Supir--}}
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="nik"></label>
                                        <input type="text" name="nik" id="nik" class="form-control"
                                               placeholder="NIK Pengambil"
                                               aria-describedby="helpId" autocomplete="off" required>
                                        <small id="helpId" class="text-muted">Masukkan NIK Pengambil</small>
                                    </div>
                                </div>
{{--                                <div class="col-md-4 col-xs-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="supir"></label>--}}
{{--                                        <select class="custom-select" name="supir" id="supir">--}}
{{--                                        </select>--}}
{{--                                        <small id="helpId" class="text-muted">Masukkan nama pengambil</small>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="pemilik_spb"></label>
                                        <input type="text" name="pemilik_spb" id="pemilik_spb"
                                               class="form-control"
                                               placeholder="Pemilik SPB" readonly
                                               aria-describedby="helpId" required>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" id="btn-cetak-kwitansi"
                                            class="btn btn-primary">Cetak
                                        Kwitansi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="card ">
                        <div class="card-header ">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Detail Berat</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="first_weight">Berat Awal</label>
                                        <input type="number" name="first_weight" id="first_weight"
                                               class="form-control"
                                               placeholder="First Weight" aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="second_weight">Berat Kedua</label>
                                        <input type="number" name="second_weight" id="second_weight"
                                               class="form-control"
                                               placeholder="Second Weight"
                                               aria-describedby="helpId" readonly required>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="netto_weight">Netto</label>
                                        <input type="text" name="netto_weight" id="netto_weight"
                                               class="form-control"
                                               placeholder="Netto Weight"
                                               aria-describedby="helpId" readonly required>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="potongan_grading">Potongan Grading</label>
                                        <input type="number" name="potongan_grading" id="potongan_grading"
                                               class="form-control"
                                               placeholder="Potongan Grading"
                                               aria-describedby="helpId" readonly required>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="setelah_grading">Berat Total</label>
                                        <input type="text" name="setelah_grading" id="setelah_grading"
                                               class="form-control"
                                               placeholder="Setelah Grading"
                                               aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Total setelah Grading (Otomatis)</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="card ">
                        <div class="card-header ">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="card-title">Total yang harus dibayar</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="total_berat">Total Berat Keseluruhan</label>
                                        <input type="text" name="total_berat" id="total_berat"
                                               class="form-control"
                                               placeholder="Total Berat" aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="harga_satuan">Harga Satuan</label>
                                        <input type="text" name="harga_satuan" id="harga_satuan"
                                               class="form-control"
                                               placeholder="Harga Satuan"
                                               aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Harga satuan tanggal timbangan</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="total_pembayaran">Jumlah total yang harus dibayar</label>
                                        <input type="text" name="total_pembayaran" id="total_pembayaran"
                                               class="form-control"
                                               placeholder="Total Pembayaran"
                                               aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        let ids = [];
        $('input,select').map(function (i, item) {
            if (item.id !== "") {
                ids.push(item.id);
            }
        });
        console.log(ids)
        // $('#btn-cetak-kwitansi').on('click', function () {
        //     setTimeout(function () {
        //         window.location.reload();
        //     }, 1000)
        // })
        $(document).ready(function () {
            $('.datepicker-dropdown').datepicker({
                format: "yyyy-mm-dd",
                language: "id",
                autoclose:true,
            });

            function toggleInput(isManual) {
                $('#' + ids[2]).prop('readonly', isManual).attr('autocomplete', 'off').val('');
                $('#' + ids[6]).prop('readonly', isManual).attr('autocomplete', 'off').val('');
                $('#' + ids[9]).prop('readonly', isManual).attr('autocomplete', 'off').val('');
                $('#' + ids[10]).prop('readonly', isManual).attr('autocomplete', 'off').val('');
                $('#' + ids[12]).prop('readonly', isManual).attr('autocomplete', 'off').val('');
                $('#input-manual-button').data('manual', !isManual).text(isManual ? "Input Manual" : "Input Otomatis");
            }

            function countTotalWeight(thisVal, secondVal) {
                let resultVal = 0;
                if (thisVal !== "" && secondVal !== "") {
                    resultVal = parseFloat(thisVal) - parseFloat(secondVal);
                }
                $('#' + ids[11]).val(resultVal)
            }

            function getHarga(tanggal) {
                $.ajax({
                    url: "{{route('kwitansi.harga')}}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tanggal: tanggal
                    },
                    dataType: "json",
                    success: function ({status, harga}) {
                        let total_berat = 0;
                        let harga_satuan = 0;
                        let total_semua = 0;
                        if (harga.length === 0) {
                            $('#btn-cetak-kwitansi').prop('disabled', true);
                            swal({
                                title: "Gagal ambil harga satuan",
                                text: "Harga satuan belum di atur pada tanggal " + tanggal,
                                icon: "error",
                                buttons: {
                                    close: "Tutup",
                                    update: "Atur Harga"
                                }
                            }).then(val => {
                                if (val === 'update') {
                                    window.location.href = "{{route('harga.index')}}"
                                }
                            })
                        } else {
                            $('#btn-cetak-kwitansi').prop('disabled', false);
                            $('#' + ids[15]).val("Rp. " + harga[0].harga);
                            $('[name="harga_id"]').val(harga[0].id);
                            harga_satuan = harga[0].harga;
                            total_berat = parseFloat($('#' + ids[14]).val());
                            total_semua = harga_satuan * total_berat;
                            $('#' + ids[16]).val("Rp. " + total_semua);
                        }
                    }
                })
            }

            function countGradding(thisVal, valBefore) {
                let resultVal = 0;
                if (thisVal !== "") {
                    resultVal = parseFloat(valBefore) - parseFloat(thisVal);
                }
                $('#' + ids[13]).val(resultVal)
                $('#' + ids[14]).val(resultVal)
                let tanggal = $('#tgl_timbangan').val();
                if (tanggal === '') {
                    swal({
                        title: "Tanggal Timbangan Kosong",
                        text: "Kosong, periksa kembali tanggal timbangan",
                        icon: "error",
                        buttons: {
                            close: "Tutup",
                        }
                    })
                    return
                }
                getHarga(tanggal)

            }

            $('.tiketEmpty').on('change', function () {
                if ($(this).is(':checked')) {
                    $.ajax({
                        url: "{{route('kwitansi.kosong')}}",
                        method: "POST",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function ({status, kosong}) {
                            if (kosong.length === 0) {
                                $("#no_tiket").val("NULL-1");
                            } else {
                                let nullIncrement = kosong[0].no_ticket;
                                let sliceNull = nullIncrement.split('-');
                                let nextIncrementNull = parseInt(sliceNull[1]);
                                nextIncrementNull++;
                                $('#no_tiket').val("NULL-" + nextIncrementNull);
                            }
                        }
                    })
                    $('#no_tiket').prop('readonly', true);
                } else {
                    $('#no_tiket').prop('readonly', false).val("");
                }
            })
            //first weight
            $('#' + ids[9]).on('change', function () {
                countTotalWeight($(this).val(), $('#' + ids[10]).val());
            });
            //second weight
            $('#' + ids[10]).on('change', function () {
                countTotalWeight($('#' + ids[9]).val(), $(this).val());
            });
            $('#' + ids[12]).on('change', function () {
                countGradding($(this).val(), $('#' + ids[11]).val())
            })
            //@TODO:pemilik spb jika pemilik tidak ditemukan, berikan opsi untuk menambahkan pemilik spb sendiri
            $('#input-manual-button').on('click', function () {
                let isManual = $(this).data('manual');
                toggleInput(isManual);
            });
            $("#supir").on('change', function (e) {
                let id = e.target.value;
                $.ajax({
                    url: "{{route('kwitansi.petani')}}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        nik: true,
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function ({status, petani}) {
                        if (petani.length > 0) {
                            $('input#' + ids[7]).val(petani[0].nik)
                        } else {
                            $('input#' + ids[7]).val("-")
                        }
                    },
                })
            })
            $("#supir").select2({
                theme: 'bootstrap4',
                width: 'style',
                placeholder: 'Pilih Pengambil',
                minimumInputLength: 1,
                tags: true,
                ajax: {
                    url: "{{route('kwitansi.petani')}}",
                    method: "POST",
                    dataType: "json",
                    data: function (params) {
                        return {
                            petani: params.term
                        }
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.petani
                        }
                    },
                    cache: true
                }
            });
            $('#manualSelectHarga').on('change', function (e) {

                $('#hargaManual').val(this.value);
            })
            $(document).on('blur', 'input#' + ids[5], function () {
                let spb = $(this).val();
                let spb_id = $(this).val();
                if (!$('#' + ids[8]).prop('readonly')) {
                    $.ajax({
                        url: "{{route('spb.check')}}",
                        method: "POST",
                        dataType: "json",
                        data: {
                            spb: spb
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function ({status, spb}) {
                            if (spb.length > 0) {
                                $('#btn-cetak-kwitansi').prop('disabled', true);
                                $('#no_spb_desc').text("Nomor SPB sudah terdaftar, periksa kembali atau ganti nomor spb lain").addClass('text-danger');
                                $('#no_spb').addClass('bg-danger');
                            } else {
                                $('#btn-cetak-kwitansi').prop('disabled', false);
                            }
                        }
                    })
                    return;
                }
                if (spb.length !== 0) {
                    $.ajax({
                        url: "{{route('kwitansi.spb')}}",
                        method: "POST",
                        dataType: "json",
                        data: {
                            spb: spb
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function ({status, spb}) {
                            if (spb.length === 0) {
                                $('#btn-cetak-kwitansi').prop('disabled', true);
                                swal({
                                    title: "Pemilik SPB tidak ditemukan",
                                    text: "Pemilik SPB tidak ditemukan, periksa kembali nomor yang ada masukkan",
                                    icon: "error",
                                    buttons: {
                                        close: "Tutup",
                                        manual: "Input Manual"
                                    }
                                }).then(val => {
                                    if (val === 'manual') {
                                        $('#pemilik_spb').prop('readonly', false);
                                        $('#btn-cetak-kwitansi').prop('disabled', false);
                                        $('#no_spb').focus();
                                    }
                                })
                            } else {
                                $.ajax({
                                    url: "{{route('kwitansi.detail')}}",
                                    method: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        spb: spb_id
                                    },
                                    dataType: "json",
                                    success: function ({status, spb}) {
                                        if (spb.length > 0) {
                                            $('#btn-cetak-kwitansi').prop('disabled', true);
                                            $('#no_spb_desc').text("Nomor SPB sudah dibayar, periksa kembali atau ganti nomor spb lain").addClass('text-danger');
                                            $('#no_spb').addClass('bg-danger');
                                            swal({
                                                title: "Peringantan!!!",
                                                text: "Nomor SPB sudah dilakukan pembayaran pada tanggal " + spb[0].tanggal_pembayaran + " dengan nomor berkas " + spb[0].no_berkas,
                                                icon: "error",
                                                button: "Close"
                                            })
                                        }
                                    },
                                    error: function () {

                                    }
                                });
                                $('#' + ids[8]).val(spb[0].korlap.nama_korlap)
                                $('[name="spb_id"]').val(spb[0].id);
                                $('#btn-cetak-kwitansi').prop('disabled', false);
                            }
                        }
                    })
                }
            });
            $(document).on('focus', 'input#' + ids[3], function () {
                if ($(this).hasClass('bg-danger')) {
                    $(this).removeClass('bg-danger');
                    $('#no_tiket_desc').removeClass('text-danger').text("Masukkan Nomor Tiket");
                }
            });
            $(document).on('focus', 'input#' + ids[5], function () {
                if ($(this).hasClass('bg-danger')) {
                    $(this).removeClass('bg-danger');
                    $('#no_spb_desc').removeClass('text-danger').text("Masukkan Nomor SPB");
                }
            });
            $(document).on('blur', 'input#' + ids[3], function () {
                let ticket_number = $(this).val();
                let total_berat = 0;
                let harga_satuan = 0;
                let total_semua = 0;
                if (ticket_number.length > 4) {
                    $.ajax({
                        url: "{{route('kwitansi.tiket')}}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            no_ticket: ticket_number
                        },
                        dataType: "json",
                        success: function ({status, tickets}) {
                            if (tickets.length === 0) {
                                $('#btn-cetak-kwitansi').prop('disabled', true);
                                swal({
                                    title: "Nomor Tiket tidak ditemukan",
                                    text: "Tidak ada data untuk nomor tiket tersebut",
                                    icon: "warning",
                                    buttons: {
                                        close: "Tutup",
                                        manual: "Input Manual"
                                    }
                                }).then(function (val) {
                                    if (val === 'manual') {
                                        toggleInput(false);
                                        $('#btn-cetak-kwitansi').prop('disabled', false);
                                    }
                                })
                            } else if (tickets[0].status_pembayaran === "belum") {
                                $('#' + ids[2]).val(tickets[0].tanggal_masuk);
                                $('#' + ids[6]).val(tickets[0].no_kendaraan);
                                $('#' + ids[9]).val(tickets[0].first_weight);
                                $('#' + ids[10]).val(tickets[0].second_weight);
                                $('#' + ids[11]).val(tickets[0].netto_weight);
                                $('#' + ids[12]).val(tickets[0].potongan_gradding);
                                $('#' + ids[13]).val(tickets[0].setelah_gradding);
                                $('#' + ids[14]).val(tickets[0].setelah_gradding);
                                $('[name="timbangan_id"]').val(tickets[0].id);
                                total_berat = tickets[0].setelah_gradding;
                                $('#btn-cetak-kwitansi').prop('disabled', false);
                                $.ajax({
                                    url: "{{route('kwitansi.harga')}}",
                                    method: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        tanggal: tickets[0].tanggal_masuk
                                    },
                                    dataType: "json",
                                    success: function ({status, harga}) {
                                        if (harga.length === 0) {
                                            $('#btn-cetak-kwitansi').prop('disabled', true);
                                            swal({
                                                title: "Gagal ambil harga satuan",
                                                text: "Harga satuan belum di atur pada tanggal " + tickets[0].tanggal_masuk,
                                                icon: "error",
                                                buttons: {
                                                    close: "Tutup",
                                                    update: "Atur Harga"
                                                }
                                            }).then(val => {
                                                if (val === 'update') {
                                                    window.location.href = "{{route('harga.index')}}"
                                                }
                                            })
                                        } else {
                                            $('#btn-cetak-kwitansi').prop('disabled', false);
                                            $('#' + ids[15]).val("Rp. " + harga[0].harga);
                                            $('[name="harga_id"]').val(harga[0].id);
                                            harga_satuan = harga[0].harga;
                                            total_semua = harga_satuan * total_berat;
                                            $('#' + ids[16]).val("Rp. " + total_semua);
                                            $('#_ticket').val(tickets[0].no_ticket)
                                        }
                                    }
                                })
                            } else {
                                $('#btn-cetak-kwitansi').prop('disabled', true);
                                swal({
                                    title: "Nomor tiket sudah dibayar sebelumnya",
                                    text: "SPB sudah dibayar, klik tombol detail untuk melihat detailnya",
                                    icon: "error",
                                    buttons: {ok: "OK", detail: "Details"}
                                }).then(function (value) {
                                    $('#no_tiket_desc').text("Nomor Tiket sudah dibayar, periksa kembali atau ganti nomor tiket lain").addClass('text-danger');
                                    $('#no_tiket').addClass('bg-danger');
                                    if (value === 'detail') {
                                        $.ajax({
                                            url: "{{route('kwitansi.timbangan')}}",
                                            method: "POST",
                                            dataType: "json",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                _ticket: $('#no_tiket').val()
                                            },
                                            success: function (res) {
                                                swal({
                                                    text: "Pembayaran dengan nomor tiket " + res.detail[0].no_ticket + " sudah dibayar pada " + res.detail[0].updated_at
                                                });
                                            }
                                        })

                                    } else {
                                        null;
                                    }
                                })
                            }
                        }
                    })
                }
            })
        })
    </script>
@endpush
