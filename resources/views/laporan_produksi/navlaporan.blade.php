@php
    $rot = request()->route()->getName();
@endphp

<ul class="nav nav-pills float-start" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $rot == 'gudangnew.laporan_produksi' ? 'active' : '' }}"
            href="{{ route('gudangnew.laporan_produksi') }}" type="button" role="tab" aria-controls="pills-home"
            aria-selected="true">Campur Produksi</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $rot == 'gudangnew.laporan_box_produksi' ? 'active' : '' }}"
            href="{{ route('gudangnew.index', ['nm_gudang' => 'bk']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Box Produksi</a>
    </li>
</ul>
