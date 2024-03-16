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
            <tr>
                <td></td>
                <td>
                    <button class="btn float-end btn-primary btn-sm history" lokasi='cabut' data-bs-toggle="modal"
                        data-bs-target="#load_history"><i class="fas fa-history"></i>
                        History
                    </button>
                </td>
            </tr>
            <tr>
                <td>Search :</td>
                <td><input type="text" id="pencarian" class="form-control float-end"></td>
            </tr>
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



                        <th class="dhead text-center tdhide" colspan="3">Susut BK</th>
                        @if ($nm_gudang == 'summary')
                            <th class="text-white text-center bg-danger tdhide" colspan="3">BK Sisa</th>
                        @else
                            <th class="text-white text-center bg-danger tdhide" colspan="2">Wip Sisa</th>
                        @endif
                        <th class="dhead text-center" rowspan="2">Selesai Bk</th>

                        <th class="dhead text-center" colspan="7">Cabut</th>
                        <th class="bg-danger text-white text-center" colspan="2">Bk Sisa Pgws</th>
                        <th class="dhead" rowspan="2">Ttl Rp Cost</th>
                        <th class="dhead" rowspan="2">Ttl Rp Bk</th>
                        <th class="dhead" rowspan="2">Selesai</th>
                    </tr>
                    <tr>
                        @if ($nm_gudang == 'summary')
                            <th class="dhead text-center tdhide">Pcs</th>
                            <th class="dhead text-center tdhide">Gr</th>
                            <th class="dhead text-center tdhide">Ttl Rp</th>
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
                        <th class="dhead text-center">Pcs Akhir</th>
                        <th class="dhead text-center">Gr Akhir</th>
                        <th class="dhead text-center">Susut</th>
                        <th class="dhead text-center">Eot</th>
                        <th class="dhead text-center">Flx</th>

                        <th class="text-white bg-danger text-center">Pcs</th>
                        <th class="text-white bg-danger text-center">Gr</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ttlwippcs = 0;
                        $ttlwipgr = 0;
                        $ttlwiprupiah = 0;
                        $ttlbtupcs = 0;
                        $ttlbtugr = 0;
                        $ttlbtuttlrp = 0;
                        $ttlsstbk = 0;
                        $ttlsstbkgr = 0;
                        $ttlbksisapcs = 0;
                        $ttlbksisagr = 0;
                        $ttlbksisattlrp = 0;
                        $ttlcbtawalpcs = 0;
                        $ttlcbtawalgr = 0;
                        $ttlcbtakhirpcs = 0;
                        $ttlcbtakhirgr = 0;
                        $ttlcbteot = 0;
                        $ttlcbtflx = 0;
                        $ttlbksisapgwspcs = 0;
                        $ttlbksisapgwsgr = 0;
                        $ttlrpcost = 0;
                        $ttlrpbk = 0;
                    @endphp
                    @foreach ($gudang as $no => $g)
                        @php
                            $ket = $g->ket2;
                            $resSum = Cache::remember('datacabutsum5_' . $ket, now()->addMinutes(5), function () use (
                                $ket,
                                $linkApi,
                            ) {
                                return Http::get("$linkApi/datacabutsum2", ['nm_partai' => $ket])->object();
                            });
                            $c = $resSum;
                            $g->relatedModel = $c;

                            $wipPcs = $g->pcs ?? 0;
                            $wipGr = $g->gr ?? 0;
                            $wipTllrp = $g->total_rp ?? 0;
                            $bkPcs = $c->pcs_bk ?? 0;
                            $bkGr = $c->gr_awal_bk ?? 0;

                            $gr_susut = $g->gr_susut ?? 0;
                            $pcs_susut = $g->pcs_susut ?? 0;
                            $WipSisaPcs = $wipPcs - $bkPcs - $pcs_susut;
                            $WipSisaGr = $wipGr - $bkGr - $gr_susut;
                            $selesai_bk = $c->selesai ?? 'T';
                            $gr_akhir_cbt = $c->gr_akhir ?? 0;
                            $gr_akhir_eo = $c->gr_eo_akhir ?? 0;

                            $hrga_modal_satuan = $wipTllrp / ($wipGr - $gr_susut);
                            $pcs_awal_bk = $c->pcs_bk ?? 0;
                            $gr_awal_bk = $c->gr_awal_bk ?? 0;

                            $pcs_awal_cbt = $c->pcs_awal ?? 0;
                            $gr_awal_cbt = $c->gr_awal ?? 0;
                            $gr_awal_eo = $c->gr_awal_eo ?? 0;
                            $ttl_rp_cbt = $c->ttl_rp ?? 0;
                            $ttl_rp_eo = $c->ttl_rp_eo ?? 0;
                        @endphp
                        @php
                            $ttlwippcs += $wipPcs;
                            $ttlwipgr += $wipGr;
                            $ttlwiprupiah += $wipTllrp;
                            $ttlbtupcs += $bkPcs;
                            $ttlbtugr += $bkGr;
                            $ttlbtuttlrp += $g->selesai == 'Y' ? $bkGr * $hrga_modal_satuan : 0;
                            $ttlsstbk += $g->pcs_susut ?? 0;
                            $ttlsstbkgr += $g->gr_susut ?? 0;
                            $ttlbksisapcs += $WipSisaPcs;
                            $ttlbksisagr += $WipSisaGr;
                            $ttlbksisattlrp += $hrga_modal_satuan * $WipSisaGr;
                            $ttlcbtawalpcs += $c->pcs_awal ?? 0;
                            $ttlcbtawalgr += $gr_awal_cbt + $gr_awal_eo;
                            $ttlcbtakhirpcs += $c->pcs_akhir ?? 0;
                            $ttlcbtakhirgr += $gr_akhir_cbt + $gr_akhir_eo;
                            $ttlcbteot += $c->eot ?? 0;
                            $ttlcbtflx += $c->gr_flx ?? 0;
                            $ttlbksisapgwspcs += $pcs_awal_bk - $pcs_awal_cbt;
                            $ttlbksisapgwsgr += $gr_awal_bk - ($gr_awal_cbt + $gr_awal_eo);
                            $ttlrpcost += $ttl_rp_cbt + $ttl_rp_eo;
                            $ttlrpbk += $g->selesai_1 == 'Y' ? $hrga_modal_satuan * $bkGr : 0;
                        @endphp
                        <tr>
                            <td>{{ $no + 1 }} </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" nm_partai="{{ $g->ket2 }}"
                                    data-bs-target="#load_bk_cabut" class="show_box">{{ $g->ket2 }}</a>
                            </td>
                            <td class="text-center fw-bold">
                                {{ $g->nm_grade }}
                            </td>

                            @if ($nm_gudang == 'summary')
                                <td class="text-end fw-bold tdhide">{{ number_format($wipPcs, 0) }}
                                </td>
                                <td class="text-end fw-bold tdhide">{{ number_format($wipGr, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($wipTllrp, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($bkPcs, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($bkGr, 0) }}</td>
                                <td class="text-end fw-bold tdhide">
                                    {{ $g->selesai == 'Y' ? number_format($bkGr * $hrga_modal_satuan, 0) : '0' }}
                                </td>
                            @else
                                <td class="text-end fw-bold tdhide">{{ number_format($wipPcs, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($wipGr, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($bkPcs, 0) }}</td>
                                <td class="text-end fw-bold tdhide">{{ number_format($bkGr, 0) }}</td>
                            @endif
                            <td class="text-end fw-bold tdhide">{{ number_format($g->pcs_susut ?? 0, 0) }}
                            </td>
                            <td class="text-end fw-bold tdhide">{{ number_format($g->gr_susut ?? 0, 0) }}
                            </td>
                            <td class="text-end fw-bold tdhide">
                                {{ number_format((1 - $bkGr / $wipGr) * 100, 1) }}%
                            </td>
                            @if ($nm_gudang == 'summary')
                                <td class="text-end fw-bold text-danger tdhide">
                                    {{ number_format($WipSisaPcs, 0) }}
                                </td>
                                <td class="text-end fw-bold text-danger tdhide">
                                    {{ number_format($WipSisaGr, 0) }}
                                </td>
                                <td class="text-end fw-bold text-danger tdhide">
                                    {{ number_format($hrga_modal_satuan * $WipSisaGr, 0) }}
                                </td>
                            @else
                                <td class="text-end fw-bold text-danger tdhide">
                                    {{ number_format($WipSisaPcs, 0) }}
                                </td>
                                <td class="text-end fw-bold text-danger tdhide">
                                    {{ number_format($WipSisaGr, 0) }}
                                </td>
                            @endif
                            <td class="text-center fw-bold">
                                @if ($g->selesai_1 == 'Y')
                                    <i class="fas fa-check text-success fa-lg"></i>
                                @else
                                    @if ($g->selesai == 'Y')
                                        <a href="#" class="btn btn-sm btn-primary selesai_box"
                                            data-bs-toggle="modal" data-bs-target="#load_bk_selesai"
                                            lokasi="{{ $lokasi }}" nm_partai="{{ $g->ket2 }}"
                                            gudang="{{ $nm_gudang }}">Selesai</a>
                                    @else
                                        <a href="#"><i class="fas  fa-hourglass-half text-danger"></i></a>
                                    @endif
                                @endif

                            </td>
                            <td class="text-end fw-bold">{{ number_format($c->pcs_awal ?? 0, 0) }}</td>

                            <td class="text-end fw-bold">{{ number_format($gr_awal_eo, 0) }}
                            </td>
                            <td class="text-end fw-bold">{{ number_format($c->pcs_akhir ?? 0, 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($gr_akhir_cbt + $gr_akhir_eo, 0) }}
                            </td>
                            <td class="text-end fw-bold">
                                {{ $gr_awal_cbt + $gr_awal_eo == 0 ? 0 : number_format((1 - ($gr_akhir_cbt + $gr_akhir_eo) / ($gr_awal_cbt + $gr_awal_eo)) * 100, 1) }}%
                            </td>
                            <td class="text-end fw-bold">{{ number_format($c->eot ?? 0, 0) }}</td>
                            <td class="text-end fw-bold">{{ number_format($c->gr_flx ?? 0, 0) }}</td>

                            <td class="text-end text-danger fw-bold">
                                {{ number_format($pcs_awal_bk - $pcs_awal_cbt, 0) }}</td>
                            <td class="text-end text-danger fw-bold">
                                {{ number_format($gr_awal_bk - ($gr_awal_cbt + $gr_awal_eo), 0) }}
                            </td>

                            <td class="text-end fw-bold">{{ number_format($ttl_rp_cbt + $ttl_rp_eo, 0) }}</td>
                            <td class="text-end fw-bold">
                                @if ($g->selesai_1 == 'Y')
                                    {{-- {{ number_format($hrga_modal_satuan * ($gr_akhir_cbt + $gr_akhir_eo), 0) }} --}}
                                    {{ number_format($hrga_modal_satuan * $bkGr, 0) }}
                                @else
                                @endif

                            </td>
                            <td class="text-center fw-bold">
                                @if ($g->selesai_1 == 'Y')
                                    <a href="#" class="btn btn-primary btn-sm finish" data-bs-toggle="modal"
                                        data-bs-target="#load_bk_finish" lokasi="{{ $lokasi }}"
                                        nm_partai="{{ $g->ket2 }}" gudang="{{ $nm_gudang }}">Selesai</a>
                                @else
                                @endif

                            </td>

                        </tr>
                    @endforeach



                </tbody>
                <tfoot>
                    <tr>
                        <td class="dhead" colspan="3">Total</td>
                        @if ($nm_gudang == 'summary')
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlwippcs, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlwipgr, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlwiprupiah, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlbtupcs, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlbtugr, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlbtuttlrp, 0) }}</td>
                        @else
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlwippcs, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlwipgr, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlbtupcs, 0) }}</td>
                            <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlbtugr, 0) }}</td>
                        @endif
                        <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlsstbk, 0) }}</td>
                        <td class="text-end dhead fw-bold tdhide">{{ number_format($ttlsstbkgr, 0) }}</td>
                        <td class="text-end dhead fw-bold tdhide">-</td>
                        @if ($nm_gudang == 'summary')
                            <td class="text-end bg-danger text-white fw-bold  tdhide">
                                {{ number_format($ttlbksisapcs, 0) }}
                            </td>
                            <td class="text-end bg-danger text-white fw-bold  tdhide">
                                {{ number_format($ttlbksisagr, 0) }}
                            </td>
                            <td class="text-end fw-bold  bg-danger text-white tdhide">
                                {{ number_format($ttlbksisattlrp, 0) }}
                            </td>
                        @else
                            <td class="text-end bg-danger text-white fw-bold  tdhide">
                                {{ number_format($ttlbksisapcs, 0) }}
                            </td>
                            <td class="text-end bg-danger text-white fw-bold  tdhide">
                                {{ number_format($ttlbksisagr, 0) }}
                            </td>
                        @endif
                        <td class="text-center dhead fw-bold">-</td>
                        <td class="text-end dhead fw-bold">{{ number_format($ttlcbtawalpcs, 0) }}</td>
                        <td class="text-end dhead fw-bold">{{ number_format($ttlcbtawalgr, 0) }}</td>
                        <td class="text-end dhead fw-bold">{{ number_format($ttlcbtakhirpcs, 0) }}</td>
                        <td class="text-end dhead fw-bold">{{ number_format($ttlcbtakhirgr, 0) }}</td>
                        <td class="text-end fw-bold dhead">-</td>
                        <td class="text-end fw-bold dhead">{{ number_format($ttlcbteot, 0) }}</td>
                        <td class="text-end fw-bold dhead">{{ number_format($ttlcbtflx, 0) }}</td>

                        <td class="text-end bg-danger text-white fw-bold">{{ number_format($ttlbksisapgwspcs, 0) }}
                        </td>
                        <td class="text-end bg-danger text-white fw-bold">{{ number_format($ttlbksisapgwsgr, 0) }}
                        </td>

                        <td class="text-end fw-bold dhead">{{ number_format($ttlrpcost, 0) }}</td>
                        <td class="text-end fw-bold dhead">{{ number_format($ttlrpbk, 0) }}</td>
                        <td class="text-center fw-bold dhead"></td>

                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>
