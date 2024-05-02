<x-theme.app title="Data" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">Data </h6>
            </div>
            <div class="col-lg-6">
                <a class="btn btn-primary float-end" href=""><i class="fas fa-plus"> </i> Data</a>
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <table class="table table-hover table-bordered" id="table" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Nota</th>
                    <th>Tanggal</th>
                    <th>Suplier</th>
                    <th class="text-end">Gr</th>
                    <th class="text-end">Rp Gr</th>
                    <th class="text-end">Ttl Rp</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bk as $no => $b)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $b->nota_bk }}</td>
                        <td>{{ tanggal($b->tanggal) }}</td>
                        <td>{{ $b->suplier_akhir }}</td>
                        <td class="text-end">{{ round($b->gr_beli, 0) }}</td>
                        <td class="text-end">{{ number_format($b->harga, 0) }}</td>
                        <td class="text-end">{{ number_format($b->harga * $b->gr_beli, 0) }}</td>
                        <td class="text-center">
                            <a href="" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                            <a target="_blank" class="btn btn-primary btn-sm"
                                href="{{ route('bahanbaku.print_nota', ['id_bkin' => $b->id_bkin]) }}"><i
                                    class="fas fa-print"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-slot>


</x-theme.app>
