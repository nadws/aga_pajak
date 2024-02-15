@php
    if ($nm_gudang == 'wipcetak') {
        $url = 'gudangBk.export_wip_cetak';
    } else {
        $url = 'gudangBk.export_buku_campur_bk';
    }

@endphp

<form action="{{ route($url) }}" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-3">
            <button class="btn btn-warning kembali">Kembali</button>
        </div>
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            @if ($nm_gudang == 'wipcetak')
                <x-theme.button modal="Y" idModal="importcetak" icon="fas fa-upload" addClass="float-end"
                    teks="Import" />
            @else
                <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" />
            @endif

        </div>

        {{-- <div class="col-lg-3">
            <table class="float-end">
                <td>Search :</td>
                <td><input type="text" id="pencarian" class="form-control float-end"></td>
            </table>
        </div> --}}
        <div class="col-lg-12 mt-2">

            <div class="table-container">
                <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                    <thead>
                        @php
                            $ttlRp = 0;
                            $pcs = 0;
                            $gr = 0;
                            foreach ($gudang as $g) {
                                $pcs += $g->pcs;
                                $gr += $g->gr;
                                $ttlRp += $g->rupiah * $g->gr;
                            }
                        @endphp
                        <tr>
                            <th class="dhead">#</th>
                            <th class="dhead">ID</th>
                            <th class="dhead">Buku</th>
                            <th class="dhead">Suplier Awal</th>
                            <th class="dhead">Date</th>
                            <th class="dhead">Grade</th>
                            <th class="dhead text-end">Pcs <br> {{ number_format($pcs, 0) }}</th>
                            <th class="dhead text-end">Gram <br> {{ number_format($gr, 0) }}</th>
                            @if ($presiden)
                                <th class="dhead">Rp/Gr</th>
                            @endif
                            <th class="dhead">Lot</th>
                            <th class="dhead">Keterangan / Nama Partai Herry</th>
                            <th class="dhead">Keterangan / Nama Partai Sinta</th>
                            @if ($presiden)
                                <th class="dhead text-end">Ttl Rp <br> {{ number_format($ttlRp, 0) }}</th>
                            @endif
                            <th class="dhead">Lok</th>
                            <th class="dhead text-center">
                                @if ($nm_gudang == 'produksi' || $nm_gudang == 'wip')
                                    <button type="submit" name="submit" value="export_produksi"
                                        class="badge bg-success"><i class="fas fa-file-excel"></i></button>
                                @else
                                    <button type="submit" name="submit" value="export" class="badge bg-success"><i
                                            class="fas fa-file-excel"></i></button>
                                @endif
                                <br>
                                {{-- <input type="checkbox" id="checkAll" name="" id=""> --}}
                                <input type="hidden" name="gudang" value="{{ $nm_gudang }}" id="">
                            </th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gudang as $no => $g)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $g->id_buku_campur }}</td>
                                <td>{{ $g->buku }}</td>
                                <td>{{ $g->suplier_awal }} </td>
                                <td>{{ $g->tgl }}</td>
                                <td>{{ $g->nm_grade }}</td>
                                <td class="text-end">{{ number_format($g->pcs, 0) }}</td>
                                <td class="text-end">{{ number_format($g->gr, 0) }}</td>
                                @if ($presiden)
                                    <td class="text-end">{{ number_format($g->rupiah, 0) }}</td>
                                @endif
                                <td>{{ $g->no_lot }}</td>
                                <td>{{ $g->ket }}</td>
                                <td>{{ $g->ket2 }}</td>
                                @if ($presiden)
                                    <td class="text-end">{{ number_format($g->rupiah * $g->gr, 0) }}</td>
                                @endif
                                <td>{{ $g->lok_tgl }}</td>


                                <td class="text-center">
                                    {{-- <input type="checkbox" class="checkbox-item"
                                    name="id_buku_campur[]" value="{{ $g->id_buku_campur }}"
                                    id=""> --}}
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
