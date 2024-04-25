<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="importcetak" icon="fas fa-upload" addClass="float-end"
                    teks="Import" />
                <a href="{{ route('gudangcetak.export_g_cetak') }}" class="btn btn-success float-end me-2"><i
                        class="fas fa-file-excel"></i> Export</a>
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        {{-- <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="post"> --}}
        @csrf
        <section class="row">

            {{-- <div class="col-lg-9">
                @include('gudangnew.nav')
            </div> --}}
            {{-- <div class="col-lg-3">
                    <table class="float-end">
                        <td>Search :</td>
                        <td><input type="text" id="pencarian" class="form-control float-end"></td>
                    </table>
                </div> --}}
            <div class="col-lg-9"></div>
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
                                <th class="dhead">No</th>
                                <th class="dhead">Partai</th>
                                <th class="dhead">No Box</th>
                                <th class="dhead">Tipe</th>
                                <th class="dhead">Pengawas</th>
                                <th class="dhead">Nama Anak</th>
                                <th class="dhead">Kelas</th>
                                <th class="dhead" class="text-end">Pcs</th>
                                <th class="dhead" class="text-end">Gr</th>
                                <th class="dhead" class="text-end">Pcs timbang ulang</th>
                                <th class="dhead" class="text-end">Gr timbang ulang</th>
                                <th class="dhead">Kerja</th>
                                <th class="dhead"><button type="submit" class="badge bg-success">masuk bk
                                        grading</button></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($cabut as $no => $g)
                                @php
                                    $gdng_ctk = DB::table('gudang_ctk')
                                        ->where('no_box', $g->no_box)
                                        ->first();

                                    if (isset($gdng_ctk) && $gdng_ctk->gudang == 'sortir') {
                                        continue;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $g->nm_partai }}</td>
                                    <td>{{ $g->no_box }}</td>
                                    <td>{{ $g->tipe }}</td>
                                    <td>{{ $g->name }}</td>
                                    <td>{{ $g->nama }}</td>
                                    <td>{{ $g->id_kelas }}</td>
                                    <td class="text-end">{{ number_format($g->pcs_akhir, 0) }}</td>
                                    <td class="text-end">{{ number_format($g->gr_akhir, 0) }}</td>
                                    <td class="text-end">{{ number_format($gdng_ctk->pcs_timbang_ulang ?? 0, 0) }}</td>
                                    <td class="text-end">{{ number_format($gdng_ctk->gr_timbang_ulang ?? 0, 0) }}</td>
                                    <td>{{ $g->kerja }}</td>
                                    <td class="text-center"><input type="checkbox" class="nota-checkbox" name="gudang"
                                            value="">
                                    </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

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
