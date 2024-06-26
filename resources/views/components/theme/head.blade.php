@props(['title' => 'Laravel'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    {{--
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/main/app.css">
    <link rel="shortcut icon" href="/assets/login/img/empat.svg" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('theme') }}/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/pages/fontawesome.css">
    <link rel="stylesheet"
        href="{{ asset('theme') }}/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap6.min.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/pages/datatables.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/widgets/todo.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/dragula/dragula.min.css">


    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- my css --}}

    {{--
    <link rel="stylesheet" href="{{ asset('mycss.css') }}"> --}}
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        .table-container {
            max-height: 500px;
            /* Atur tinggi maksimum sesuai kebutuhan */
            overflow-y: scroll;
            /* Aktifkan scroll vertikal jika perlu */
        }

        #tableSearch thead {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 1010;
        }

        #tableSearch tfoot {
            position: sticky;
            bottom: 0;
            background-color: #fff;
            z-index: 1010;
        }


        .table-container::-webkit-scrollbar {
            width: 6px;
            /* Atur lebar scrollbar sesuai keinginan */
        }

        /* Warna latar belakang scrollbar */
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Warna scrollbar itu sendiri */
        .table-container::-webkit-scrollbar-thumb {
            background: #8c8989;
        }

        /* Warna scrollbar saat dihover */
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #8c8989;
        }

        .dhead {
            background-color: #8c8989 !important;
            color: white;
            font-weight: normal;
            font-size: 14px;
        }

        .dhead_second {
            background-color: #757474 !important;
            color: white;
            font-weight: normal;
            font-size: 14px;
        }

        #image-preview img {
            width: 200px;
            height: 200px;
        }

        .modal-lg-max {
            max-width: 1200px;
        }

        .select2 {
            width: 100% !important;

        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid rgb(183, 182, 182);
            border-radius: 4px;
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000;
            line-height: 36px;
            /* font-size: 12px; */


        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
            position: absolute;
            top: 1px;
            right: 1px;
            width: 20px;
        }

        label {
            font-size: 13px;
            color: #7F8BA2;
            margin-bottom: 10px;
        }

        input:read-only {
            background-color: #E9ECEF;
        }

        input:active {
            background-color: #E9ECEF;
        }

        .active_navbar_new {
            text-decoration: underline;
            color: #ffffff !important;
        }

        .active-nvs {
            text-decoration: underline;
            color: #435EBE !important;
        }

        .card-hover .card-front {
            position: relative;
            z-index: 2;
            transition: transform .5s;
        }

        .card-hover .card-back {
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            transform: rotateY(180deg);
            transition: transform .5s, opacity .5s;
        }

        .card-hover:hover .card-front {
            transform: rotateY(180deg);
        }

        .card-hover:hover .card-back {

            opacity: 1;
            transform: rotateY(0deg);
        }

        .card-icon i {
            transition: all 0.3s ease-in-out;
        }

        .card:hover .card-icon i {
            transform: rotate(360deg);
        }

        .card:hover .card-title {
            opacity: 0;
        }

        .card:hover .card-text {
            opacity: 1;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3.5rem);
        }

        .layout-horizontal .header-top .logo img {
            height: 50px;
        }

        .modal-dialog-centered {
            justify-content: center;
        }

        .img-detail {
            width: 50%;
            height: 350px;
            object-fit: cover;
        }

        .nowrap {
            white-space: nowrap;
        }

        th {
            background-color: #8c8989;

        }

        table.dataTable thead>tr>th.sorting,
        table.dataTable thead>tr>th.sorting_asc,
        table.dataTable thead>tr>th.sorting_desc,
        table.dataTable thead>tr>th.sorting_asc_disabled,
        table.dataTable thead>tr>th.sorting_desc_disabled,
        table.dataTable thead>tr>td.sorting,
        table.dataTable thead>tr>td.sorting_asc,
        table.dataTable thead>tr>td.sorting_desc,
        table.dataTable thead>tr>td.sorting_asc_disabled,
        table.dataTable thead>tr>td.sorting_desc_disabled {
            cursor: pointer;
            position: relative;
            padding-right: 26px;
            background-color: #8c8989;
            color: white;
        }
    </style>
    @yield('styles')

    <livewire:styles />
    <livewire:scripts />
</head>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
