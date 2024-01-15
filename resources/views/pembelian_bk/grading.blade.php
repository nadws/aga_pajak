<div class="row">
    <div class="col-lg-6">
        <h6>No Lot :{{ $invoice->no_lot }}</h6>
    </div>
    <div class="col-lg-6">
        <h6 class="text-end">No Nota :{{ $invoice->no_nota }}</h6>
    </div>
    <div class="col-lg-12">
        <div class="table-responsive">
            <h6>Grading</h6>
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="dhead" style="text-align: center;white-space: nowrap;">Tanggal</th>
                        <th class="dhead" style="text-align: center;white-space: nowrap;">No BK Campur</th>
                        <th class="dhead" style="text-align: right; white-space: nowrap;">Gr Basah</th>
                        <th class="dhead" style="text-align: right; white-space: nowrap;">Pcs Awal</th>
                        <th class="dhead" style="text-align: right; white-space: nowrap;">Gr Gdg Kering</th>
                        <th class="dhead" style="text-align: right; white-space: nowrap;">Susut Gram Beli / Kering</th>
                        <th class="dhead" style="text-align: right; white-space: nowrap;">Gr Kering / Basah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">{{ date('d-m-Y', strtotime($grading->tgl)) }}</td>
                        <td align="center">{{ $grading->no_campur }}</td>
                        <td style="text-align: right">{{ number_format($grading->gr_basah, 0) }}</td>
                        <td style="text-align: right">{{ number_format($grading->pcs_awal, 0) }}</td>
                        <td style="text-align: right">{{ number_format($grading->gr_kering, 0) }}</td>
                        <td style="text-align: right">
                            {{ number_format((1 - $grading->qty / $grading->gr_kering) * 100, 0) }}
                            %</td>
                        <td style="text-align: right">
                            {{ number_format((1 - $grading->gr_kering / $grading->gr_basah) * 100, 0) }}
                            %</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-12">
        <hr>
    </div>
    <div class="col-lg-8">
        <h6>Buku Campur</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="dhead">Grade</th>
                    <th class="text-end dhead">Pcs</th>
                    <th class="text-end dhead">Gr</th>
                    <th class="text-end dhead">Rupiah/gram</th>
                    <th class="text-end dhead">Total Rupiah</th>
                </tr>
            </thead>
            <tbody style="background-color: #F2F7FF">
                @php
                    $pcs = 0;
                    $gr = 0;
                    $rupiah = 0;
                    $rupiah_gr = 0;
                @endphp
                @foreach ($buku_campur as $g)
                    <tr>
                        <td>
                            {{ $g->nm_grade }}
                        </td>
                        <td class="text-end">{{ $g->pcs }}</td>
                        <td class="text-end">{{ $g->gr }}</td>
                        <td class="text-end">{{ number_format($g->rupiah, 0) }}</td>
                        <td class="text-end">{{ number_format($g->rupiah * $g->gr, 0) }}</td>
                    </tr>
                    @php
                        $pcs += $g->pcs;
                        $gr += $g->gr;
                        $rupiah += $g->rupiah;
                        $rupiah_gr += $g->rupiah * $g->gr;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th class="text-end">{{ number_format($pcs, 0) }}</th>
                    <th class="text-end">{{ number_format($gr, 0) }}</th>
                    <th class="text-end">{{ number_format($rupiah_gr / $gr, 2) }}</th>
                    <th class="text-end">{{ number_format($rupiah_gr, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
