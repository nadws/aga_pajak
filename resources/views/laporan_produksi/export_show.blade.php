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
            <table class="table table-bordered" id="tblAldi2" border="1">
                <thead>
                    <tr>
                        <th class="dhead text-center">No</th>
                        <th class="dhead text-center">Partai</th>
                        <th class="dhead text-center">No Box</th>
                        <th class="dhead text-center">Pengawas</th>
                        <th class="dhead text-center">Nama anak</th>
                        <th class="dhead text-center">Kelas</th>
                        <th class="dhead text-center">Tanggal</th>
                        <th class="dhead text-end">Pcs Awal</th>
                        <th class="dhead text-end">Gr Awal</th>
                        <th class="dhead text-end">Eot</th>
                        <th class="dhead text-end">Flx</th>
                        <th class="dhead text-end">Pcs Akhir</th>
                        <th class="dhead text-end">Gr Akhir</th>
                        <th class="dhead text-end">Susut</th>
                        <th class="dhead text-end">Cost Cbt</th>
                        <th class="dhead text-end">Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bk as $no => $g)
                        <tr>
                            <td class=" text-center">{{ $no + 1 }}</td>
                            <td class=" text-center">{{ $g->nm_partai }}</td>
                            <td class=" text-center">{{ $g->no_box }}</td>
                            <td class=" text-center">{{ $g->pengawas }}</td>
                            <td class=" text-center">{{ $g->nama_anak }}</td>
                            <td class=" text-center">{{ $g->kelas }}</td>
                            <td class=" text-center">{{ tanggal($g->tgl_terima) }}</td>
                            <td class=" text-end">{{ $g->pcs_awal }}</td>
                            <td class=" text-end">{{ $g->gr_awal }}</td>
                            <td class=" text-end">{{ $g->eot }}</td>
                            <td class=" text-end">{{ $g->gr_flx ?? 0 }}</td>
                            <td class=" text-end">{{ $g->pcs_akhir }}</td>
                            <td class=" text-end">{{ $g->gr_akhir }}</td>
                            <td class=" text-end {{ $g->susut > 23 ? 'text-danger' : '' }}">
                                {{ round($g->susut, 1) }}%</td>
                            <td class=" text-end">{{ $g->ttl_rp }}</td>
                            <td class=" text-center">{{ $g->kategori }}</td>


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
