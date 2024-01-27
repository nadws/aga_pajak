@php
    $file = 'dataperbox.xls';
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=$file");
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <style>
                thead {
                    position: sticky;
                    top: 0;
                    background-color: #f1f1f1;
                    /* Warna latar belakang header yang tetap */
                    z-index: 1;
                }
            </style>
            <table class="table table-bordered" id="tblAldi2" border="1">
                <thead>
                    <tr>
                        <th class="dhead" rowspan="2">#</th>
                        <th class="dhead" rowspan="2">Ket / nama partai</th>
                        <th class="dhead" rowspan="2">Grade</th>
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
                    @foreach ($bk as $no => $g)
                        @php
                            $gr_eo_awal = $g->gr_eo_awal ?? 0;
                            $gr_eo_akhir = $g->gr_eo_akhir ?? 0;
                            $ttl_rp_eo = $g->ttl_rp_eo ?? 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no + 1 }}</td>
                            <td class="text-center">{{ $g->nm_partai ?? '-' }}</td>
                            <td class="text-center">{{ $g->tipe ?? '-' }}</td>
                            <td class="text-center">{{ $g->no_lot ?? '-' }}</td>
                            <td class="text-center">{{ $g->no_box ?? '-' }}</td>
                            <td class="text-center">{{ $g->name ?? '-' }}</td>
                            <td class="text-center">{{ $g->pcs_awal ?? 0 }}</td>
                            <td class="text-center">{{ $g->gr_awal ?? 0 }}</td>
                            <td class="text-center">{{ $g->pcs_awal ?? 0 }}</td>
                            <td class="text-center">{{ $g->gr_awal ?? 0 + $gr_eo_awal }}</td>
                            <td class="text-center">{{ $g->pcs_akhir ?? 0 }}</td>
                            <td class="text-center">{{ $g->gr_akhir ?? 0 + $gr_eo_akhir }}</td>
                            @php
                                $pcs_awal_bk = $g->pcs_awal ?? 0;
                                $gr_awal_bk = $g->gr_awal ?? 0;

                                $pcs_awal_cbt = $g->pcs_awal ?? 0;
                                $gr_awal_cbt = $g->gr_awal ?? 0;
                                $pcs_akhir_cbt = $g->pcs_akhir ?? 0;
                                $gr_akhir_cbt = $g->gr_akhir ?? 0;

                            @endphp
                            <td class="text-center">
                                {{ $gr_akhir_cbt + $gr_eo_akhir == 0 ? '0' : number_format((1 - ($gr_akhir_cbt + $gr_eo_akhir) / ($gr_awal_cbt + $gr_eo_awal)) * 100, 1) }}
                                %
                            </td>
                            <td class="text-center">
                                {{ number_format($g->ttl_rp ?? 0 + $ttl_rp_eo, 0) }}
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>
