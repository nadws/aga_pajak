<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">

                {{-- <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" /> --}}
                <form action="{{ route('gudangBk.export_buku_campur_bk') }}" method="post">
                    @csrf
                    <button class="btn btn-success float-end me-2"><i class="fas fa-file-excel"></i> Export</button>
                </form>
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
            <div class="col-lg-12 mt-2">

                <table class="table table-hover table-bordered" id="table" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Partai</th>
                            <th>No Box</th>
                            <th>Tipe</th>
                            <th>Ket</th>
                            <th>Warna</th>
                            <th>Tgl Terima</th>
                            <th>Pengawas</th>
                            <th>Nama Anak</th>
                            <th>Kelas</th>
                            <th class="text-end">Pcs</th>
                            <th class="text-end">Gr</th>
                            <th>Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabut as $no => $g)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $g->nm_partai }}</td>
                                <td>{{ $g->no_box }}</td>
                                <td>{{ $g->tipe }}</td>
                                <td>{{ $g->ket }}</td>
                                <td>{{ $g->warna }}</td>
                                <td>{{ tanggal($g->tgl_terima) }}</td>
                                <td>{{ $g->pengawas }}</td>
                                <td>{{ $g->nama_anak }}</td>
                                <td>{{ $g->kelas }}</td>
                                <td class="text-end">{{ $g->pcs_awal }}</td>
                                <td class="text-end">{{ $g->gr_awal }}</td>
                                <td>{{ $g->kategori }}</td>
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
