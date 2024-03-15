<form id="save_susut">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <hr>
        </div>
        <div class="col-lg-3">
            <h5>{{ $title }}</h5>
        </div>
        <div class="col-lg-9"></div>
        <div class="col-lg-3">
            <button class="btn btn-warning kembali">Kembali</button>
        </div>
        <div class="col-lg-6">

        </div>
        <div class="col-lg-3">
            <table class="float-end">
                <td>Search :</td>
                <td><input type="text" id="pencarian" class="form-control float-end"></td>
            </table>
        </div>
        <div class="col-lg-12">
            <button type="submit" class="btn btn-sm btn-primary float-end mt-2">Simpan Susut</button>
            <input type="hidden" class="form-control gudang" name="gudang" value="{{ $nm_gudang }}">
        </div>


        <div class="col-lg-12 mt-2">
            <div class="table-container table-responsive">
                <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                    <thead>
                        <tr>
                            <th class="dhead" rowspan="2">#</th>
                            <th class="dhead" rowspan="2">Ket / nama partai</th>
                            <th class="dhead" rowspan="2">Grade</th>


                            <th class="dhead text-center" colspan="3">BK</th>
                            <th class="dhead text-center" colspan="3">BK TIMBANG ULANG</th>
                            <th class="dhead text-center tdhide" colspan="3">Susut Wip - bk</th>
                            <th class="text-white text-center bg-danger tdhide" colspan="3">Wip Sisa</th>
                            <th class="dhead text-center" rowspan="2">Selesai</th>

                        </tr>
                        <tr>
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">Bk Cost</th>
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">Ttl Rp</th>

                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">sst(%)</th>

                            <th class="text-white text-center bg-danger tdhide">Pcs</th>
                            <th class="text-white text-center bg-danger tdhide">Gr</th>
                            <th class="text-white text-center bg-danger tdhide">Ttl Rp</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gudang as $no => $g)
                            @php
                                $ket = $g->partai_h;
                                $resSum = Cache::remember('datacetak' . $ket, now()->addMinutes(5), function () use (
                                    $ket,
                                    $linkApi,
                                ) {
                                    return Http::get("$linkApi/datacetak", ['nm_partai' => $ket])->object();
                                });
                                $b = $resSum;
                                $g->relatedModel = $b;

                                $bkPcs = $b->pcs_awal ?? 0;
                                $bkGr = $b->gr_awal ?? 0;
                                $rp_satuan = ($g->ttl_rp + $g->cost_cabut) / ($g->gr_cabut + $bkGr);
                            @endphp
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $g->partai_h }}</td>
                                <td class="text-center fw-bold">{{ $g->grade }}</td>
                                <td class="tdhide fw-bold text-end">{{ number_format($g->pcs_cabut, 0) }}</td>
                                <td class="tdhide fw-bold text-end">{{ number_format($g->gr_cabut, 0) }}</td>
                                <td class="tdhide fw-bold text-end">{{ number_format($g->ttl_rp + $g->cost_cabut, 0) }}
                                </td>
                                <td class="text-end fw-bold tdhide">{{ number_format($bkPcs, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($bkGr, 0) }}</td>
                                <td class="text-end fw-bold tdhide">0</td>
                                <td class="text-end fw-bold tdhide" width="120px">
                                    <input type="text" class="form-control" style="width: 100%; font-size: 13px"
                                        name="pcs_susut[]" value="{{ $g->pcs_susut ?? 0 }}"
                                        {{ $g->selesai_1 == 'Y' ? 'readonly' : '' }}>
                                </td>
                                <td class="fw-bold" width="120px">
                                    <input type="text" class="form-control" style="width: 100%; font-size: 13px"
                                        name="gr_susut[]" value="{{ $g->gr_susut ?? 0 }}"
                                        {{ $g->selesai_1 == 'Y' ? 'readonly' : '' }}>
                                    <input type="hidden" class="form-control" name="ket[]"
                                        value="{{ $g->partai_h }}">
                                    <input type="hidden" class="form-control" name="selesai[]"
                                        value="{{ $g->selesai_1 ?? 'T' }}">

                                </td>
                                <td class="text-end fw-bold">{{ number_format((1 - $bkGr / $g->gr_cabut) * 100, 0) }}%
                                </td>
                                @php
                                    $pcs_sisa = $g->pcs_cabut - $bkPcs - $g->pcs_susut;
                                    $gr_sisa = $g->gr_cabut - $bkGr - $g->gr_susut;
                                @endphp
                                <td class="text-end fw-bold tdhide">{{ number_format($pcs_sisa, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($gr_sisa, 0) }}</td>
                                <td class="text-end fw-bold tdhide">0</td>
                                <td class="text-center fw-bold">

                                    @if ($g->selesai_1 == 'Y')
                                        <a href="#" ket="{{ $g->partai_h }}" gudang="{{ $nm_gudang }}"
                                            class="btn btn-warning btn-sm cancel_susut">
                                            <i class="fas fa-undo"></i>
                                            Cancel
                                        </a>
                                    @else
                                        <a href="#" ket="{{ $g->partai_h }}" gudang="{{ $nm_gudang }}"
                                            class="btn btn-primary btn-sm selesai_susut">
                                            Selesai
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
