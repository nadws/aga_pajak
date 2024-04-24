<div class="row">
    <div class="col-lg-1 mb-2">
        <label for="">Load Data</label>
        <select name="example" class="form-control select2 load-data" id="">
            @php
                $val = [5, 25, 50, 100, 'ALL'];
            @endphp
            <option value="">Pilih</option>
            @foreach ($val as $d)
                <option value="{{ $d }}">{{ $d }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Pencarian : </label>
            <input type="text" id="pencarianBox" name="example" class="form-control">
        </div>
    </div>
    <div class="col-lg-9">
        <a href="{{ route('gudangnew.export_show_box', ['nm_partai' => $nm_partai, 'limit' => 'ALL']) }}"
            class="btn btn-sm btn-success float-end">Export</a>
    </div>
</div>
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
        <table class="table table-bordered" id="tblAldi2">
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
                        <td class=" text-end">{{ number_format($g->pcs_awal, 0) }}</td>
                        <td class=" text-end">{{ number_format($g->gr_awal, 0) }}</td>
                        <td class=" text-end">{{ number_format($g->eot, 0) }}</td>
                        <td class=" text-end">{{ number_format($g->gr_flx ?? 0, 0) }}</td>
                        <td class=" text-end">{{ number_format($g->pcs_akhir, 0) }}</td>
                        <td class=" text-end">{{ number_format($g->gr_akhir, 0) }}</td>
                        <td class=" text-end {{ $g->susut > 23 ? 'text-danger' : '' }}">
                            {{ number_format($g->susut, 1) }}%</td>
                        <td class=" text-end">{{ number_format($g->ttl_rp, 0) }}</td>
                        <td class=" text-center">{{ $g->kategori }}</td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
