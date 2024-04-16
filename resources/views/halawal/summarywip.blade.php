<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont="container-fluid">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <style>
            .kartu:hover {
                background-color: lightblue;
                /* Warna latar belakang saat dihover */
            }

            .loadingbk {
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
            <div class="col-lg-12 punya_cetak" style="display: none">
                <div class="row">
                    <div class="col-lg-12">

                        <hr>
                    </div>
                    <div class="col-lg-3">

                    </div>
                    <div class="col-lg-9 mb-2">
                        <a href="{{ route('sumsortir.export_opname_cetak') }}" class="btn btn-success float-end"><i
                                class="fas fa-file-excel"></i> Export</a>
                    </div>
                    <div class="col-lg-3">
                        <button class="btn btn-warning kembali">Kembali</button>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="text-center">{{ $title }}</h5>
                    </div>
                    <div class="col-lg-3">
                        <table class="float-end">
                            <td>Search :</td>
                            <td><input type="text" class="form-control float-end pencarian_cetak"></td>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div id="load_data"></div>
                <div class="loadingbk" style="display: none">

                </div>
            </div>
            <div class="col-lg-4 mt-4 card-pilihan">
                <a href="#" class="opencabut" gudang='{{ $nm_gudang }}' lokasi="wip">
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
                <a href="#" class="opencabut" gudang='{{ $nm_gudang }}' lokasi="wipcetak">
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
                <a href="#" class="opencabut" gudang='{{ $nm_gudang }}' lokasi="wipsortir">
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

        <x-theme.modal title="Data Bk Cabut" idModal="load_bk_cabut" btnSave="T" size="modal-lg-max">
            <button class="btn btn-primary btn-loading" type="button" disabled="">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <div class="load_box"></div>
        </x-theme.modal>

        <x-theme.modal title="Data Bk Cabut" idModal="load_bk_sortir" btnSave="T" size="modal-lg-max">
            <button class="btn btn-primary btn-loading" type="button" disabled="">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <div class="load_box_sortir"></div>
        </x-theme.modal>

        {{-- SELESAI --}}
        <form id="save_selesai">
            @csrf
            <x-theme.modal title="Selesai Bk" idModal="load_bk_selesai" btnSave="Y" size="modal-lg-max">

                <div class="load_seleai_box"></div>
            </x-theme.modal>
        </form>

        {{-- SELESAI FINISH --}}
        <form id='selesai_bk'>
            <div class="modal fade" id="load_bk_finish" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-success ms-4 mt-4"><i class="fas fa-check-square"></i> Selesaikan Data
                                </h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menyelesaikan data ?</p>
                                <input type="hidden" class="nm_partai" name="nm_partai">
                                <input type="hidden" class="lokasi" name="lokasi">
                                <input type="hidden" class="gudang" name="gudang" value="{{ $nm_gudang }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Selesai</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- History --}}
        <style>
            .modal-lg-max-lg {
                max-width: 1400px;
            }
        </style>
        <x-theme.modal title="History WIP" idModal="load_history" btnSave="T" size="modal-lg-max-lg">
            <div class="history_wip"></div>
        </x-theme.modal>


    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                pencarian('pencarian', 'tableSearch')
                $(document).on('click', '.opencabut', function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('gudang');
                    var lokasi = $(this).attr('lokasi');


                    if (lokasi == 'wip') {
                        var url = "{{ route('summarybk.sum_bagi') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else if (lokasi == 'wipsortir') {
                        var url = "{{ route('sumsortir.index') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else if (lokasi == 'wipcetak') {

                        var url = "{{ route('sumsortir.cetak2') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                        $('.punya_cetak').show();

                    } else {
                        alert('sedang terjadi masalah, masih dalam perbaikan')
                        exit();
                    }
                    load_data(url, nm_gudang, lokasi);



                });

                function load_data(url, nm_gudang, lokasi) {
                    $.ajax({
                        type: "get",
                        url: url,
                        data: {
                            nm_gudang: nm_gudang,
                            lokasi: lokasi,
                        },
                        success: function(response) {
                            $('.loadingbk').hide();
                            $('#load_data').html(response);
                        }
                    });
                }
                $(document).on('click', '.kembali', function(e) {
                    e.preventDefault();
                    $('.loadingbk').show();
                    $('.punya_cetak').hide();
                    $('#load_data').html('');
                    setTimeout(function() {
                        $('.loadingbk').hide();
                        $('.card-pilihan').show();
                    }, 500);
                });
                $(document).on('click', '.finish', function(e) {
                    e.preventDefault();
                    var lokasi = $(this).attr('lokasi');
                    var nm_partai = $(this).attr('nm_partai');

                    $('.lokasi').val(lokasi);
                    $('.nm_partai').val(nm_partai);


                });
                $(document).on('submit', '#selesai_bk', function(e) {
                    e.preventDefault();
                    var nm_gudang = $('.nm_gudang').val();
                    var lokasi = $('.lokasi').val();
                    var nm_partai = $('.nm_partai').val();

                    if (lokasi == 'wip') {
                        var url = "{{ route('summarybk.sum_bagi') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else if (lokasi == 'wipsortir') {
                        var url = "{{ route('sumsortir.index') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else if (lokasi == 'wipcetak') {
                        var url = "{{ route('sumsortir.cetak') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();

                    } else {
                        alert('sedang terjadi masalah, masih dalam perbaikan')
                        exit();
                    }
                    $('.loadingbk').show();
                    $('#load_data').html('');

                    $('#load_bk_finish').modal('hide');

                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.selesai_partai') }}",
                        data: {
                            nm_partai: nm_partai
                        },
                        success: function(response) {
                            Toastify({
                                text: "Data berhasil diselesaikan",
                                duration: 3000,
                                style: {
                                    background: "#EAF7EE",
                                    color: "#7F8B8B"
                                },
                                close: true,
                                avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                            }).showToast();
                            load_data(url, nm_gudang, lokasi);
                        }
                    });

                });

                $(document).on('submit', '#save_selesai  ', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    var nm_gudang = $('.nm_gudang').val();
                    var lokasi = $('.lokasi').val();


                    if (lokasi == 'wip') {
                        var url = "{{ route('summarybk.sum_bagi') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else if (lokasi == 'wipsortir') {
                        var url = "{{ route('sumsortir.index') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else if (lokasi == 'wipcetak') {
                        var url = "{{ route('sumsortir.cetak') }}";
                        $('.card-pilihan').hide();
                        $('.loadingbk').show();
                    } else {
                        alert('sedang terjadi masalah, masih dalam perbaikan')
                        exit();
                    }

                    $('#load_data').html('');
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('sumsortir.save_selesai') }}", // Replace with your actual backend endpoint
                        data: formData,
                        success: function(response) {

                            load_data(url, nm_gudang, lokasi);
                            $("#load_bk_selesai").modal('hide');
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
                        error: function(error) {
                            // Handle error response, if needed
                            console.log(error);
                            load_data(url, nm_gudang, lokasi);
                            alert('Error submitting form');
                        },
                        complete: function() {
                            // Hide loading spinner or any UI indication if needed
                        }
                    });
                });


            });
            $(document).ready(function() {

                $('.spinerLot').addClass('d-none');
                $(document).on('click', '.show_lot', function(e) {
                    e.preventDefault();
                    var nm_partai = $(this).attr('partai');
                    var no = $(this).attr('no');
                    $('.loadLotLoading' + no).removeClass('d-none');
                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.get_no_lot') }}",
                        data: {
                            nm_partai: nm_partai
                        },
                        success: function(response) {
                            $('.loadLotLoading' + no).addClass('d-none');
                            $('.load_lot' + no).html(response);
                            $(".show" + no).hide();
                            $(".hide" + no).show();
                            $(".hide" + no).removeAttr("hidden");
                        }
                    });

                });
                $(document).on('click', '.hide_lot', function(e) {
                    e.preventDefault();
                    var no = $(this).attr('no');
                    $('.load_lot' + no).html('');
                    $(".hide" + no).hide();
                    $(".show" + no).show();


                });
                var currentNoLot = '';
                var currentNmPartai = '';

                $(document).on('click', '.show_box', function(e) {
                    e.preventDefault();
                    $('.btn-loading').removeClass('d-none');

                    var no_lot = $(this).attr('no_lot');
                    var nm_partai = $(this).attr('nm_partai');
                    currentNoLot = no_lot;
                    currentNmPartai = nm_partai;
                    $('.no_lot_input').val(no_lot);
                    $('.nm_partai_input').val(nm_partai);
                    console.log(no_lot);
                    console.log(nm_partai);
                    $('.load_box').html('');
                    loadBoxData(no_lot, nm_partai, 5); // Default limit 5
                });
                $(document).on('click', '.show_box_sortir', function(e) {
                    e.preventDefault();
                    $('.btn-loading').removeClass('d-none');
                    var nm_partai = $(this).attr('nm_partai');
                    currentNmPartai = nm_partai;
                    $('.nm_partai_input').val(nm_partai);
                    $('.load_box_sortir').html('');
                    loadBoxDataSortir(nm_partai, 5); // Default limit 5
                });

                $(document).on('change', '.load-data', function() {
                    $('.btn-loading').removeClass('d-none');
                    var val = $(this).val();
                    $('.load_box').html('');
                    loadBoxData(currentNoLot, currentNmPartai, val);
                    $(this).val(val);
                });

                function loadBoxData(no_lot, nm_partai, limit) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.get_no_box') }}",
                        data: {
                            no_lot: no_lot,
                            nm_partai: nm_partai,
                            limit: limit // Menambahkan parameter limit ke dalam data yang dikirimkan
                        },
                        success: function(response) {
                            $('.btn-loading').addClass('d-none');
                            $('.load_box').html(response);
                            pencarian('pencarianBox', 'tblAldi2')

                        }
                    });
                }

                function loadBoxDataSortir(nm_partai, limit) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('sumsortir.get_no_box_sortir') }}",
                        data: {
                            nm_partai: nm_partai,
                            limit: limit // Menambahkan parameter limit ke dalam data yang dikirimkan
                        },
                        success: function(response) {
                            $('.btn-loading').addClass('d-none');
                            $('.load_box_sortir').html(response);
                            pencarian('pencarianBox', 'tblAldi2')

                        }
                    });
                }
                $(document).on('click', '.show_td', function(e) {
                    e.preventDefault();
                    $('.tdhide').show();
                    $('.show_td').hide();

                });
                $(document).on('click', '.hide_td', function(e) {
                    e.preventDefault();
                    $('.tdhide').hide();
                    $('.show_td').show();

                });

                $(document).on('click', '.selesai_box', function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('gudang');
                    var lokasi = $(this).attr('lokasi');
                    var nm_partai = $(this).attr('nm_partai');
                    $.ajax({
                        type: "get",
                        url: "{{ route('sumsortir.load_box_selesai') }}",
                        data: {
                            nm_gudang: nm_gudang,
                            nm_partai: nm_partai,
                            lokasi: lokasi,
                        },
                        success: function(response) {
                            $(".load_seleai_box").html(response);
                        }
                    });

                });

                $(document).on('keyup', '.gram', function() {
                    var gr = $(this).val();
                    var rp_satuan = $('.rp_satuan').val()

                    var total = parseFloat(gr) * parseFloat(rp_satuan);
                    total = Math.floor(total);
                    var totalRupiah = total.toLocaleString("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 0,
                    });

                    $('.ttlrp').val(totalRupiah);
                });



                $(document).on('change', '.pindah', function() {
                    if ($(this).prop('checked')) {
                        $('.form-pindah').show();
                    } else {
                        $('.form-pindah').hide();
                    }
                });

                $(document).on('click', '.history', function() {
                    var kategori = 'history';
                    var lokasi = $(this).attr('lokasi');

                    if (lokasi == 'cabut') {
                        var url = "{{ route('summarybk.sum_bagi') }}";
                    } else if (lokasi == 'sortir') {
                        var url = "{{ route('sumsortir.index') }}";
                    } else if (lokasi == 'cetak') {
                        var url = "{{ route('sumsortir.cetak') }}";
                    }


                    $.ajax({
                        type: "get",
                        url: url,
                        data: {
                            kategori: kategori
                        },
                        success: function(response) {
                            $('.history_wip').html(response);
                        }
                    });
                });

                $("body").on("click", ".pagination a", function(e) {
                    e.preventDefault();

                    var page = $(this).attr("href").split("page=")[1];

                    var search = $('.pencarian_cetak').val();
                    load_cetak(page, search);
                });

                $(document).on("keyup", ".pencarian_cetak", function() {
                    var search = $(this).val();
                    var page = (1);
                    load_cetak(page, search);
                });

                function load_cetak(page, search) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('sumsortir.cetak2') }}",
                        data: {
                            page: page,
                            search: search,
                        },
                        success: function(response) {
                            $('#load_data').html(response);
                        }
                    });
                }
            });
        </script>
    @endsection
</x-theme.app>
