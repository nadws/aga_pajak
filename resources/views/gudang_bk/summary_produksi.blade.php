<x-theme.app title="{{ $title }}" table="Y" sizeCard="11">

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
        <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="post">
            @csrf
            <section class="row">

                <div class="col-lg-9">
                    @include('gudang_bk.nav')
                </div>
                <div class="col-lg-3">
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
                                    <th class="dhead">#</th>
                                    <th class="dhead">Grade</th>
                                    <th class="dhead">Pcs</th>
                                    <th class="dhead">Gr</th>
                                    <th class="dhead">Rp/Gr</th>
                                    <th class="dhead">Keterangan / Nama Partai Herry</th>
                                    <th class="dhead">Keterangan / Nama Partai Sinta</th>
                                    <th class="dhead">Total Rp</th>
                                    <th class="dhead">
                                        @if ($nm_gudang == 'produksi' || $nm_gudang == 'wip' || $nm_gudang == 'summary_produksi')
                                            <button type="submit" name="submit" value="export_produksi"
                                                class="badge bg-success"><i class="fas fa-file-excel"></i></button>
                                        @else
                                            <button type="submit" name="submit" value="export"
                                                class="badge bg-success"><i class="fas fa-file-excel"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gudang as $no => $g)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $g->nm_grade }}</td>
                                        <td class="text-end">{{ $g->pcs }}</td>
                                        <td class="text-end">{{ $g->gr }}</td>
                                        <td class="text-end">{{ number_format($g->total_rp / $g->gr, 0) }}</td>
                                        <td>{{ $g->ket }}</td>
                                        <td>{{ $g->ket2 }}</td>
                                        <td class="text-end">{{ number_format($g->total_rp, 0) }}</td>
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
