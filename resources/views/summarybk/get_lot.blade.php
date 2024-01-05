@foreach ($lot as $no2 => $g)
    @php
        $response = Http::get("https://sarang.ptagafood.com/api/apibk/sarang?nm_partai=$g->ket&no_lot=$g->no_lot");
        $bk = $response['data']['bk_cabut'] ?? null;
        $b = json_decode(json_encode($bk));

        $cbt = $response['data']['cabut'] ?? null;
        $c = json_decode(json_encode($cbt));
    @endphp

    <tr>
        <td>
        </td>
        <td></td>
        <td class="text-center">
            <a href="#" data-bs-toggle="modal" no_lot="{{ $g->no_lot }}" nm_partai="{{ $g->ket }}"
                data-bs-target="#load_bk_cabut" class="float-end show_box">{{ $g->no_lot }} <i
                    class="fas fa-search text-primary "></i></a>
        </td>
        <td class="text-end">{{ number_format($g->pcs, 0) }}</td>
        <td class="text-end">{{ number_format($g->gr, 0) }}</td>
        <td class="text-end">{{ number_format($b->pcs_awal ?? 0, 0) }}</td>
        <td class="text-end">{{ number_format($b->gr_awal ?? 0, 0) }}</td>

        <td class="text-end">{{ number_format($c->pcs_awal ?? 0, 0) }}</td>
        <td class="text-end">{{ number_format($c->gr_awal ?? 0, 0) }}</td>
        <td class="text-end">{{ number_format($c->pcs_akhir ?? 0, 0) }}</td>
        <td class="text-end">{{ number_format($c->gr_akhir ?? 0, 0) }}</td>
        <td class="text-end">{{ number_format($c->susut ?? 0, 0) }}</td>
        <td class="text-end">{{ number_format($c->ttl_rp ?? 0, 0) }}</td>
        @php
            $pcs_awal_bk = $b->pcs_awal ?? 0;
            $gr_awal_bk = $b->gr_awal ?? 0;

            $pcs_awal_cbt = $c->pcs_awal ?? 0;
            $gr_awal_cbt = $c->gr_awal ?? 0;
        @endphp
        <td class="text-end">{{ number_format($pcs_awal_bk - $pcs_awal_cbt) }}</td>
        <td class="text-end">{{ number_format($gr_awal_bk - $gr_awal_cbt) }}</td>
    </tr>
@endforeach
