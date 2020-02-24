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
        <img class="img-rounded" height="{{ $invoice->logo_height }}" src="data:image/png;base64,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">
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
        <img class="img-rounded" height="{{ $invoice->logo_height }}" src="data:image/png;base64,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">
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
    <div
        style="z-index:-1;position: relative;float: right;margin-right: 10px; transform: rotate(-10deg);border:3px #ddd solid;border-radius: 4px">
        <h1 style="font-size: 400%;color: #ddd;padding-left:50px;padding-right:50px"><b>COPY</b></h1></div>
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
