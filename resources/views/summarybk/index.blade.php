<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">
                {{-- <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" /> --}}
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
                    <div class="table-container">
                        <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                            <thead>
                                <tr>
                                    <th class="dhead" rowspan="2">#</th>
                                    <th class="dhead" rowspan="2">No Loot</th>
                                    <th class="dhead" rowspan="2">Keterangan</th>
                                    <th class="dhead text-center" colspan="3">Gudang</th>
                                    <th class="dhead text-center" colspan="3">Cabut</th>
                                    <th class="dhead text-center" colspan="3">Cetak</th>
                                    <th class="dhead text-center" colspan="3">Sortir</th>
                                    <th class="dhead text-center" colspan="3">Pengiriman</th>
                                </tr>
                                <tr>
                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp</th>

                                    <th class="dhead text-end">Pcs</th>
                                    <th class="dhead text-end">Gr</th>
                                    <th class="dhead text-end">Rp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gudang as $no => $g)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $g->no_lot }}</td>
                                        <td>{{ $g->ket }}</td>
                                        <td class="text-end">{{ $g->pcs }}</td>
                                        <td class="text-end">{{ $g->gr }}</td>
                                        <td class="text-end">{{ number_format($g->total_rp, 0) }}</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">0</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </form>
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
