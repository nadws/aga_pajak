<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


<div class="row">
    <div class="col-4 col-lg-6 text-start">

        <p style="margin-bottom: 1px; font-size: 12px">Kpd Yth, Bpk/Ibu</p>
        <h6 style="font-size: 12px">{{ strtoupper($pembelian->suplier_akhir) }}</h6>
    </div>
    <div class="col-8 col-lg-6 text-end">
        <table class="float-end" style="font-size: 13px;">
            <tbody>
                <tr>
                    <td align="left">
                        No Nota
                    </td>
                    <td width="10">:</td>
                    <td width="130" class="text-end">#{{ $pembelian->no_nota }}</td>
                </tr>
                <tr>
                    <td align="left">

                        Tanggal
                    </td>
                    <td width="10">:</td>
                    <td width="130" class="text-end">{{ tanggal($pembelian->tgl) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>
<div class="row mt-4">
    <div class="col">
        <div class="tbl-container bdr">

            <table class="table">
                <thead class=" text-white" style="background-color: #309fee">
                    <tr>
                        <th class="text-start">Grade</th>
                        <th>ket</th>
                        <th class="text-end" style="padding-right: 18px">Gr</th>
                        <th class="text-end" style="padding-right: 18px">Rp</th>
                        <th class="text-end">Total Rp</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $qty_total = 0;
                    @endphp
                    @foreach ($produk as $no => $p)
                        @php
                            $qty_total += $p->qty;
                            $ket = $p->ket;
                            $ket_with_br = strlen($ket) > 50 ? wordwrap($ket, 50, "<br>\n") : $ket;
                        @endphp
                        <tr class="align-middle">
                            <td align="left">{{ $p->nm_produk }}</td>
                            <td>{{ $p->ket }}</td>
                            <td align="right">{{ number_format($p->qty, 0) }}</td>
                            <td align="right">{{ number_format($p->h_satuan, 0) }}</td>
                            <td align="right">
                                {{ $p->id_produk == '7' ? number_format($p->h_satuan, 0) : number_format($p->qty == '0' ? '0' : $p->qty * $p->h_satuan, 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="rounded-tfoot" style="background-color: #D3D3D3;border-top: 2px solid white;">
                    <tr>
                        <th style="text-align: start;">Total</th>
                        <th></th>
                        <th style="text-align: right;">
                            <?= number_format($qty_total, 0) ?>
                        </th>
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
