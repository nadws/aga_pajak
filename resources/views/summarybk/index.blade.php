<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont='container-fluid'>

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
                {{-- <br> <br> --}}
                {{-- <h6>Total Rp Gudang BK : {{ number_format($total_bk, 0) }}</h6>
                <h6>Total Rp Invoice : {{ number_format($total_invoice->ttl_hrga, 0) }}</h6> --}}
            </div>
            <div class="col-lg-6">
                {{--
                <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end" teks="Import" />
                --}}
                <x-theme.button href="{{ route('summarybk.export_summary', ['nm_gudang' => $nm_gudang]) }}"
                    icon="fas fa-file-excel" addClass="float-end" teks="Export Per Partai" />
                <x-theme.button href="{{ route('summarybk.export_summary_lot') }}" icon="fas fa-file-excel"
                    addClass="float-end" teks="Export Per Lot" />
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
                                    <th class="dhead" rowspan="2">Ket / nama partai</th>
                                    <th class="dhead" rowspan="2">Grade / No Lot</th>

                                    @if ($nm_gudang == 'summary')
                                        <th class="dhead text-center" colspan="3">Wip</th>
                                        <th class="dhead text-center" colspan="3">BK</th>
                                    @else
                                        <th class="dhead text-center" colspan="2">Wip</th>
                                        <th class="dhead text-center" colspan="2">BK</th>
                                    @endif



                                    <th class="dhead text-center" colspan="2">Susut Wip - bk</th>
                                    @if ($nm_gudang == 'summary')
                                        <th class="text-white text-center bg-danger" colspan="3">Wip Sisa</th>
                                    @else
                                        <th class="text-white text-center bg-danger" colspan="2">Wip Sisa</th>
                                    @endif

                                    <th class="dhead text-center" colspan="7">Cabut</th>
                                    <th class="bg-danger text-white text-center" colspan="2">Bk Sisa Pgws</th>
                                    <th class="dhead" rowspan="2">Ttl Rp</th>
                                </tr>
                                <tr>
                                    @if ($nm_gudang == 'summary')
                                        <th class="dhead text-center">Pcs</th>
                                        <th class="dhead text-center">Gr</th>
                                        <th class="dhead text-center">Ttl Rp</th>
                                        <th class="dhead text-center">Pcs</th>
                                        <th class="dhead text-center">Gr</th>
                                        <th class="dhead text-center">Ttl Rp</th>
                                    @else
                                        <th class="dhead text-center">Pcs</th>
                                        <th class="dhead text-center">Gr</th>
                                        <th class="dhead text-center">Pcs</th>
                                        <th class="dhead text-center">Gr</th>
                                    @endif



                                    <th class="dhead text-center">Gr</th>
                                    <th class="dhead text-center">sst(%)</th>
                                    @if ($nm_gudang == 'summary')
                                        <th class="text-white text-center bg-danger">Pcs</th>
                                        <th class="text-white text-center bg-danger">Gr</th>
                                        <th class="text-white text-center bg-danger">Ttl Rp</th>
                                    @else
                                        <th class="text-white text-center bg-danger">Pcs</th>
                                        <th class="text-white text-center bg-danger">Gr</th>
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
                                @foreach ($gudang as $no => $g)
                                    @php
                                        $ket = $g->ket2;
                                        $response = Http::get("$linkApi/bk_sum", ['nm_partai' => $ket]);
                                        $b = $response->object();

                                        $resSum = Http::get("$linkApi/datacabutsum2", ['nm_partai' => $ket]);
                                        $c = $resSum->object();

                                        $wipPcs = $g->pcs ?? 0;
                                        $wipGr = $g->gr ?? 0;
                                        $wipTllrp = $g->total_rp ?? 0;
                                        $bkPcs = $b->pcs_awal ?? 0;
                                        $bkGr = $b->gr_awal ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $g->ket2 }}
                                            <a href="#" class="float-end show_lot show{{ $no + 1 }}"
                                                partai="{{ $g->ket2 }}" no="{{ $no + 1 }}">
                                                <i class="fas fa-sort-down fa-lg text-primary "></i>

                                                <div class="spinner-border spinerLot loadLotLoading{{ $no + 1 }} spinner-border-sm text-primary"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </a>

                                            <a href="#" class="float-end hide_lot hide{{ $no + 1 }}" hidden
                                                no="{{ $no + 1 }}">
                                                <i class="fas fa-sort-up fa-lg text-primary "></i>
                                            </a>

                                        </td>
                                        <td class="text-center fw-bold">{{ $g->nm_grade }}</td>
                                        @php
                                            $hrga_modal_satuan = $wipTllrp / $wipGr;
                                        @endphp
                                        @if ($nm_gudang == 'summary')
                                            <td class="text-end fw-bold">{{ number_format($wipPcs, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($wipGr, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($wipTllrp, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($bkPcs, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($bkGr, 0) }}</td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($bkGr * $hrga_modal_satuan, 0) }}
                                            </td>
                                        @else
                                            <td class="text-end fw-bold">{{ number_format($wipPcs, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($wipGr, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($bkPcs, 0) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($bkGr, 0) }}</td>
                                        @endif






                                        <td class="text-end fw-bold">{{ number_format($g->gr_susut ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">
                                            {{ number_format((1 - $bkGr / $wipGr) * 100, 1) }}%
                                        </td>
                                        @php
                                            $gr_susut = $g->gr_susut ?? 0;
                                            $WipSisaPcs = $wipPcs - $bkPcs;
                                            $WipSisaGr = $wipGr - $bkGr - $gr_susut;
                                        @endphp

                                        @if ($nm_gudang == 'summary')
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($WipSisaPcs, 0) }}
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($WipSisaGr, 0) }}
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($hrga_modal_satuan * $WipSisaGr, 0) }}
                                            </td>
                                        @else
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($WipSisaPcs, 0) }}
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($WipSisaGr, 0) }}
                                            </td>
                                        @endif


                                        <td class="text-end fw-bold">{{ number_format($c->pcs_awal ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($c->gr_awal ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($c->pcs_akhir ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($c->gr_akhir ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($c->susut ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($c->eot ?? 0, 0) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($c->gr_flx ?? 0, 0) }}</td>
                                        @php
                                            $pcs_awal_bk = $b->pcs_awal ?? 0;
                                            $gr_awal_bk = $b->gr_awal ?? 0;

                                            $pcs_awal_cbt = $c->pcs_awal ?? 0;
                                            $gr_awal_cbt = $c->gr_awal ?? 0;
                                        @endphp
                                        <td class="text-end text-danger fw-bold">
                                            {{ number_format($pcs_awal_bk - $pcs_awal_cbt, 0) }}</td>
                                        <td class="text-end text-danger fw-bold">
                                            {{ number_format($gr_awal_bk - $gr_awal_cbt, 0) }}
                                        </td>

                                        <td class="text-end fw-bold">{{ number_format($c->ttl_rp ?? 0, 0) }}</td>

                                    </tr>
                            <tbody class="load_lot{{ $no + 1 }}"></tbody>
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
        <x-theme.modal title="Data Bk Cabut" idModal="load_bk_cabut" btnSave="T" size="modal-lg-max">
            <button class="btn btn-primary btn-loading" type="button" disabled="">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <div class="load_box"></div>
        </x-theme.modal>

    </x-slot>



    @section('scripts')
        <script>
            $(document).ready(function() {
                pencarian('pencarian', 'tableSearch')
                $(document).on('click', '#checkAll', function() {
                    // Setel properti checked dari kotak centang individu sesuai dengan status "cek semua"
                    $('.checkbox-item').prop('checked', $(this).prop('checked'));
                });
                $('.spinerLot').addClass('d-none');
                $('.show_lot').click(function(e) {

                    e.preventDefault();
                    var nm_partai = $(this).attr('partai');
                    var no = $(this).attr('no');
                    $('.loadLotLoading' + no).removeClass('d-none');
                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.get_no_lot') }}",
                        data: {
                            nm_partai: nm_partai
                        },
                        success: function(response) {
                            $('.loadLotLoading' + no).addClass('d-none');
                            $('.load_lot' + no).html(response);
                            $(".show" + no).hide();
                            $(".hide" + no).show();
                            $(".hide" + no).removeAttr("hidden");
                        }
                    });

                });
                $('.hide_lot').click(function(e) {
                    e.preventDefault();
                    var no = $(this).attr('no');
                    $('.load_lot' + no).html('');
                    $(".hide" + no).hide();
                    $(".show" + no).show();


                });
                var currentNoLot = '';
                var currentNmPartai = '';

                $(document).on('click', '.show_box', function(e) {
                    e.preventDefault();
                    $('.btn-loading').removeClass('d-none');

                    var no_lot = $(this).attr('no_lot');
                    var nm_partai = $(this).attr('nm_partai');
                    currentNoLot = no_lot;
                    currentNmPartai = nm_partai;
                    $('.no_lot_input').val(no_lot);
                    $('.nm_partai_input').val(nm_partai);
                    console.log(no_lot);
                    console.log(nm_partai);
                    $('.load_box').html('');
                    loadBoxData(no_lot, nm_partai, 5); // Default limit 5
                });

                $(document).on('change', '.load-data', function() {
                    $('.btn-loading').removeClass('d-none');
                    var val = $(this).val();
                    $('.load_box').html('');
                    loadBoxData(currentNoLot, currentNmPartai, val);
                    $(this).val(val);
                });

                function loadBoxData(no_lot, nm_partai, limit) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.get_no_box') }}",
                        data: {
                            no_lot: no_lot,
                            nm_partai: nm_partai,
                            limit: limit // Menambahkan parameter limit ke dalam data yang dikirimkan
                        },
                        success: function(response) {
                            $('.btn-loading').addClass('d-none');
                            $('.load_box').html(response);
                            pencarian('pencarianBox', 'tblAldi2')

                        }
                    });
                }





            });
        </script>
    @endsection
</x-theme.app>
