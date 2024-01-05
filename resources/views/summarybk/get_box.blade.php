<table class="table table-bordered">
    <thead>
        <tr>
            <th class="dhead" rowspan="2">Ket / nama partai</th>
            <th class="dhead" rowspan="2">No Lot</th>
            <th class="dhead" rowspan="2">No Box</th>
            <th class="dhead" rowspan="2">Pengawas</th>
            <th class="dhead text-center" colspan="2">BK</th>
            <th class="dhead text-center" colspan="6">Cabut</th>
            <th class="dhead text-center" colspan="2">Sisa</th>
        </tr>
        <tr>
            <th class="dhead text-center">Pcs</th>
            <th class="dhead text-center">Gr</th>

            <th class="dhead text-center">Pcs Awal</th>
            <th class="dhead text-center">Gr Awal</th>
            <th class="dhead text-center">Pcs Akhir</th>
            <th class="dhead text-center">Gr Akhir</th>
            <th class="dhead text-center">Susut</th>
            <th class="dhead text-center">Rp</th>

            <th class="dhead text-center">Pcs</th>
            <th class="dhead text-center">Gr</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bk as $g)
            @php
                $response = Http::get("https://sarang.ptagafood.com/api/apibk/cabut_perbox?no_box=$g->no_box");
                $cbt = $response['data']['cabut'] ?? null;
                $c = json_decode(json_encode($cbt));
            @endphp
            <tr>
                <td class="text-center">{{ $g->nm_partai ?? '-' }}</td>
                <td class="text-center">{{ $g->no_lot ?? '-' }}</td>
                <td class="text-center">{{ $g->no_box ?? '-' }}</td>
                <td class="text-center">{{ $g->name ?? '-' }}</td>
                <td class="text-center">{{ $g->pcs_awal ?? 0 }}</td>
                <td class="text-center">{{ $g->gr_awal ?? 0 }}</td>
                <td class="text-center">{{ $c->pcs_awal ?? 0 }}</td>
                <td class="text-center">{{ $c->gr_awal ?? 0 }}</td>
                <td class="text-center">{{ $c->pcs_akhir ?? 0 }}</td>
                <td class="text-center">{{ $c->gr_akhir ?? 0 }}</td>
                @php
                    $pcs_awal_bk = $g->pcs_awal ?? 0;
                    $gr_awal_bk = $g->gr_awal ?? 0;

                    $pcs_awal_cbt = $c->pcs_awal ?? 0;
                    $gr_awal_cbt = $c->gr_awal ?? 0;
                    $pcs_akhir_cbt = $c->pcs_akhir ?? 0;
                    $gr_akhir_cbt = $c->gr_akhir ?? 0;
                @endphp
                <td class="text-center">
                    {{ $gr_akhir_cbt == 0 ? '0' : number_format((1 - $gr_akhir_cbt / $gr_awal_cbt) * 100, 1) }} %
                </td>
                <td class="text-center">
                    {{ $gr_akhir_cbt == 0 ? number_format($c->rupiah ?? 0, 0) : number_format($c->ttl_rp ?? 0, 0) }}
                </td>
                <td class="text-center">
                    {{ number_format($pcs_awal_bk - $pcs_awal_cbt) }}
                </td>
                <td class="text-center">
                    {{ number_format($gr_awal_bk - $gr_awal_cbt) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
