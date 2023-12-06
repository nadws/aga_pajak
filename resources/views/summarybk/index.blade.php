<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont='container-fluid'>

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6> <br> <br>
                <h6>Total Rp Gudang BK : {{ number_format($total_bk, 0) }}</h6>
                <h6>Total Rp Invoice : {{ number_format($total_invoice->ttl_hrga, 0) }}</h6>
            </div>
            <div class="col-lg-6">
                {{-- <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" /> --}}
                <x-theme.button href="{{ route('summarybk.export_summary') }}" icon="fas fa-file-excel"
                    addClass="float-end" teks="Export" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="get">
            <section class="row">

                <div class="col-lg-8">
                    @include('gudang_bk.nav')
                </div>
                <div class="col-lg-4">
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
                                    <th class="dhead" rowspan="2">No Lot</th>
                                    <th class="dhead" rowspan="2">Ket / nama partai</th>
                                    <th class="dhead text-center" colspan="3"
                                        style="border-left: 3px solid #787878;border-right: 3px solid #787878">
                                        Gudang Wip</th>
                                    <th class="dhead text-center" colspan="2"
                                        style="border-right: 3px solid #787878;">Bk Cabut</th>
                                    <th class="dhead text-center" colspan="5"
                                        style="border-right: 3px solid #787878;">Cabut</th>
                                    <th class="dhead text-center" colspan="3"
                                        style="border-right: 3px solid #787878;">Gdg <br> cbt selesai</th>
                                    <th class="dhead text-center" colspan="5"
                                        style="border-right: 3px solid #787878;">Cetak</th>
                                    <th class="dhead text-center" colspan="2">Gdg <br> ctk selesai</th>
                                </tr>
                                <tr>
                                    <th class="dhead text-end" style="border-left: 3px solid #787878;">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-center" style="border-right: 3px solid #787878;">Aksi</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end" style="border-right: 3px solid #787878;">Gr</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp C</th>
                                    <th class="dhead text-end">Rp/gr</th>
                                    <th class="dhead text-end" style="border-right: 3px solid #787878;">susut</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-center" style="border-right: 3px solid #787878;">Aksi</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp C</th>
                                    <th class="dhead text-end">Rp/gr</th>
                                    <th class="dhead text-end" style="border-right: 3px solid #787878;">susut</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gudang as $no => $g)
                                    @php
                                        $response = Http::get("sarang.ptagafood.com/api/apibk/sarang?no_lot=$g->no_lot&nm_partai=$g->ket");
                                        $cbt = $response['data']['cabut'] ?? null;
                                        $c = json_decode(json_encode($cbt));

                                        $ctk = $response['data']['cetak'] ?? null;
                                        $ck = json_decode(json_encode($ctk));

                                        $str = $response['data']['sortir'] ?? null;
                                        $st = json_decode(json_encode($str));

                                        $bk_cbt = $response['data']['bk_cabut'] ?? null;
                                        $bk_cb = json_decode(json_encode($bk_cbt));

                                        $pcs_awal_bk = $bk_cb->pcs_awal ?? 0;
                                        $gr_awal_bk = $bk_cb->gr_awal ?? 0;

                                        $pcs_awal_cbt = $c->pcs_awal ?? 0;
                                        $gr_awal_cbt = $c->gr_awal ?? 0;
                                        $pcs_akhir_cbt = $c->pcs_akhir ?? 0;
                                        $gr_akhir_cbt = $c->gr_akhir ?? 0;

                                        $ttl_rp_cbt_dibawa = $c->ttl_rp_dibawa ?? 0;
                                        $rp_cetak = $ck->rp_c ?? 0;
                                        $rp_cetak_dibawa = $ck->rp_c_dibawa ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $g->no_lot }}</td>
                                        <td>{{ $g->ket }}</td>

                                        <td class="text-end" style="border-left: 3px solid #787878">
                                            {{ $g->selesai_1 == 'Y' ? '0' : number_format($g->pcs - $pcs_awal_bk, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ $g->selesai_1 == 'Y' ? '0' : number_format($g->gr - $gr_awal_bk, 0) }}
                                        </td>
                                        <td class="text-center" style="border-right: 3px solid #787878">
                                            @if ($g->selesai_1 == 'Y')
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <a href="{{ route('summarybk.selesai1', ['no_lot' => $g->no_lot, 'ket' => $g->ket]) }}"
                                                    class="btn btn-primary btn-sm ">Selesai</a>
                                            @endif

                                        </td>

                                        <td class="text-end">
                                            {{ number_format($pcs_awal_bk - $pcs_awal_cbt, 0) }}
                                        </td>
                                        <td class="text-end" style="border-right: 3px solid #787878">
                                            {{ number_format($gr_awal_bk - $gr_awal_cbt, 0) }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($pcs_awal_cbt - $pcs_akhir_cbt, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($c->gr_awal_cbt_hilang ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($c->ttl_rp ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($c->rp_gram ?? 0, 0) }}
                                        </td>
                                        <td class="text-end" style="border-right: 3px solid #787878">
                                            {{ number_format($c->susut ?? 0, 0) }} %
                                        </td>
                                        @php
                                            $pcs_awal_ctk = $ck->pcs_awal ?? 0;
                                            $gr_awal_ctk = $ck->gr_awal ?? 0;
                                        @endphp
                                        <td class="text-end">
                                            {{ $g->selesai_2 == 'Y' ? '0' : number_format($pcs_akhir_cbt - $pcs_awal_ctk, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ $g->selesai_2 == 'Y' ? '0' : number_format($gr_akhir_cbt - $gr_awal_ctk, 0) }}
                                        </td>
                                        <td class="text-center" style="border-right: 3px solid #787878">
                                            @if ($g->selesai_2 == 'Y')
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <a href="{{ route('summarybk.selesai2', ['no_lot' => $g->no_lot, 'ket' => $g->ket]) }}"
                                                    class="btn btn-primary btn-sm ">Selesai</a>
                                            @endif
                                        </td>
                                        @php
                                            $ttl_pcs_ctk = $ck->pcs_awal - $ck->pcs_akhir ?? 0;
                                        @endphp
                                        <td class="text-end">
                                            {{ number_format($ck->pcs_awal_ctk_dibawa ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($ck->gr_awal_ctk_dibawa ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ $ttl_pcs_ctk == 0 ? 0 : number_format($rp_cetak + $ttl_rp_cbt_dibawa, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ $ttl_pcs_ctk == 0 ? 0 : number_format(($rp_cetak + $ttl_rp_cbt_dibawa) / $ck->gr_awal, 0) }}
                                        </td>
                                        <td class="text-end" style="border-right: 3px solid #787878">
                                            {{ number_format($ck->susut ?? 0, 0) }} %
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($ck->pcs_akhir ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($ck->gr_akhir ?? 0, 0) }}
                                        </td>


                                        {{-- @php
                                            $rp_cabut = $c->ttl_rp ?? 0;
                                            $rp_cetak = $ck->rp_c ?? 0;
                                            $rp_sortir = $st->rp_c ?? 0;
                                        @endphp
                                        <td class="text-end">
                                            {{ number_format($rp_cabut + $rp_cetak + $rp_sortir, 0) }}
                                        </td> --}}



                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </form>
        <form action="{{ route('gudangBk.import_summary_wip') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Gudang Bk" idModal="import" btnSave="Y">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">File</label>
                        <input type="file" class="form-control" name="file">
                        <input type="hidden" name="gudang" value="{{ $nm_gudang }}" id="">
                    </div>
                </div>

            </x-theme.modal>
        </form>
    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                pencarian('pencarian', 'tableSearch')
                $(document).on('click', '#checkAll', function() {
                    // Setel properti checked dari kotak centang individu sesuai dengan status "cek semua"
                    $('.checkbox-item').prop('checked', $(this).prop('checked'));
                });
            });
        </script>
    @endsection
</x-theme.app>
