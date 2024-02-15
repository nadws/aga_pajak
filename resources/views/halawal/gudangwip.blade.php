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
            <div class="col-lg-4 mt-4 card-pilihan">
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
            </div>
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

    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                pencarian('pencarian', 'tableSearch')
                $(document).on('click', '#checkAll', function() {
                    // Setel properti checked dari kotak centang individu sesuai dengan status "cek semua"
                    $('.checkbox-item').prop('checked', $(this).prop('checked'));
                });

                $('.opencabut').click(function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('gudang');
                    $('.card-pilihan').hide();
                    $('.loading').show();
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
            });
        </script>
    @endsection
</x-theme.app>
