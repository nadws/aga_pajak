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
                            <th class="dhead" rowspan="2">Grade / No Lot</th>
                            <th class="dhead text-center" colspan="2">Wip</th>
                            <th class="dhead text-center" colspan="2">BK</th>
                            <th class="dhead text-center" colspan="3">Susut Wip</th>
                            <th class="text-white text-center bg-danger" colspan="2">Wip Sisa</th>
                            <th class="dhead text-center" rowspan="2">Selesai</th>
                        </tr>
                        <tr>
                            <th class="dhead text-center">Pcs</th>
                            <th class="dhead text-center">Gr</th>
                            <th class="dhead text-center">Pcs</th>
                            <th class="dhead text-center">Gr</th>
                            <th class="dhead text-center">Pcs</th>
                            <th class="dhead text-center">Gr</th>
                            <th class="dhead text-center">Sst(%)</th>
                            <th class="text-white text-center bg-danger">Pcs</th>
                            <th class="text-white text-center bg-danger">Gr</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gudang as $no => $g)
                            @php
                                $ket = $g->ket2;
                                $resSum = Cache::remember('bk_sum_all_' . $ket, now()->addHours(8), function () use ($ket, $linkApi) {
                                    return Http::get("$linkApi/bk_sum_all", ['nm_partai' => $ket])->object();
                                });
                                $b = $resSum;
                                $g->relatedModel = $b;

                                $wipPcs = $g->pcs ?? 0;
                                $wipGr = $g->gr ?? 0;
                                $wipTllrp = $g->total_rp ?? 0;
                                $bkPcs = $b->pcs_awal ?? 0;
                                $bkGr = $b->gr_awal ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $g->ket2 }}</td>
                                <td class="text-center fw-bold">{{ $g->nm_grade }}</td>
                                <td class="text-end fw-bold">{{ number_format($wipPcs, 0) }}</td>
                                <td class="text-end fw-bold">{{ number_format($wipGr, 0) }}</td>
                                <td class="text-end fw-bold">{{ number_format($bkPcs, 0) }}</td>
                                <td class="text-end fw-bold">{{ number_format($bkGr, 0) }}</td>
                                <td class="text-end fw-bold" width="120px">
                                    <input type="text" class="form-control" style="width: 100%; font-size: 13px"
                                        name="pcs_susut[]" value="{{ $g->pcs_susut ?? 0 }}"
                                        {{ $g->selesai == 'Y' ? 'readonly' : '' }}>
                                </td>

                                <td class="fw-bold" width="120px">
                                    <input type="text" class="form-control" style="width: 100%; font-size: 13px"
                                        name="gr_susut[]" value="{{ $g->gr_susut ?? 0 }}"
                                        {{ $g->selesai == 'Y' ? 'readonly' : '' }}>
                                    <input type="hidden" class="form-control" name="ket[]"
                                        value="{{ $g->ket2 }}">
                                    <input type="hidden" class="form-control" name="selesai[]"
                                        value="{{ $g->selesai ?? 'T' }}">

                                </td>
                                <td class="text-end fw-bold">{{ number_format((1 - $bkGr / $wipGr) * 100, 0) }}
                                </td>
                                @php
                                    $gr_susut = $g->gr_susut ?? 0;
                                    $WipSisaPcs = $wipPcs - $bkPcs;
                                    $WipSisaGr = $wipGr - $bkGr - $gr_susut;
                                @endphp
                                <td class="text-end fw-bold text-danger">{{ number_format($WipSisaPcs, 0) }}
                                </td>
                                <td class="text-end fw-bold text-danger">{{ number_format($WipSisaGr, 0) }}
                                </td>
                                <td class="text-center">
                                    @if ($g->selesai == 'Y')
                                        {{-- <a href="{{ route('summarybk.cancel_susut', ['ket' => $g->ket2]) }}"
                                            class="btn btn-warning btn-sm  "> <i class="fas fa-undo"></i> Cancel
                                        </a> --}}

                                        <a href="#" ket="{{ $g->ket2 }}" gudang="{{ $nm_gudang }}"
                                            class="btn btn-warning btn-sm cancel_susut">
                                            <i class="fas fa-undo"></i>
                                            Cancel
                                        </a>
                                    @else
                                        {{-- <a href="{{ route('summarybk.selesai_susut', ['ket' => $g->ket2]) }}"
                                            class="btn btn-primary btn-sm"> Selesai
                                        </a> --}}

                                        <a href="#" ket="{{ $g->ket2 }}" gudang="{{ $nm_gudang }}"
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
