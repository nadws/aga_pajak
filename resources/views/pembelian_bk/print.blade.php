<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body class="py-5">
    <style>
        .bdr {
            border-radius: 16px;
            overflow: hidden;
        }

        .rounded-tfoot th:first-child {
            border-bottom-left-radius: 16px;
        }

        .rounded-tfoot th:last-child {
            border-bottom-right-radius: 16px;
        }

        .dotted-line {
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .dotted-line::after {
            content: "..........................................................................................................";
            letter-spacing: 6px;
            font-size: 30px;
            color: #9cbfdb;
            display: inline-block;
            vertical-align: 3px;
            padding-left: 10px;
        }
    </style>
    @for ($i = 0; $i < 2; $i++)
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col">
                    <img src="/assets/login/img/empat.svg" width="100" alt="">
                </div>
                <div class="col">
                    <h5>PT Agrika Gatya Arum</h5>
                    <p>Alamat disini</p>
                </div>
            </div>
            <hr class="text-black" style="border: 1px solid black">
            <div class="row">
                <div class="col text-start">
                    <h6>Tagihan Kepada</h6>
                    <h4>{{ strtoupper($pembelian->suplier_akhir) }}</h4>
                </div>
                <div class="col"></div>
                <div class="col">
                    <p class="text-end"><span class="fw-bold">#{{ $pembelian->no_nota }}</span></p>
                    <p class="text-end"><span class="fw-bold">{{ tanggal($pembelian->tgl) }}</span></p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="tbl-container bdr">
                        <table class="table ">
                            <thead class=" text-white" style="background-color: #716F6C">
                                <tr>
                                    <th class="text-start">Nama Produk</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Total</th>
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
                                        <td align="left">{{ $p->nm_produk }}</td>
                                        <td>{{ $p->ket }}</td>
                                        <td align="right">{{ number_format($p->qty, 0) }}</td>
                                        <td align="center">{{ $p->nm_satuan }}</td>
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
                                        $rows = $row * 15;
                                    }
                                @endphp

                            </tbody>
                            <tfoot class="rounded-tfoot" style="background-color: #ffd61f;">
                                <tr>
                                    <th style="text-align: center;">Total</th>
                                    <th></th>
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
            </div>
            <p class="text-start">Catatan : <br> Jangan Dibanting</p>
            @if ($i == 0)
                <h6 class="dotted-line">Gunting disini</h6>
            @endif
        </div>
    @endfor






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>
