<table>
    <thead>
        <tr>
            <th width="39px">#</th>
            <th width="110px">Tanggal</th>
            <th width="177px">Suplier Awal</th>
            <th width="102px">Nota BK</th>
            <th width="102px">Nota Lot</th>
            <th width="184px">Suplier Akhir</th>
            <th width="151px">Keterangan</th>
            <th width="125px">Gr Beli</th>
            <th width="156px">Total Nota Bk</th>
            <th width="151px">Gr Basah</th>
            <th width="114px">Pcs Awal</th>
            <th width="151px">Gr Kering</th>
            <th width="80px">Susut</th>
            <th width="100px">No Buku Campur</th>
            <th width="80px">TGL Grade</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pembelian as $no => $p)
            <tr>
                <td>{{ $no + 1 }}</td>
                <td>{{ $p->tgl }}</td>
                <td>{{ strtoupper($p->nm_suplier) }}</td>
                <td>{{ $p->no_nota }}</td>
                <td>{{ $p->no_lot }}</td>
                <td>{{ strtoupper($p->suplier_akhir) }}</td>
                <td></td>
                <td>{{ $p->gr_beli }}</td>
                <td>{{ $p->total_harga }}</td>
                <td>{{ $p->gr_basah }}</td>
                <td>{{ $p->pcs_awal }}</td>
                <td>{{ $p->gr_kering }}</td>
                <td align="right">
                    {{ empty($p->gr_kering) ? '0' : number_format((1 - $p->gr_beli / $p->gr_kering) * -100, 0) }}
                    %</td>
                <td align="right">{{ $p->no_campur }}</td>
                <td>{{ $p->tgl_grading }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
