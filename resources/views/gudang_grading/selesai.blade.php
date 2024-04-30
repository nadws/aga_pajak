<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <style>
            /* width */
            ::-webkit-scrollbar {
                width: 10px;
            }

            /* Track */
            ::-webkit-scrollbar-track {
                box-shadow: inset 0 0 5px grey;
                border-radius: 10px;
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
                background: #257687;
                border-radius: 2px;
            }
        </style>
        <div class="row justify-content-start">
                <div class="d-flex justify-content-between">
                    <h6 class="mt-1">{{ $title }} </h6>
                    <a href="{{ route('gudang_grading.export') }}" class="btn btn-sm btn-success float-end me-2"><i
                            class="fas fa-file-excel"></i> Export</a>
                </div>
        </div>
        @include('gudang_grading.nav')
    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Siap Grading Awal</h6>
                </div>
                <input type="text" class="mt-3 form-control form-control-sm mb-2" placeholder="pencarian"
                    id="pencarian">
                <div style="overflow-x: hidden; height: 400px">
                    <table id="tableSearch" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="dhead">Grade</th>
                                <th class="dhead text-end">Pcs</th>
                                <th class="dhead text-end">Gr</th>
                                <th class="dhead text-end">Rp Gr</th>
                                <th class="dhead text-end">Ttl Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gudangbj as $no => $g)
                                <tr>
                                    <td>{{ $g->grade }}</td>
                                    <td class="text-end">{{ $g->pcs - $g->pcs_kredit }}</td>
                                    <td class="text-end">{{ $g->gr - $g->gr_kredit }}</td>
                                    <td class="text-end">
                                        {{ number_format(($g->ttl_rp - $g->ttl_rp_kredit) / ($g->gr - $g->gr_kredit), 0) }}
                                        {{-- {{ $g->ttl_rp - $g->ttl_rp_kredit }}
                                        {{ $g->gr - $g->gr_kredit }} --}}
                                    </td>
                                    <td class="text-end">{{ number_format($g->ttl_rp - $g->ttl_rp_kredit, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Sudah Jadi Box Kecil</h6>
                </div>
                <input type="text" class="mt-3 form-control form-control-sm mb-2" placeholder="pencarian"
                    id="pencarian2">
                <div style="overflow-x: hidden; height: 400px">
                    <table id="tableSearch2" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="dhead">Grade</th>
                                <th class="dhead">No Box</th>
                                <th class="dhead text-end">Pcs</th>
                                <th class="dhead text-end">Gr</th>
                                <th class="dhead text-end">Rp Gr</th>
                                <th class="dhead text-end">Ttl Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historyBoxKecil as $no => $g)
                                <tr>
                                    <td>{{ $g->grade }}</td>
                                    <td>{{ $g->no_box }}</td>
                                    <td class="text-end">{{ $g->pcs }}</td>
                                    <td class="text-end">{{ $g->gr }}</td>
                                    <td class="text-end">
                                        {{ number_format($g->rp_gram, 0) }}
                                        {{-- {{ $g->ttl_rp - $g->ttl_rp_kredit }}
                                        {{ $g->gr - $g->gr_kredit }} --}}
                                    </td>
                                    <td class="text-end">{{ number_format($g->gr * $g->rp_gram, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                pencarian('pencarian', 'tableSearch')
                pencarian('pencarian2', 'tableSearch2')
            });
        </script>
    @endsection
</x-theme.app>
