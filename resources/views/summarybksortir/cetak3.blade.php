<div class="row">


    <style>
        .tdhide {
            display: none;
            overflow: hidden;
        }
    </style>
    <div class="col-lg-12 mt-2">

        <table class="table table-hover table-bordered " width="100%">
            <thead>
                <tr>
                    <th class="dhead" rowspan="2">No</th>
                    <th class="dhead" rowspan="2">No Box</th>
                    <th class="dhead" rowspan="2">Tipe</th>
                    <th class="dhead" rowspan="2">Grade</th>
                    <th class="dhead" rowspan="2">Partai</th>
                    <th class="dhead text-center" colspan="3">BK</th>
                    <th class="dhead text-center" colspan="3">BK Timbang ulang</th>
                    <th class="dhead text-center" colspan="3">Susut Bk</th>

                    <th class="dhead text-center" colspan="11">Cetak</th>
                    <th class="text-white text-center bg-danger" colspan="2">Bk sisa pgws</th>
                    <th class="dhead text-end" rowspan="2">cost bk</th>
                    <th class="dhead text-end" rowspan="2">cost ctk</th>

                </tr>
                <tr>
                    <th class="dhead text-end">pcs</th>
                    <th class="dhead text-end">gr</th>
                    <th class="dhead text-end">ttl rp</th>

                    <th class="dhead text-end">pcs</th>
                    <th class="dhead text-end">gr</th>
                    <th class="dhead text-end">ttl rp</th>

                    <th class="dhead text-end">pcs</th>
                    <th class="dhead text-end">gr</th>
                    <th class="dhead text-end">sst%</th>

                    <th class="dhead text-end">pcs awal</th>
                    <th class="dhead text-end">gr awal</th>
                    <th class="dhead text-end">pcs tidak ctk</th>
                    <th class="dhead text-end">gr tidak ctk</th>
                    <th class="dhead text-end">pcs ctk awal </th>
                    <th class="dhead text-end">gr ctk awal</th>
                    <th class="dhead text-end">pcs cu </th>
                    <th class="dhead text-end">gr cu</th>
                    <th class="dhead text-end">pcs akhir </th>
                    <th class="dhead text-end">gr akhir</th>
                    <th class="dhead text-end">sst%</th>
                    <th class="tex-end text-white bg-danger">pcs</th>
                    <th class="tex-end text-white bg-danger">gr</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wip_cetak as $no => $g)
                    @php
                        $response = Http::get("https://sarang.ptagafood.com/api/apibk/cetak_detail?no_box=$g->no_box");
                        $c = $response->object();

                    @endphp
                    <tr>
                        <td>{{ $no + $wip_cetak->firstItem() }}</td>
                        <td>{{ $g->no_box }}</td>
                        <td>{{ $g->tipe }}</td>
                        <td>{{ $g->grade }}</td>
                        <td>{{ $g->partai_h }}</td>
                        <td class="text-end">{{ number_format($g->pcs_cabut, 0) }}</td>
                        <td class="text-end">{{ number_format($g->gr_cabut, 0) }}</td>
                        <td class="text-end">{{ number_format($g->ttl_rp + $g->cost_cabut, 0) }}</td>

                        <td class="text-end">{{ number_format($g->pcs_timbang_ulang, 0) }}</td>
                        <td class="text-end">{{ number_format($g->gr_timbang_ulang, 0) }}</td>
                        <td class="text-end">{{ number_format($g->ttl_rp + $g->cost_cabut, 0) }}</td>

                        <td class="text-end">{{ number_format($g->pcs_cabut - $g->pcs_timbang_ulang, 0) }}</td>
                        <td class="text-end">{{ number_format($g->gr_cabut - $g->gr_timbang_ulang, 0) }}</td>
                        <td class="text-end">
                            {{ number_format((1 - $g->gr_timbang_ulang / $g->gr_cabut) * 100, 1) }}%
                        </td>

                        <td class="text-end">{{ $c->pcs_awal ?? 0 }}</td>
                        <td class="text-end">{{ $c->gr_awal ?? 0 }}</td>
                        <td class="text-end">{{ $c->pcs_tidak_cetak ?? 0 }}</td>
                        <td class="text-end">{{ $c->gr_tidak_cetak ?? 0 }}</td>
                        <td class="text-end">{{ $c->pcs_awal_ctk ?? 0 }}</td>
                        <td class="text-end">{{ $c->gr_awal_ctk ?? 0 }}</td>
                        <td class="text-end">{{ $c->pcs_cu ?? 0 }}</td>
                        <td class="text-end">{{ $c->gr_cu ?? 0 }}</td>
                        <td class="text-end">{{ $c->pcs_akhir ?? 0 }}</td>
                        <td class="text-end">{{ $c->gr_akhir ?? 0 }}</td>
                        @php
                            $pcs_awal_pgws = $c->pcs_awal ?? 0;
                            $gr_awal_pgws = $c->gr_awal ?? 0;
                            $susut = empty($c->gr_akhir)
                                ? '0'
                                : (1 - ($c->gr_akhir + $c->gr_cu) / $c->gr_awal_ctk) * 100;
                        @endphp
                        <td class="text-end">{{ number_format($susut, 0) }}%</td>
                        <td class="text-end">{{ number_format($g->pcs_timbang_ulang - $pcs_awal_pgws) }}</td>
                        <td class="text-end">{{ number_format($g->gr_timbang_ulang - $gr_awal_pgws) }}</td>
                        <td class="text-end">{{ number_format($g->ttl_rp + $g->cost_cabut, 0) }}</td>
                        <td class="text-end">{{ number_format($c->rp_ctk ?? 0, 0) }}</td>
                    </tr>
                @endforeach

                {{-- @foreach ($cabut as $no => $c)
                        @php
                            $bk = \App\Models\GudangBkModel::getPartaicetak($c->nm_partai);
                            $gdng_ctk = DB::table('gudang_ctk')
                                ->where('no_box', $c->no_box)
                                ->where('selesai', 'selesai')
                                ->first();

                            if (empty($gdng_ctk->selesai)) {
                            } else {
                                continue;
                            }
                        @endphp
                        <tr>
                            <td>{{ $c->no_box }}</td>
                            <td>{{ $c->tipe }}</td>
                            <td>{{ $bk->nm_grade ?? ' ' }}</td>
                            <td>{{ $c->nm_partai }}</td>
                            <td align="right">{{ $c->pcs_akhir }}</td>
                            <td align="right">{{ $c->gr_akhir }}</td>
                            <td align="right">
                                {{ empty($bk->total_rp) ? 0 : number_format(($bk->total_rp / $bk->gr) * $c->gr_akhir, 0) }}
                            </td>
                            <td align="right">{{ number_format($gdng_ctk->pcs_timbang_ulang ?? 0) }}</td>
                            <td align="right">{{ number_format($gdng_ctk->gr_timbang_ulang ?? 0) }}</td>
                            <td align="right">
                                @php
                                    $pcs_timbang_ulang = $gdng_ctk->pcs_timbang_ulang ?? 0;
                                    $gr_timbng_ulang = $gdng_ctk->gr_timbang_ulang ?? 0;
                                @endphp
                                @if ($gr_timbng_ulang == 0)
                                    0
                                @else
                                    {{ empty($bk->total_rp) ? 0 : number_format(($bk->total_rp / $bk->gr) * $c->gr_akhir, 0) }}
                                @endif

                            </td>
                            <td class="text-end">{{ number_format($c->pcs_akhir - $pcs_timbang_ulang, 0) }}</td>
                            <td class="text-end">{{ number_format($c->gr_akhir - $gr_timbng_ulang, 0) }}</td>
                            <td class="text-end">
                                {{ number_format((1 - $gr_timbng_ulang / $c->gr_akhir) * 100, 0) }}%
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach --}}
            </tbody>
        </table>
        <div class="float-end mt-2">
            {!! $wip_cetak->links() !!}
        </div>

    </div>
</div>
