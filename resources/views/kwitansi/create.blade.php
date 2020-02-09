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
                                               class="form-control" placeholder="Tanggal Timbangan"
                                               aria-describedby="helpId" readonly value="">
                                        <small id="helpId" class="text-muted">Tanggal Masuk Timbangan (Otomatis)</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="no_tiket"></label>
                                        <input type="text" name="no_tiket" id="no_tiket" class="form-control"
                                               placeholder="Nomor Tiket"
                                               aria-describedby="helpId" autocomplete="off" required>
                                        <small id="no_tiket_desc" class="text-muted">Masukkan Nomor Tiket</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="supir"></label>
                                        <input type="text" name="supir" id="supir" class="form-control"
                                               placeholder="Nama Sopir"
                                               aria-describedby="helpId" autocomplete="off" required>
                                        <small id="helpId" class="text-muted">Masukkan nama pengambil
                                            (Sopir)</small>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="no_spb"></label>
                                        <input type="number" name="no_spb" id="no_spb" class="form-control"
                                               placeholder="No. SPB"
                                               aria-describedby="helpId" autocomplete="off" required>
                                        <small id="no_spb_desc" class="text-muted">Masukkan Nomor SPB</small>
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
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="pemilik_spb"></label>
                                        <input type="text" name="pemilik_spb" id="pemilik_spb"
                                               class="form-control"
                                               placeholder="Pemilik SPB" readonly
                                               aria-describedby="helpId">
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button formtarget="_blank" type="submit" id="btn-cetak-kwitansi"
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
                                        <input type="text" name="first_weight" id="first_weight"
                                               class="form-control"
                                               placeholder="First Weight" aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="second_weight">Berat Kedua</label>
                                        <input type="text" name="second_weight" id="second_weight"
                                               class="form-control"
                                               placeholder="Second Weight"
                                               aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="netto_weight">Netto</label>
                                        <input type="text" name="netto_weight" id="netto_weight"
                                               class="form-control"
                                               placeholder="Netto Weight"
                                               aria-describedby="helpId" readonly>
                                        <small id="helpId" class="text-muted">Otomatis</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="potongan_grading">Potongan Grading</label>
                                        <input type="text" name="potongan_grading" id="potongan_grading"
                                               class="form-control"
                                               placeholder="Potongan Grading"
                                               aria-describedby="helpId" readonly>
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
        $('input').map(function (i, item) {
            if (item.id !== "") {
                ids.push(item.id);
            }
        });
        $('#btn-cetak-kwitansi').on('click', function () {
            setTimeout(function () {
                window.location.reload();
            }, 1000)
        })
        $(document).ready(function () {
            $(document).on('blur', 'input#' + ids[5], function () {
                let spb = $(this).val();
                let spb_id = $(this).val();
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
                                        update: "Lihat daftar Pemilik SPB"
                                    }
                                }).then(val => {
                                    if (val === 'update') {
                                        window.location.href = "{{route('spb.index')}}"
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
                                })
                                $('#' + ids[7]).val(spb[0].korlap.nama_korlap)
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
                                    button: "OK"
                                })
                            } else if (tickets[0].status_pembayaran === "belum") {
                                $('#' + ids[2]).val(tickets[0].tanggal_masuk);
                                $('#' + ids[6]).val(tickets[0].no_kendaraan);
                                $('#' + ids[8]).val(tickets[0].first_weight + " KG");
                                $('#' + ids[9]).val(tickets[0].second_weight + " KG");
                                $('#' + ids[10]).val(tickets[0].netto_weight + " KG");
                                $('#' + ids[11]).val(tickets[0].potongan_gradding + " KG");
                                $('#' + ids[12]).val(tickets[0].setelah_gradding + " KG");
                                $('#' + ids[13]).val(tickets[0].setelah_gradding + " KG");
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
                                        if (harga === "null") {
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
                                            $('#' + ids[14]).val("Rp. " + harga.harga);
                                            $('[name="harga_id"]').val(harga.id);
                                            harga_satuan = harga.harga;
                                            total_semua = harga_satuan * total_berat;
                                            $('#' + ids[15]).val("Rp. " + total_semua);
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
                                                    text: "Pembayaran dengan nomor tiket " + res.detail[0].no_ticket + " sudah dibayar pada tanggal sekian"
                                                });
                                                console.log(res)
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
