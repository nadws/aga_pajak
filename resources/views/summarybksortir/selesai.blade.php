@csrf
<div class="row">
    <div class="col-lg-3">

    </div>
    <div class="col-lg-6">

    </div>
    <div class="col-lg-3">
        <table class="float-end">
            <td>Search :</td>
            <td><input type="text" id="pencarian" class="form-control float-end"></td>
        </table>
    </div>

    <div class="col-lg-12 mt-2">
        <div class="table-container table-responsive">
            <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                <thead>
                    <tr>
                        <th class="dhead" rowspan="2">#</th>
                        <th class="dhead" rowspan="2">Ket / nama partai</th>
                        <th class="dhead" rowspan="2">Grade / No Lot</th>
                        <th class="dhead text-center" colspan="3">Wip</th>
                        <th class="dhead text-center" colspan="2">BK</th>
                        <th class="dhead text-center" colspan="2">Susut Wip</th>
                        <th class="text-white text-center bg-danger" colspan="2">Wip Sisa</th>
                    </tr>
                    <tr>
                        <th class="dhead text-center">Pcs</th>
                        <th class="dhead text-center">Gr</th>
                        <th class="dhead text-center">Ttl Rp</th>
                        <th class="dhead text-center">Pcs</th>
                        <th class="dhead text-center">Gr</th>
                        <th class="dhead text-center">Gr</th>
                        <th class="dhead text-center">Sst(%)</th>
                        <th class="text-white text-center bg-danger">Pcs</th>
                        <th class="text-white text-center bg-danger">Gr</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ttl_rp = 0;
                    @endphp
                    @foreach ($gudang as $no => $g)
                        @php
                            $ket = $g->ket2;
                            $response = Http::get("$linkApi/bk_sum_all", ['nm_partai' => $ket, 'kategori' => $kategori]);
                            $b = $response->object();

                            $wipPcs = $g->pcs ?? 0;
                            $wipGr = $g->gr ?? 0;
                            $wipTllrp = $g->total_rp ?? 0;
                            $bkPcs = $b->pcs_awal ?? 0;
                            $bkGr = $b->gr_awal ?? 0;
                            $gr_susut = $g->gr_susut ?? 0;

                            $ttl_rp += $wipTllrp / ($wipGr - $gr_susut);
                        @endphp
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $g->ket2 }}</td>
                            <td class="text-center fw-bold">{{ $g->nm_grade }}</td>
                            <td class="text-end fw-bold">{{ number_format($wipPcs, 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($wipGr, 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($wipTllrp, 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($bkPcs, 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($bkGr, 0) }}</td>

                            <td class="fw-bold text-end">
                                {{ number_format($g->gr_susut ?? 0, 0) }}
                            </td>
                            <td class="text-end fw-bold">{{ number_format((1 - $bkGr / $wipGr) * 100, 0) }}
                            </td>
                            @php

                                $WipSisaPcs = $wipPcs - $bkPcs;
                                $WipSisaGr = $wipGr - $bkGr - $gr_susut;
                            @endphp
                            <td class="text-end fw-bold text-danger">{{ number_format($WipSisaPcs, 0) }}
                            </td>
                            <td class="text-end fw-bold text-danger">{{ number_format($WipSisaGr, 0) }}
                            </td>





                        </tr>
                    @endforeach

                    <input type="hidden" class="rp_satuan" name="rp_satuan" value="{{ $ttl_rp }}">
                    <input type="hidden" name="ket_sebelum" value="{{ $g->ket2 }}">

                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <label for="">Pindah Partai &nbsp;</label>
        <input type="checkbox" name="pindah" id="" class="pindah" value="Y">
    </div>
    <div class="col-lg-8">

    </div>

    <div class="col-lg-4 form-pindah" style="display: none">
        <h5>Pindah Partai</h5>
    </div>
    <div class="col-lg-12 form-pindah" style="display: none">
        <hr>
    </div>
    <div class="col-lg-2 form-pindah" style="display: none">
        <label for="">Partai</label>
        <input type="text" class="form-control" name="partai">
    </div>
    <div class="col-lg-2 form-pindah" style="display: none">
        <label for="">Grade</label>
        <input type="text" class="form-control" name="grade">
    </div>
    <div class="col-lg-2 form-pindah" style="display: none">
        <label for="">Pcs</label>
        <input type="text" class="form-control" name="pcs">
    </div>
    <div class="col-lg-2 form-pindah" style="display: none">
        <label for="">Gr</label>
        <input type="text" class="form-control gram" name="gr">
    </div>
    <div class="col-lg-2 form-pindah" style="display: none">
        <label class="text-end" for="">Rp/Gr</label>
        <input type="text" class="form-control text-end" value="{{ number_format($ttl_rp, 0) }}" readonly>
    </div>
    <div class="col-lg-2 form-pindah" style="display: none">
        <label class="text-end" for="">Ttl Rp</label>
        <input type="text" class="form-control ttlrp" readonly>
    </div>
</div>
<input type="hidden" class="lokasi" name="lokasi" value="{{ $lokasi }}">
<input type="hidden" class="nm_gudang" value="{{ $nm_gudang }}">
