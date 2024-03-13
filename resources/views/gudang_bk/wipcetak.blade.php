@php
    if ($nm_gudang == 'wipcetak') {
        $url = 'gudangBk.export_wip_cetak';
    } else {
        $url = 'gudangBk.export_buku_campur_bk';
    }

@endphp



<div class="row">
    <div class="col-lg-3">
        <h5>Wip Cetak</h5>
    </div>
    <div class="col-lg-9"></div>
    <div class="col-lg-3">

        <button class="btn btn-warning kembali">Kembali</button>
    </div>
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <x-theme.button modal="Y" idModal="bkcetakawal" icon="fas fa-plus" addClass="float-end"
            teks="Tambah Bk Cetak" />
        @if ($nm_gudang == 'wipcetak')
            <x-theme.button modal="Y" idModal="importcetak" icon="fas fa-upload" addClass="float-end"
                teks="Import" />
        @else
            <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end" teks="Import" />
        @endif
        <form action="{{ route($url) }}" method="post">
            @csrf
            <button type="submit" name="submit" value="export" class="btn float-end me-2  btn-success"><i
                    class="fas fa-file-excel"></i> Export
            </button>
        </form>

    </div>
    <div class="col-lg-9"></div>
    <div class="col-lg-3">
        <table class="float-end">
            <td>Search :</td>
            <td><input type="text" id="pencarian" class="form-control float-end"></td>
        </table>
    </div>
    <div class="col-lg-12 mt-2">

        <div class="table-container">
            <form id="save_pindah_gudang">
                <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                    <thead>
                        <tr>
                            <th class="dhead">#</th>
                            <th class="dhead">Partai H</th>
                            <th class="dhead">No Box</th>
                            <th class="dhead">Tipe</th>
                            <th class="dhead">Grade</th>
                            <th class="dhead">Pcs sdh cabut</th>
                            <th class="dhead">Gr sdh cabut</th>
                            <th class="dhead">Cost Bk</th>
                            <th class="dhead">Cost Cabut</th>
                            <th class="dhead">Pcs timbang ulang</th>
                            <th class="dhead">Gr timbang ulang</th>
                            <th class="dhead">Selesai</th>
                            <th class="dhead">Gudang</th>
                            <th class="dhead"> <button type="submit" class="badge bg-success">masuk bk
                                    grading</button></th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowNumber = 1;
                        @endphp
                        @foreach ($wip_cetak as $c)
                            @php
                                $bk = \App\Models\GudangBkModel::getPartaicetak($c->partai_h);

                                $total_rp = $bk->total_rp ?? 0;
                                $gr = $bk->gr ?? 0;
                                $gr_susut = $bk->gr_susut ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $rowNumber }}</td>
                                <td>{{ $c->partai_h }}</td>
                                <td>{{ $c->no_box }}</td>
                                <td>{{ $c->tipe }}</td>
                                <td>{{ $c->grade }}</td>
                                <td align="right">{{ $c->pcs_cabut }}</td>
                                <td align="right">{{ $c->gr_cabut }}</td>
                                <td align="right">
                                    {{ $total_rp == 0 ? '0' : number_format(($total_rp / ($gr - $gr_susut)) * $c->gr_cabut, 0) }}
                                </td>
                                <td align="right">{{ number_format($c->cost_cabut) }}</td>
                                <td align="right">{{ $c->pcs_timbang_ulang }}</td>
                                <td align="right">{{ $c->gr_timbang_ulang }}</td>
                                <td align="right">{{ $c->selesai }}</td>
                                <td>{{ $c->gudang }}</td>

                                <td>
                                    <input type="checkbox" class="nota-checkbox" name="gudang"
                                        value="{{ $c->id_gudang_ctk }}" {{ $c->gudang == 'sortir' ? 'checked' : '' }}>
                                </td>
                            </tr>
                            @php
                                $rowNumber++;
                            @endphp
                        @endforeach
                        @foreach ($cabut as $no => $c)
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
                                <td>{{ $rowNumber }}</td>
                                <td>{{ $c->nm_partai }}</td>
                                <td>{{ $c->no_box }}</td>
                                <td>{{ $c->tipe }}</td>
                                <td>{{ $bk->nm_grade }}</td>
                                <td align="right">{{ $c->pcs_akhir }}</td>
                                <td align="right">{{ $c->gr_akhir }}</td>
                                <td align="right">{{ number_format(($bk->total_rp / $bk->gr) * $c->gr_akhir, 0) }}
                                </td>
                                <td align="right">{{ number_format($c->ttl_rp) }}</td>
                                <td align="right">{{ number_format($gdng_ctk->pcs_timbang_ulang ?? 0) }}</td>
                                <td align="right">{{ number_format($gdng_ctk->gr_timbang_ulang ?? 0) }}</td>
                                <td align="right">proses</td>

                                <td></td>
                            </tr>
                            @php
                                $rowNumber++;
                            @endphp
                        @endforeach


                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
