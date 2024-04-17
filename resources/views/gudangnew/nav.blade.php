<ul class="nav nav-pills float-start" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'bk' ? 'active' : '' }}"
            href="{{ route('gudangnew.index', ['nm_gudang' => 'bk']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Gudang Bk</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'produksi' ? 'active' : '' }}"
            href="{{ route('gudangnew.index', ['nm_gudang' => 'produksi']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Rencana Produksi</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'reject' ? 'active' : '' }}"
            href="{{ route('gudangnew.index', ['nm_gudang' => 'reject']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Gudang Reject</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'summary_produksi' ? 'active' : '' }}"
            href="{{ route('gudangnew.index', ['nm_gudang' => 'summary_produksi']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Campur Produksi</a>
    </li>

</ul>
