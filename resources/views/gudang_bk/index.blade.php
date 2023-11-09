<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('gudangBk.export_gudang_bk') }}" method="get">
            <section class="row">

                <div class="col-lg-6">
                    @include('gudang_bk.nav')
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-4">
                    <table class="float-end">
                        <td>Search :</td>
                        <td><input type="text" id="pencarian" class="form-control float-end"></td>
                    </table>
                </div>
                <div class="col-lg-12 mt-2">

                    <div class="table-container">
                        <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                            <thead>
                                @php
                                    $ttlRp = 0;
                                    foreach ($gudang as $g) {
                                        $ttlRp += $g->rupiah;
                                    }
                                @endphp
                                <tr>
                                    <th class="dhead">#</th>
                                    <th class="dhead">Tanggal</th>
                                    <th class="dhead">No Lot</th>
                                    <th class="dhead">Grade</th>
                                    <th class="text-end dhead">Pcs</th>
                                    <th class="text-end dhead">Gram</th>
                                    <th class="text-end dhead">Rp/gr</th>
                                    <th class="text-end dhead">Rupiah <br>(Rp {{ number_format($ttlRp, 0) }})</th>
                                    <th class="dhead">Ket</th>
                                    <th class="dhead">Lok/Tgl</th>
                                    <th class="dhead">No Produksi</th>
                                    @if ($nm_gudang == 'produksi')
                                        <th class="text-end dhead">Pcs diambil</th>
                                        <th class="text-end dhead">Gr diambil</th>
                                        <th class="text-end dhead">Pcs sisa</th>
                                        <th class="text-end dhead">Gr sisa</th>
                                    @endif
                                    <th class="dhead text-center">
                                        <button type="submit" name="submit" value="export" class="badge bg-success"><i
                                                class="fas fa-file-excel"></i></button>
                                        <br>
                                        <input type="checkbox" id="checkAll" name="" id="">
                                        <input type="hidden" name="gudang" value="{{ $nm_gudang }}" id="">
                                    </th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gudang as $no => $g)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ tanggal($g->tgl) }}</td>
                                        <td>{{ $g->no_lot }}</td>
                                        <td>{{ $g->nm_grade }}</td>
                                        <td class="text-end">{{ $g->pcs }}</td>
                                        <td class="text-end">{{ $g->gr }}</td>
                                        <td class="text-end">{{ number_format($g->rupiah / $g->gr, 0) }}</td>
                                        <td class="text-end">Rp {{ number_format($g->rupiah, 0) }}</td>
                                        <td>{{ $g->ket }}</td>
                                        <td>{{ $g->lok_tgl }}</td>
                                        <td>{{ $g->no_produksi }}</td>
                                        @if ($nm_gudang == 'produksi')
                                            <td class="text-end">{{ $g->pcs_diambil }}</td>
                                            <td class="text-end">{{ $g->gr_diambil }}</td>
                                            <td class="text-end">{{ $g->pcs - $g->pcs_diambil }}</td>
                                            <td class="text-end">{{ $g->gr - $g->gr_diambil }}</td>
                                        @endif

                                        <td class="text-center"><input type="checkbox" class="checkbox-item"
                                                name="id_buku_campur[]" value="{{ $g->id_buku_campur }}"
                                                id=""></td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </form>
        <form action="{{ route('gudangBk.import_gudang_bk') }}" method="post" enctype="multipart/form-data">
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
