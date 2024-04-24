<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">

                <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" />
                <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="post">
                    @csrf
                    <button class="btn btn-success float-end me-2"><i class="fas fa-file-excel"></i> Export</button>
                </form>
                <x-theme.button modal="Y" idModal="tambah" icon="fas fa-plus" addClass="float-end"
                    teks="Tambah BK" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        {{-- <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="post"> --}}
        @csrf
        <section class="row">

            <div class="col-lg-9">
                @include('gudangnew.nav')
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
                            {{-- <th class="dhead text-center">
                                @if ($nm_gudang == 'produksi' || $nm_gudang == 'wip')
                                    <button type="submit" name="submit" value="export_produksi"
                                        class="badge bg-success"><i class="fas fa-file-excel"></i></button>
                                @else
                                    <button type="submit" name="submit" value="export" class="badge bg-success"><i
                                            class="fas fa-file-excel"></i></button>
                                @endif
                                <br>

                                <input type="hidden" name="gudang" value="{{ $nm_gudang }}" id="">
                            </th> --}}


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


                                {{-- <td class="text-center">
                                    <input type="checkbox" class="checkbox-item" name="id_buku_campur[]"
                                        value="{{ $g->id_buku_campur }}" id="">
                                </td> --}}


                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </section>
        {{-- </form> --}}
        <form action="{{ route('gudangBk.import_buku_campur_bk') }}" method="post" enctype="multipart/form-data">
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
        <form action="{{ route('gudangBk.import_gudang_produksi_new') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Gudang Gabung" idModal="import2" btnSave="Y">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">File</label>
                        <input type="file" class="form-control" name="file">
                        <input type="hidden" name="gudang" value="{{ $nm_gudang }}" id="">
                    </div>
                </div>

            </x-theme.modal>
        </form>
        <form action="{{ route('gudangnew.save_gudang_bk') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah Bk Awal" size="modal-lg-max" idModal="tambah" btnSave="Y">
                <div class="row">
                    <div class="col-lg-12">
                        <h5>gudang {{ $nm_gudang }}</h5>
                    </div>
                    <input type="hidden" value="{{ $nm_gudang }}" name="gudang">
                    <input type="hidden" value="herry" name="lokasi">
                    <div class="col-lg-2">
                        <label for="">Suplier Awal</label>
                        <input type="text" class="form-control" name="suplier_awal[]">
                    </div>
                    <div class="col-lg-2">
                        <label for="">Date</label>
                        <input type="date" class="form-control" name="tgl[]">
                    </div>
                    <div class="col-lg-1">
                        <label for="">Grade</label>
                        <input type="text" class="form-control" name="grade[]">
                    </div>
                    <div class="col-lg-2">
                        <label for="">Pcs</label>
                        <input type="text" class="form-control" name="pcs[]">
                    </div>
                    <div class="col-lg-2">
                        <label for="">Gr</label>
                        <input type="text" class="form-control" name="gr[]">
                    </div>
                    <div class="col-lg-2">
                        <label for="">Rp/Gr</label>
                        <input type="text" class="form-control" name="rp_gram[]">
                    </div>
                    <div class="col-lg-1">
                        <label for="">Lot</label>
                        <input type="text" class="form-control" name="lot[]">
                    </div>
                    <div class="col-lg-2 mt-2">
                        <label for="">Nama Partai Herry
                        </label>
                        <input type="text" class="form-control" name="ket1[]">
                    </div>
                    <div class="col-lg-2 mt-2">
                        <label for="">Nama Partai Sinta</label>
                        <input type="text" class="form-control" name="ket2[]">
                    </div>

                </div>
                <div class="tambah-data"></div>
                <div class="col-lg-12 mt-2">
                    <button type="button" class="btn btn-block btn-lg tbh_baris"
                        style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                        <i class="fas fa-plus"></i> Tambah Baris Baru
                    </button>
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


                var count = 3;
                $(document).on("click", ".tbh_baris", function() {
                    count = count + 1;
                    $.ajax({
                        url: "/gudangnew/tbh_baris?count=" + count,
                        type: "Get",
                        success: function(data) {
                            $(".tambah-data").append(data);
                        },
                    });
                });

                $(document).on("click", ".remove_baris", function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();
                });
            });
        </script>
    @endsection
</x-theme.app>
