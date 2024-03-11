<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont="container-fluid">

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
        <style>
            .kartu:hover {
                background-color: lightblue;
                /* Warna latar belakang saat dihover */
            }

            .loading {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border: 6px solid #f3f3f3;
                border-top: 6px solid #3498db;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>

        <div class="row">
            <div class="col-lg-9">
                @include('gudang_bk.nav')
            </div>
            <div class="col-lg-12">
                <div id="load_data"></div>
                <div class="loading" style="display: none">

                </div>
            </div>
            <div class="col-lg-4 mt-4 card-pilihan">
                <a href="#" class="opencabut" gudang='wip'>
                    <div class="card kartu" style="border: 1px solid blue; ">
                        <div class="card-body">
                            <div class="text-center mb-4">

                                <img src="{{ asset('img/evaluation.png') }}" alt="" width="120px"
                                    class="mx-auto">
                            </div>
                            <h5 class="text-center">Cabut</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 mt-4 card-pilihan">
                <a href="#" class="opencabut" gudang='wipcetak'>
                    <div class="card kartu" style="border: 1px solid blue; ">
                        <div class="card-body">
                            <div class="text-center mb-4">

                                <img src="{{ asset('img/evaluation.png') }}" alt="" width="120px"
                                    class="mx-auto">
                            </div>
                            <h5 class="text-center">Cetak</h5>
                        </div>
                    </div>
                </a>
            </div>
            {{-- <div class="col-lg-4 mt-4 card-pilihan">
                <a href="#" class="opencabut" gudang='wipsortir'>
                    <div class="card kartu" style="border: 1px solid blue; ">
                        <div class="card-body">
                            <div class="text-center mb-4">

                                <img src="{{ asset('img/evaluation.png') }}" alt="" width="120px"
                                    class="mx-auto">
                            </div>
                            <h5 class="text-center">Sortir</h5>
                        </div>
                    </div>
                </a>
            </div> --}}
        </div>

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
        <form action="{{ route('gudangBk.import_wip_cetak') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Gudang Wip Cetak" idModal="importcetak" btnSave="Y">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">File</label>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

            </x-theme.modal>
        </form>

        <form action="{{ route('gudangBk.save_bj_baru') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah BJ Cetak" idModal="bkcetakawal" size="modal-lg-max" btnSave="Y">
                <div class="row">
                    <div class="col-lg-1">
                        <label for="">Partai</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">No Box</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">Tipe</label>
                    </div>
                    <div class="col-lg-1">
                        <label for="">Grade</label>
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
                    <div class="col-lg-2">
                        <label for="">Aksi</label> <br>
                    </div>
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="partai[]">
                    </div>
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="no_box[]">
                    </div>
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="tipe[]">
                    </div>
                    <div class="col-lg-1 mt-2">
                        <input type="text" class="form-control" name="grade[]">
                    </div>
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
                    <div class="col-lg-2">
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

                function getWip(nm_gudang) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('gudangBk.wip') }}",
                        data: {
                            nm_gudang: nm_gudang
                        },
                        success: function(response) {
                            setTimeout(function() {
                                $('.loading').hide();
                                $('#load_data').html(response);
                            }, 1000);

                        }
                    });
                }

                $('.opencabut').click(function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('gudang');
                    $('.card-pilihan').hide();
                    $('.loading').show();
                    getWip(nm_gudang)

                });
                $(document).on('click', '.kembali', function(e) {
                    e.preventDefault();
                    $('.loading').show();
                    $('#load_data').html('');
                    setTimeout(function() {
                        $('.loading').hide();
                        $('.card-pilihan').show();
                    }, 500);


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
                $(document).on('submit', '#save_pindah_gudang', function(e) {
                    e.preventDefault();
                    var idNota = [];
                    var idNotaCetak = [];
                    $('.nota-checkbox').each(function() {
                        if ($(this).is(':checked')) {
                            idNota.push($(this).val());
                        } else {
                            idNotaCetak.push($(this).val());
                        }
                    });


                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('gudangBk.pindah_gudang') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            id_nota: idNota,
                            id_nota_cetak: idNotaCetak,
                        },
                        success: function(response) {
                            $('.card-pilihan').hide();
                            $('#load_data').html('');
                            $('.loading').show();
                            getWip('wipcetak')
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle kesalahan jika ada
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endsection
</x-theme.app>
