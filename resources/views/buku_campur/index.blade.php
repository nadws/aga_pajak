<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} : {{ tanggal($tgl1) }} ~ {{ tanggal($tgl2) }}</h6>
            </div>
            <div class="col-lg-6">
                <x-theme.btn_filter title="Filter Pembelian Bk" />
                <x-theme.button modal="Y" idModal="import" icon="fas fa-upload" addClass="float-end"
                    teks="Import" />
                <x-theme.akses :halaman="$halaman" route="pembelian_bk" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        @include('pembelian_bk.nav')
        <form action="{{ route('export_buku_campur') }}" method="get">
            <button class="float-end btn btn-primary btn-sm " name="submit" value="approve"><i
                    class="fas fa-check-double"></i>
                Approve</button>
            <button class="float-end btn btn-success btn-sm me-2" name="submit" value="export"><i
                    class="fas fa-file-excel"></i>
                Export</button>
            <br>
            <br>
            <section class="row">
                <div class="col-lg-8"></div>
                <div class="col-lg-4 mb-2">
                    <table class="float-end">
                        <td>Pencarian :</td>
                        <td><input type="text" id="pencarian" class="form-control float-end"></td>
                    </table>


                </div>
                <table class="table table-hover" id="tableSearch" width="100%">
                    <thead>
                        <tr>
                            <th class="dhead" width="5">#</th>
                            <th class="dhead">Tanggal</th>
                            <th class="dhead">No Nota</th>
                            <th class="dhead">No Lot</th>
                            <th class="dhead">Suplier Awal</th>
                            <th class="dhead">Suplier Akhir</th>
                            <th class="dhead" style="text-align: right">Total Harga</th>
                            <th class="dhead" style="text-align: center">Status</th>
                            <th class="dhead" style="text-align: center">Grading</th>
                            <th class="dhead" style="text-align: center">Export <br> <input type="checkbox"
                                    name="" id="checkAll" id="">
                            </th>
                            <th class="dhead" style="text-align: center">Approve <br> <input type="checkbox"
                                    name="" id="checkAll2" id="">
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembelian as $no => $p)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ tanggal($p->tgl) }}</td>
                                <td>{{ $p->no_nota }}</td>
                                <td>{{ $p->no_lot }}</td>
                                <td>{{ ucwords(strtolower($p->nm_suplier)) }}</td>
                                <td>{{ ucwords(strtolower($p->suplier_akhir)) }}</td>
                                <td align="right">Rp.
                                    {{ $p->approve_bk_campur == 'T' ? number_format($p->total_harga, 0) : number_format($p->tl_harga, 0) }}
                                </td>

                                <td align="center">
                                    <span class="badge {{ $p->rupiah == '0' ? 'bg-danger' : 'bg-success' }}">
                                        {!! $p->rupiah == '0' ? 'Harga ?' : 'Harga <i class="fas fa-check"></i>' !!}
                                    </span>
                                </td>
                                <td align="center">
                                    @if (empty($p->nota_grading))
                                        <i class="fas fa-times text-danger"></i>
                                    @else
                                        <a href="#" class="btn btn-sm btn-success grading_nota"
                                            no_nota="{{ $p->no_nota }}" data-bs-toggle="modal"
                                            data-bs-target="#viewgrading"><i class="fas fa-eye"></i></a>
                                    @endif

                                </td>

                                <td style="text-align: center">
                                    <input type="checkbox" name="ceknota[]" class="checkbox-item" id=""
                                        value="{{ $p->no_nota }}">
                                </td>
                                <td style="text-align: center">
                                    @if ($p->rupiah == '0')
                                        <i class="fas fa-times text-danger fa-lg"></i>
                                        <input type="hidden" name="ceknotaapprove[]" class="checkbox-item2"
                                            id="" value="{{ $p->no_nota }}">
                                    @else
                                        @if ($p->approve_bk_campur == 'T')
                                            <input type="checkbox" name="ceknotaapprove[]" class="checkbox-item2"
                                                id="" value="{{ $p->no_nota }}">
                                        @else
                                            <i class="fas fa-check text-success"></i>
                                            <input type="checkbox" hidden name="ceknotaapprove[]" id=""
                                                value="{{ $p->no_nota }}">
                                        @endif
                                    @endif

                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </section>
        </form>

        <form action="{{ route('grading') }}" method="post">
            @csrf
            <x-theme.modal title="Campur BKIN" size="modal-lg" idModal="grading">
                <div id="grading_nota2"></div>
            </x-theme.modal>
        </form>


        <x-theme.modal title="Campur BKIN" size="modal-lg" idModal="viewgrading" btnSave="T">
            <div id="grading_nota"></div>

        </x-theme.modal>
        <form action="{{ route('import_buku_campur') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Import Buku Campur" idModal="import" btnSave="Y">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">File</label>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

            </x-theme.modal>
        </form>

        <form action="{{ route('delete_bk') }}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data
                                </h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="no_nota" name="no_nota">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>





    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                pencarian('pencarian', 'tableSearch')

                $(document).on('click', '.delete_nota', function() {
                    var no_nota = $(this).attr('no_nota');
                    $('.no_nota').val(no_nota);
                })
                $(document).on('click', '.grading_nota', function() {
                    var no_nota = $(this).attr('no_nota');
                    $.ajax({
                        type: "get",
                        url: "/get_grading?no_nota=" + no_nota,
                        success: function(data) {
                            $('#grading_nota').html(data);
                            $('.nota_grading').val(no_nota);
                            $('.nota_grading_text').text(no_nota);
                        }
                    });

                });


                $(document).on('click', '.grading_notatambah', function() {
                    var no_nota = $(this).attr('no_nota');
                    $.ajax({
                        type: "get",
                        url: "/get_grading2?no_nota=" + no_nota,
                        success: function(data) {
                            $('#grading_nota2').html(data);
                            $('.nota_grading').val(no_nota);
                            $('.nota_grading_text').text(no_nota);
                            load_grade(no_nota);
                        }
                    });


                });

                function load_grade(no_nota) {

                    $.ajax({
                        type: "get",
                        url: "{{ route('load_grade') }}",
                        data: {
                            no_nota: no_nota
                        },
                        success: function(r) {
                            $("#load_grade").html(r);
                        }
                    });
                }


                $(document).on('click', '#checkAll', function() {
                    // Setel properti checked dari kotak centang individu sesuai dengan status "cek semua"
                    $('.checkbox-item').prop('checked', $(this).prop('checked'));
                });
                $(document).on('click', '#checkAll2', function() {
                    // Setel properti checked dari kotak centang individu sesuai dengan status "cek semua"
                    $('.checkbox-item2').prop('checked', $(this).prop('checked'));
                });
                $(document).on('click', '.hapus_grade', function() {
                    var id_grade = $(this).attr('id_grade');
                    Swal.fire({
                        title: 'Apakah yakin ingin menghapus?',
                        text: "Data yang dihapus tidak bisa dikembalikan lagi!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batalkan'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "Get",
                                url: "{{ route('delete_tipe_grade') }}",
                                data: {
                                    id_grade: id_grade
                                },
                                success: function(response) {
                                    load_grade();
                                    Toastify({
                                        text: "Data berhasil dihapus",
                                        duration: 3000,
                                        style: {
                                            background: "#EAF7EE",
                                            color: "#7F8B8B"
                                        },
                                        close: true,
                                        avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                                    }).showToast();
                                }
                            });

                        }
                    })

                });


                $(document).on('click', '.btn-save', function() {
                    var nm_grade = $("input[name='nm_grade']").val();
                    if (nm_grade !== "") {
                        $.ajax({
                            url: "{{ route('save_grade') }}",
                            method: "Get",
                            data: {
                                nm_grade: nm_grade
                            },
                            success: function(response) {
                                load_grade();
                                Toastify({
                                    text: "Data berhasil disimpan",
                                    duration: 3000,
                                    style: {
                                        background: "#EAF7EE",
                                        color: "#7F8B8B"
                                    },
                                    close: true,
                                    avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                                }).showToast();
                            },
                            error: function() {
                                Toastify({
                                    text: "Terjadi kesalahan dalam menyimpan data",
                                    duration: 3000,
                                    style: {
                                        background: "#FCEDE9",
                                        color: "#7F8B8B"
                                    },
                                    close: true,
                                    avatar: "https://cdn-icons-png.flaticon.com/512/564/564619.png"
                                }).showToast();
                            }
                        });
                    } else {
                        Toastify({
                            text: "Nama Grade tidak boleh kosong",
                            duration: 3000,
                            position: 'center',
                            style: {
                                background: "#FCEDE9",
                                color: "#7F8B8B"
                            },
                            close: true,
                            avatar: "https://cdn-icons-png.flaticon.com/512/564/564619.png"
                        }).showToast();
                    }
                });

            });
        </script>
    @endsection
</x-theme.app>
