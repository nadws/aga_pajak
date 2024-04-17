<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont="container-fluid">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        {{-- <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="post"> --}}
        @csrf
        <section class="row">

            <div class="col-lg-9">
                @include('laporan_produksi.navlaporan')
            </div>
            {{-- <div class="col-lg-3">
                    <table class="float-end">
                        <td>Search :</td>
                        <td><input type="text" id="pencarian" class="form-control float-end"></td>
                    </table>
                </div> --}}
            <div class="col-lg-12 mt-2">
                <table class="table table-hover table-bordered" id="table" width="100%">
                    <thead>
                        <tr>
                            <th class="dhead" rowspan="2">#</th>
                            <th class="dhead" rowspan="2">ket / nama partai</th>
                            <th class="dhead" rowspan="2">grade</th>
                            <th class="dhead text-center" colspan="3">BK</th>
                            <th class="dhead text-center" colspan="3">BK TIMBANG ULANG</th>
                            <th class="dhead text-center" colspan="3">Susut Bk</th>
                            <th class="text-white text-center bg-danger tdhide" colspan="3">BK Sisa</th>
                        </tr>
                        <tr>
                            <th class="dhead text-end">pcs</th>
                            <th class="dhead  text-end">gr</th>
                            <th class="dhead  text-end">cost</th>

                            <th class="dhead text-end">pcs</th>
                            <th class="dhead  text-end">gr</th>
                            <th class="dhead  text-end">cost</th>

                            <th class="dhead text-end">pcs</th>
                            <th class="dhead  text-end">gr</th>
                            <th class="dhead  text-end">sst%</th>

                            <th class="text-white text-center bg-danger">Pcs</th>
                            <th class="text-white text-center bg-danger">Gr</th>
                            <th class="text-white text-center bg-danger">Ttl Rp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gudang as $no => $g)
                            @php
                                $wipPcs = $g->pcs ?? 0;
                                $wipGr = $g->gr ?? 0;
                                $wipTllrp = $g->total_rp ?? 0;
                                $gr_susut = $g->gr_susut ?? 0;

                                // api
                                $ket = $g->ket2;
                                $resSum = Cache::remember(
                                    'datacabutsum5_' . $ket,
                                    now()->addMinutes(5),
                                    function () use ($ket, $linkApi) {
                                        return Http::get("$linkApi/datacabutsum2", ['nm_partai' => $ket])->object();
                                    },
                                );
                                $c = $resSum;
                                $g->relatedModel = $c;

                                $bkPcs = $c->pcs_bk ?? 0;
                                $bkGr = $c->gr_awal_bk ?? 0;
                                $hrga_modal_satuan = $wipTllrp / ($wipGr - $gr_susut);
                                $gr_susut = $g->gr_susut ?? 0;
                                $pcs_susut = $g->pcs_susut ?? 0;

                                $WipSisaPcs = $wipPcs - $bkPcs - $pcs_susut;
                                $WipSisaGr = $wipGr - $bkGr - $gr_susut;
                            @endphp
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $g->ket2 }}</td>
                                <td>{{ $g->nm_grade }}</td>
                                <td class="text-end">{{ number_format($wipPcs, 0) }}</td>
                                <td class="text-end">{{ number_format($wipGr, 0) }}</td>
                                <td class="text-end">{{ number_format($wipTllrp, 0) }}</td>

                                <td class="text-end  ">{{ number_format($bkPcs, 0) }}</td>
                                <td class="text-end  ">{{ number_format($bkGr, 0) }}</td>
                                <td class="text-end  ">
                                    {{ $g->selesai == 'Y' ? number_format($bkGr * $hrga_modal_satuan, 0) : '0' }}
                                </td>
                                <td class="text-end ">{{ number_format($g->pcs_susut ?? 0, 0) }}
                                </td>
                                <td class="text-end ">{{ number_format($g->gr_susut ?? 0, 0) }}
                                </td>
                                <td class="text-end ">
                                    {{ number_format((1 - $bkGr / $wipGr) * 100, 1) }}%
                                </td>
                                <td class="text-end  text-danger tdhide">
                                    {{ number_format($WipSisaPcs, 0) }}
                                </td>
                                <td class="text-end  text-danger tdhide">
                                    {{ number_format($WipSisaGr, 0) }}
                                </td>
                                <td class="text-end  text-danger tdhide">
                                    {{ number_format($hrga_modal_satuan * $WipSisaGr, 0) }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </section>
        {{-- </form> --}}
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
