<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>

<style>
    .Panel {
        position: relative;
        display: inline-block;
        width: 210mm;
        min-height: 148.5mm;
        margin: 16px;
        border-bottom: 1px solid #aaa;
        background: white;
        /* border-radius: 16px; */

        /* Important */
        overflow: visible;
    }


    /**
 * Corner Ribbon Component
 */
    .corner-ribbon {
        position: absolute;
        bottom: -3px;
        right: -3px;
        height: 5.5em;
        width: 5.5em;
        padding: 8px;

        .cr-inner {
            position: absolute;
            inset: 0;
            /* background: #47469B; */
            color: #47469B;
            border-radius: 16px 8px 16px 8px;
            clip-path: polygon(100% 0, 0 100%, 100% 100%);
        }

        .cr-text {
            display: block;
            font-weight: bold;
            font-size: 20px;
            line-height: 1.3;
            transform: rotate(313deg) translateY(2.1em) translateX(-1.8em);

            strong {
                display: block;
                font-weight: normal;
                text-transform: uppercase;
            }
        }

        &::before,
        &::after {
            content: '';
            position: absolute;
            /* background: #47469B; */
            z-index: -1;
        }

        &::before {
            top: calc(100% - 8px);
            left: 0;
            height: 8px;
            width: 3px;
            border-radius: 0 0 0 50%;
        }

        &::after {
            left: calc(100% - 8px);
            top: 0;
            width: 8px;
            height: 3px;
            border-radius: 0 50% 0 0;
        }
    }


    body {
        text-align: center;
        font-family: sans-serif;
    }

    .subpage {
        justify-content: center;
        margin: 90px;
    }

    th,
    td {
        padding: 5px;
        /* Atur padding pada sel-sel tabel */
    }
</style>

