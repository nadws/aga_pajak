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

    <style>
        .tdhide {
            display: none;
            overflow: hidden;
        }
    </style>
    <div class="col-lg-12 mt-2">
        <div class="table-container table-responsive">
            <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                <thead>
                    <tr>
                        <th class="dhead" rowspan="2">#</th>
                        <th class="dhead" rowspan="2">Ket / nama partai</th>
                        <th class="dhead" rowspan="2">Grade
                            <br>
                            <center>

                                <a href="#" class="show_td">
                                    <i class="fas fa-chevron-circle-right text-white "></i>
                                </a>
                                <a href="#" class="hide_td tdhide">
                                    <i class="fas fa-chevron-circle-left text-white "></i>
                                </a>
                            </center>
                        </th>

                        @if ($nm_gudang == 'summary')
                            <th class="dhead text-center tdhide" colspan="3">BK</th>
                            <th class="dhead text-center tdhide" colspan="3">BK TIMBANG ULANG</th>
                        @else
                            <th class="dhead text-center tdhide" colspan="2">Wip</th>
                            <th class="dhead text-center tdhide" colspan="2">BK</th>
                        @endif



                        <th class="dhead text-center tdhide" colspan="3">Susut Wip - bk</th>
                        @if ($nm_gudang == 'summary')
                            <th class="text-white text-center bg-danger tdhide" colspan="3">Wip Sisa</th>
                        @else
                            <th class="text-white text-center bg-danger tdhide" colspan="2">Wip Sisa</th>
                        @endif

                        <th class="dhead text-center" rowspan="2">Selesai</th>
                        <th class="dhead text-center" colspan="7">Cetak</th>
                        <th class="bg-danger text-white text-center" colspan="2">Bk Sisa Pgws</th>
                        <th class="dhead" rowspan="2">Cost Cetak</th>
                    </tr>
                    <tr>
                        @if ($nm_gudang == 'summary')
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">Bk Cost</th>
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">Ttl Rp</th>
                        @else
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                        @endif
                        <th class="dhead text-center tdhide">Pcs</th>
                        <th class="dhead text-center tdhide">Gr</th>
                        <th class="dhead text-center tdhide">sst(%)</th>
                        @if ($nm_gudang == 'summary')
                            <th class="text-white text-center bg-danger tdhide">Pcs</th>
                            <th class="text-white text-center bg-danger tdhide">Gr</th>
                            <th class="text-white text-center bg-danger tdhide">Ttl Rp</th>
                        @else
                            <th class="text-white text-center bg-danger tdhide">Pcs</th>
                            <th class="text-white text-center bg-danger tdhide">Gr</th>
                        @endif
                        <th class="dhead text-center">Pcs Awal</th>
                        <th class="dhead text-center">Gr Awal</th>
                        <th class="dhead text-center">Pcs Cu</th>
                        <th class="dhead text-center">Gr Cu</th>
                        <th class="dhead text-center">Pcs Akhir</th>
                        <th class="dhead text-center">Gr Akhir</th>
                        <th class="dhead text-center">Susut</th>

                        <th class="text-white bg-danger text-center">Pcs</th>
                        <th class="text-white bg-danger text-center">Gr</th>
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
                            $rp_satuan = ($g->ttl_rp + $g->cost_cabut) / ($g->gr_cabut - $g->gr_susut);
                        @endphp
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $g->partai_h }}</td>
                            <td class="text-center fw-bold">{{ $g->grade }}</td>
                            <td class="tdhide fw-bold text-end">{{ number_format($g->pcs_cabut, 0) }}</td>
                            <td class="tdhide fw-bold text-end">{{ number_format($g->gr_cabut, 0) }}</td>
                            <td class="tdhide fw-bold text-end">{{ number_format($g->ttl_rp + $g->cost_cabut, 0) }}</td>
                            <td class="text-end fw-bold tdhide">{{ number_format($bkPcs, 0) }}</td>
                            <td class="text-end fw-bold tdhide">{{ number_format($bkGr, 0) }}</td>
                            <td class="text-end fw-bold tdhide">
                                {{ $g->selesai_1 == 'Y' ? number_format($rp_satuan * $bkGr) : 0 }}
                            </td>
                            <td class="text-end fw-bold tdhide">{{ $g->pcs_susut ?? 0 }}</td>
                            <td class="text-end fw-bold tdhide">{{ $g->gr_susut ?? 0 }}</td>
                            <td class="text-end fw-bold tdhide">
                                {{ number_format((1 - $bkGr / $g->gr_cabut) * 100, 0) }}%
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
                                    <i class="fas fa-check text-success fa-lg"></i>
                                @else
                                    <a href="#"><i class="fas  fa-hourglass-half text-danger"></i></a>
                                @endif

                            </td>
                            <td class="text-end fw-bold ">{{ number_format($b->pcs_awal_ctk ?? 0, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($b->gr_awal_ctk ?? 0, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($b->pcs_cu ?? 0, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($b->gr_cu ?? 0, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($b->pcs_akhir_ctk ?? 0, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($b->gr_akhir_ctk ?? 0, 0) }}</td>
                            <td class="text-end fw-bold ">0</td>
                            @php
                                $pcs_awal_ctk = $b->pcs_awal_ctk ?? 0;
                                $gr_awal_ctk = $b->gr_awal_ctk ?? 0;
                            @endphp
                            <td class="text-end fw-bold ">{{ number_format($bkPcs - $pcs_awal_ctk, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($bkGr - $gr_awal_ctk, 0) }}</td>
                            <td class="text-end fw-bold ">{{ number_format($b->ttl_rp_cetak ?? 0, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
