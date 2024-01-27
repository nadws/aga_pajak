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
        <a href="{{ route('summarybk.export_show_box') }}" class="btn btn-sm btn-success float-end">Export</a>
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