<body>
    <div class="Panel">
        <div class="corner-ribbon">
            <span class="cr-inner">
                <span class="cr-text"> Mitra</span>
            </span>

        </div>
        <div class="subpage">
            <table style="font-size: small; white-space: nowrap;  " width="100%">
                <tr>
                    <td rowspan="3" width="80%" align="left"><img src="/assets/login/img/empat.svg"
                            width="100" alt="">
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Tanggal</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;</td>
                    <td style="text-align: center;">
                        <?= date('d-F-Y', strtotime($pembelian->tgl)) ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>No Faktur</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;</td>
                    <td style="text-transform: uppercase;">
                        <?= $pembelian->no_nota ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Kpd Yth, Bpk/Ibu</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;</td>
                    <td>
                        <?= strtoupper($pembelian->suplier_akhir) ?>
                    </td>
                </tr>
            </table>
            <br>
            <table style="text-align: center; border-collapse: collapse;" width="100%" border="1">
                <thead>
                    <tr>
                        <th style="text-align: center">Produk</th>
                        <th style="text-align: right">Qty</th>
                        <th>Satuan</th>
                        <th style="text-align: right"> Harga</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $qty_total = 0;
                    @endphp
                    @foreach ($produk as $no => $p)
                        @php
                            $qty_total += $p->qty;
                        @endphp
                        <tr>
                            <td>{{ $p->nm_produk }}</td>
                            <td align="right">{{ number_format($p->qty, 0) }}</td>
                            <td>{{ $p->nm_satuan }}</td>
                            <td align="right">{{ number_format($p->h_satuan, 0) }}</td>
                            <td align="right">
                                {{ $p->id_produk == '7' ? number_format($p->h_satuan, 0) : number_format($p->qty == '0' ? '0' : $p->qty * $p->h_satuan, 0) }}
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                    @php
                        $row = 5 - $no;
                        if ($row < 0) {
                            $rows = 100;
                        } else {
                            $rows = $row * 25;
                        }
                    @endphp
                    <tr>
                        <td colspan="5" style="height: {{ $rows }}px;"></td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: right;">
                            <?= number_format($qty_total, 0) ?>
                        </th>
                        <th></th>
                        <th style="text-align: right;">
                            <?= number_format($pembelian->total_harga == '0' ? '0' : $pembelian->total_harga / $qty_total, 0) ?>
                        </th>
                        <th style="text-align: right;">
                            <?= number_format($pembelian->total_harga, 0) ?>
                        </th>
                    </tr>


                </tfoot>
            </table>

        </div>
    </div>

    <div class="Panel">
        <div class="corner-ribbon">
            <span class="cr-inner">
                <span class="cr-text">Kantor</span>
            </span>

        </div>
        <div class="subpage">



            <table style="font-size: small; white-space: nowrap;  " width="100%">
                <tr>
                    <td rowspan="3" align="left" width="80%"><img src="/assets/login/img/empat.svg"
                            width="100" alt="">
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Tanggal</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;</td>
                    <td style="text-align: center;">
                        <?= date('d-F-Y', strtotime($pembelian->tgl)) ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>No Faktur</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;</td>
                    <td style="text-transform: uppercase;">
                        <?= $pembelian->no_nota ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Kpd Yth, Bpk/Ibu</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;</td>
                    <td>
                        <?= strtoupper($pembelian->suplier_akhir) ?>
                    </td>
                </tr>
            </table>
            <br>
            <table style="text-align: center; border-collapse: collapse; padding: 5px" width="100%" border="1">
                <thead>
                    <tr>
                        <th style="text-align: center">Produk</th>
                        <th style="text-align: right">Qty</th>
                        <th>Satuan</th>
                        <th style="text-align: right"> Harga</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $qty_total = 0;
                    @endphp
                    @foreach ($produk as $no => $p)
                        @php
                            $qty_total += $p->qty;
                        @endphp
                        <tr>
                            <td>{{ $p->nm_produk }}</td>
                            <td align="right">{{ number_format($p->qty, 0) }}</td>
                            <td>{{ $p->nm_satuan }}</td>
                            <td align="right">{{ number_format($p->h_satuan, 0) }}</td>
                            <td align="right">
                                {{ $p->id_produk == '7' ? number_format($p->h_satuan, 0) : number_format($p->qty == '0' ? '0' : $p->qty * $p->h_satuan, 0) }}
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                    @php
                        $row = 5 - $no;
                        if ($row < 0) {
                            $rows = 100;
                        } else {
                            $rows = $row * 25;
                        }
                    @endphp
                    <tr>
                        <td colspan="5" style="height: {{ $rows }}px;">&nbsp;</td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: right;">
                            <?= number_format($qty_total, 0) ?>
                        </th>
                        <th></th>
                        <th style="text-align: right;">
                            <?= number_format($pembelian->total_harga == '0' ? '0' : $pembelian->total_harga / $qty_total, 0) ?>
                        </th>
                        <th style="text-align: right;">
                            <?= number_format($pembelian->total_harga, 0) ?>
                        </th>
                    </tr>


                </tfoot>
            </table>
            <br>
            @if (empty($bayar->ket))
            @else
                <table style="text-align: center; border-collapse: collapse;" width="100%" border="1">
                    <thead>
                        <tr>
                            <th style="text-align: center">Produk</th>
                            <th style="text-align: right">Qty</th>
                            <th>Satuan</th>
                            <th style="text-align: right">Harga</th>
                            <th style="text-align: right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $bayar->ket }}</td>
                            <td align="right">0</td>
                            <td></td>
                            <td align="right">{{ number_format($bayar->debit, 0) }}</td>
                            <td align="right">{{ number_format($bayar->debit, 0) }}</td>
                        </tr>

                    </tbody>
                    <tfoot>

                        <tr>
                            <th style="text-align: center;">Grand Total</th>
                            <th style="text-align: right;">
                                {{ number_format($qty_total) }}
                            </th>
                            <th></th>
                            <th style="text-align: right;">
                                {{ number_format(
                                    $pembelian->total_harga == '0' ? '0' : ($pembelian->total_harga + $bayar->debit) / $qty_total,
                                    0,
                                ) }}
                            </th>
                            <th style="text-align: right;">
                                {{ number_format($pembelian->total_harga + $bayar->debit, 0) }}
                            </th>
                        </tr>


                    </tfoot>

                </table>
            @endif


        </div>
    </div>

</body>

<script>
    window.print()
</script>

</html>
