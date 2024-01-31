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
            <div class="col-lg-12">
                <div id="load_data"></div>
                <div class="loadingbk" style="display: none">

                </div>
            </div>
            <div class="col-lg-4 mt-4 card-pilihan">
                <a href="#" class="opencabut" lokasi="wip">
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
                <a href="#" class="opencabut" lokasi="wipcetak">
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
                <a href="#" class="opencabut" lokasi="wipsortir">
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

    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {


                function load_susut(nm_gudang) {
                    $('.card-pilihan').hide();
                    $('.loadingbk').show();

                    $.ajax({
                        type: "get",
                        url: "{{ route('sumsortir.susut_wip_cabut') }}",
                        data: {
                            nm_gudang: nm_gudang,
                        },
                        success: function(response) {

                            $('.loadingbk').hide();
                            $('#load_data').html(response);

                        }
                    });
                }
                $(document).on('click', '.opencabut', function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('lokasi');

                    load_susut(nm_gudang);

                });
                $(document).on('click', '.kembali', function(e) {
                    e.preventDefault();
                    $('.loadingbk').show();
                    $('#load_data').html('');
                    setTimeout(function() {
                        $('.loadingbk').hide();
                        $('.card-pilihan').show();
                    }, 500);


                });
                $(document).on('click', '.selesai_susut', function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('gudang');
                    var ket = $(this).attr('ket');

                    $('.loadingbk').show();
                    $('#load_data').html('');
                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.selesai_susut') }}",
                        data: {
                            nm_gudang: nm_gudang,
                            ket: ket,
                        },
                        success: function(response) {

                            load_susut(nm_gudang);
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

                        }
                    });


                });
                $(document).on('click', '.cancel_susut', function(e) {
                    e.preventDefault();
                    var nm_gudang = $(this).attr('gudang');
                    var ket = $(this).attr('ket');

                    $('.loadingbk').show();
                    $('#load_data').html('');
                    $.ajax({
                        type: "get",
                        url: "{{ route('summarybk.cancel_susut') }}",
                        data: {
                            nm_gudang: nm_gudang,
                            ket: ket,
                        },
                        success: function(response) {

                            load_susut(nm_gudang);
                            Toastify({
                                text: "Data berhasil dicancel",
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


                });
                $(document).on('submit', '#save_susut', function(e) {
                    e.preventDefault();

                    // Display loading spinner or any UI indication if needed

                    // Serialize form data
                    var formData = $(this).serialize();
                    var nm_gudang = $('.gudang').val();

                    // Make an Ajax request
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('summarybk.save_susut') }}", // Replace with your actual backend endpoint
                        data: formData,
                        success: function(response) {
                            // Handle success response, if needed
                            console.log(response);
                            load_susut(nm_gudang);
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
                            load_susut(nm_gudang);
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








            });
        </script>
    @endsection
</x-theme.app>
