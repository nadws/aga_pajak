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
                <x-theme.button modal="Y" idModal="tambah_data" icon="fas fa-plus" addClass="float-end"
                    teks="Tambah Data Bk" />
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
                    <form action="{{ route('gudangcetak.masuk_bk_grading') }}" method="get">
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
                                        <td class="text-end">{{ number_format($gdng_ctk->pcs_timbang_ulang ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">{{ number_format($gdng_ctk->gr_timbang_ulang ?? 0, 0) }}
                                        </td>
                                        <td>{{ $g->kerja }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="nota-checkbox" name="no_box[]"
                                                value="{{ $g->no_box }}">
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endforeach
                                @foreach ($cetak as $c)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $c->partai_h }}</td>
                                        <td>{{ $c->no_box }}</td>
                                        <td>{{ $c->tipe }}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-end">{{ $c->pcs_cabut }}</td>
                                        <td class="text-end">{{ $c->gr_cabut }}</td>
                                        <td class="text-end">{{ $c->pcs_timbang_ulang }}</td>
                                        <td class="text-end">{{ $c->gr_timbang_ulang }}</td>
                                        <td>Suntik</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="nota-checkbox" name="no_box[]"
                                                value="{{ $c->no_box }}">
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>

            </div>

        </section>
        {{-- </form> --}}
        <form action="{{ route('gudangcetak.import_bk_ctk') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Import Cetak" idModal="importcetak" btnSave="Y">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">File</label>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

            </x-theme.modal>
        </form>

        <form action="{{ route('gudangcetak.save_cetak') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah Data Cetak" idModal="tambah_data" btnSave="Y" size="modal-lg-max">
                <div class="row">
                    <div class="col-lg-2">
                        <label for="">Partai</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">No Box</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">Tipe</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">Pcs</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">Gr</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Ttl Rp</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Cost Cabut</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">Aksi</label> <br>
                    </div>
                    <div class="col-lg-2 mt-2">
                        <input type="text" class="form-control" name="partai[]">
                    </div>
                    <div class="col-lg-2 mt-2">
                        <input type="text" class="form-control" name="no_box[]">
                    </div>
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="tipe[]">
                    </div>
                    {{-- <div class="col-lg-1 mt-2">
                    <input type="text" class="form-control" name="grade[]">
                </div> --}}
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="pcs[]">
                    </div>
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="gr[]">
                    </div>
                    <div class="col-lg-2 mt-2">
                        <input type="text" class="form-control" name="ttl_rp[]">
                    </div>
                    <div class="col-lg-2 mt-2">
                        <input type="text" class="form-control" name="cost_cabut[]">
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-sm btn-primary tambah_row"><i class="fas fa-plus"></i></button>
                    </div>

                    <div class="load_row_tambah"></div>
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

                var count = 1;
                $(document).on('click', '.tambah_row', function(e) {
                    e.preventDefault();
                    count = count + 1;
                    $.ajax({
                        type: "get",
                        url: "{{ route('halawal.load_row_cetak') }}",
                        data: {
                            count: count
                        },
                        success: function(response) {
                            $('.load_row_tambah').append(response);
                        }
                    });
                });

                $(document).on('click', '.delete_row', function(e) {
                    e.preventDefault();
                    var count = $(this).attr('count');
                    $(".baris" + count).remove();
                });
            });
        </script>
    @endsection
</x-theme.app>
