<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->name }}</title>
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        h1, h2, h3, h4, h5, h6, p, span, div {
            font-family: DejaVu Sans;
            font-size: 10px;
            font-weight: normal;
        }

        th, td {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        .panel {
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .panel-default {
            border-color: #ffffff;
        }

        .panel-body {
            padding: 15px;
        }

        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 0px;
            border-spacing: 0;
            border-collapse: collapse;
            background-color: transparent;

        }

        thead {
            text-align: left;
            display: table-header-group;
            vertical-align: middle;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }
    </style>
    @if($invoice->duplicate_header)
        <style>
            @page {
                margin-top: 140px;
            }

            header {
                top: -100px;
                position: fixed;
            }
        </style>
    @endif
</head>
<body>
<header>
    <div style="position:absolute; left:0pt; width:250pt;">
        <img class="img-rounded" height="{{ $invoice->logo_height }}" src="{{ $invoice->logo }}">
        <br/>
        {{ $invoice->business_details->get('name') }}
        <br/>
        {{ $invoice->business_details->get('city') }} {{ $invoice->business_details->get('country') }}
    </div>
    <div style="margin-left:300pt;">

        @if ($invoice->number)
            <b>Nomor Berkas #: </b> {{ $invoice->number }}
        @endif
        <br/>
        <b>Nomor Tiket #: </b> {{$invoice->customer_details->get('no_ticket')}}
    </div>
    <br/>
</header>
<main>

    <h4><b>Bulan Buku: </b> {{ $invoice->date->translatedFormat('F Y') }}<br/></h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            @if($invoice->shouldDisplayImageColumn())
                <th>Image</th>
            @endif
            <th>Nama</th>
            <th>Nomor SPB</th>
            <th>No Kendaraan</th>
            <th>Pemilik SPB</th>
            <th>Total Berat</th>
            <th>Harga Satuan</th>
            <th>Total Bayar</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoice->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if($invoice->shouldDisplayImageColumn())
                    <td>@if(!is_null($item->get('imageUrl'))) <img src="{{ url($item->get('imageUrl')) }}"/>@endif</td>
                @endif
                <td>{{ $invoice->customer_details->get('name') }}<br>
                    NIK : {{ $invoice->customer_details->get('nik') }}</td>
                <td>{{ $item->get('id') }}<br>
                    (<small>{{$item->get('name')->tgl_timbangan->translatedFormat('jS F Y')}}</small>)
                </td>
                <td>
                    {{ $invoice->customer_details->get('id') }}
                </td>
                <td>{{ $item->get('name')->pemilik }}</td>
                <td>{{ $item->get('ammount') }} KG</td>
                <td>{{ $invoice->formatCurrency()->symbol }}. {{ $item->get('price') }}
                    <br>(<small>{{$item->get('name')->tgl_timbangan->translatedFormat('jS F Y')}}</small>)
                </td>
                <td>{{ $invoice->formatCurrency()->symbol }}. {{ $item->get('totalPrice') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="clear:both; position:relative;">
        @if($invoice->notes)
            <div style="position:absolute; left:0pt; width:250pt;">

            </div>
        @endif
        <div style="margin-left: 300pt;margin-top: 20px">
            {{--            <h4>Total:</h4>--}}
            <table class="table table-bordered">
                <tbody>

                <tr>
                    <td><b>TOTAL</b></td>
                    <td><b>{{ $invoice->formatCurrency()->symbol }}. {{ $invoice->totalPriceFormatted() }}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="" style="margin-top: 30px">
        <div style="clear:both; position:relative;">
            <span style="color:#b21f2d">{{ $invoice->notes }}</span><span style="position: absolute;float: right"><b>Tanggal Dibayar: </b> {{ $invoice->date->translatedFormat('l jS F Y') }}<br/></span>
            <div style="position:absolute; left:30pt; width:250pt;margin-top: 15px">
                <h4>Dibayarkan Oleh :</h4>
                <div class="">
                    <div class="">
                        <br><br><br><br>
                        <b><u>Erni</u></b>
                    </div>
                </div>
            </div>
            <div style="margin-left: 350pt;">
                <h4>Diterima oleh :</h4>
                <div class="" style="text-align: left">
                    <div class="">
                        <br><br><br><br>
                        <b><u>{{ $invoice->customer_details->get('name') }}</u></b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($invoice->footnote)
        <div>
            {{ $invoice->footnote }}
        </div>
    @endif
</main>
<br>
<div style="border-top: dashed 2px;"></div>
<br>
<div style="margin-bottom: 30px">
    <div style="position:absolute; left:0pt; width:250pt;">
        <img class="img-rounded" height="{{ $invoice->logo_height }}" src="{{ $invoice->logo }}">
        <br/>
        {{ $invoice->business_details->get('name') }}
        <br/>
        {{ $invoice->business_details->get('city') }} {{ $invoice->business_details->get('country') }}
    </div>
    <div style="margin-left:300pt;">

        @if ($invoice->number)
            <b>Nomor Berkas #: </b> {{ $invoice->number }}
        @endif
        <br/>
        <b>Nomor Tiket #: </b> {{$invoice->customer_details->get('no_ticket')}}
    </div>
    <div style="z-index:-1;position: relative;float: right;margin-right: 10px; transform: rotate(-10deg);border:3px #ddd solid;border-radius: 4px"><h1 style="font-size: 400%;color: #ddd;padding-left:50px;padding-right:50px"><b>COPY</b></h1></div>
    <br/>
</div>
<main>

    <h4><b>Bulan Buku: </b> {{ $invoice->date->translatedFormat('F Y') }}<br/></h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            @if($invoice->shouldDisplayImageColumn())
                <th>Image</th>
            @endif
            <th>Nama</th>
            <th>Nomor SPB</th>
            <th>No Kendaraan</th>
            <th>Pemilik SPB</th>
            <th>Total Berat</th>
            <th>Harga Satuan</th>
            <th>Total Bayar</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoice->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if($invoice->shouldDisplayImageColumn())
                    <td>@if(!is_null($item->get('imageUrl'))) <img src="{{ url($item->get('imageUrl')) }}"/>@endif</td>
                @endif
                <td>{{ $invoice->customer_details->get('name') }}<br>
                    NIK : {{ $invoice->customer_details->get('nik') }}</td>
                <td>{{ $item->get('id') }}<br>
                    (<small>{{$item->get('name')->tgl_timbangan->translatedFormat('jS F Y')}}</small>)
                </td>
                <td>
                    {{ $invoice->customer_details->get('id') }}
                </td>
                <td>{{ $item->get('name')->pemilik }}</td>
                <td>{{ $item->get('ammount') }} KG</td>
                <td>{{ $invoice->formatCurrency()->symbol }}. {{ $item->get('price') }}
                    <br>(<small>{{$item->get('name')->tgl_timbangan->translatedFormat('jS F Y')}}</small>)
                </td>
                <td>{{ $invoice->formatCurrency()->symbol }}. {{ $item->get('totalPrice') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="clear:both; position:relative;">
        @if($invoice->notes)
            <div style="position:absolute; left:0pt; width:250pt;">

            </div>
        @endif
        <div style="margin-left: 300pt;margin-top: 20px">
            {{--            <h4>Total:</h4>--}}
            <table class="table table-bordered">
                <tbody>

                <tr>
                    <td><b>TOTAL</b></td>
                    <td><b>{{ $invoice->formatCurrency()->symbol }}. {{ $invoice->totalPriceFormatted() }}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="" style="margin-top: 30px">
        <div style="clear:both; position:relative;">
            <span style="color:#b21f2d">{{ $invoice->notes }}</span><span style="position: absolute;float: right"><b>Tanggal Dibayar: </b> {{ $invoice->date->translatedFormat('l jS F Y') }}<br/></span>
            <div style="position:absolute; left:30pt; width:250pt;margin-top: 15px">
                <h4>Dibayarkan Oleh :</h4>
                <div class="">
                    <div class="">
                        <br><br><br><br>
                        <b><u>Erni</u></b>
                    </div>
                </div>
            </div>
            <div style="margin-left: 350pt;">
                <h4>Diterima oleh :</h4>
                <div class="" style="text-align: left">
                    <div class="">
                        <br><br><br><br>
                        <b><u>{{ $invoice->customer_details->get('name') }}</u></b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($invoice->footnote)
        <div>
            {{ $invoice->footnote }}
        </div>
    @endif
</main>
<!-- Page count -->
<script type="text/php">
            if (isset($pdf) && $GLOBALS['with_pagination'] && $PAGE_COUNT > 1) {
                $pageText = "{PAGE_NUM} of {PAGE_COUNT}";
                $pdf->page_text(($pdf->get_width()/2) - (strlen($pageText) / 2), $pdf->get_height()-20, $pageText, $fontMetrics->get_font("DejaVu Sans, Arial, Helvetica, sans-serif", "normal"), 7, array(0,0,0));
            }



</script>
</body>
</html>
