<ul class="nav nav-pills float-start" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'bk' ? 'active' : '' }}"
            href="{{ route('gudangBk.index', ['nm_gudang' => 'bk']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Gudang Bk</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'produksi' ? 'active' : '' }}"
            href="{{ route('gudangBk.index', ['nm_gudang' => 'produksi']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Gudang Produksi</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'reject' ? 'active' : '' }}"
            href="{{ route('gudangBk.index', ['nm_gudang' => 'reject']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Gudang Reject</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $nm_gudang == 'wip' ? 'active' : '' }}"
            href="{{ route('gudangBk.index', ['nm_gudang' => 'wip']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Gudang Wip</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ request()->routeIs('summarybk.index') && request()->query('nm_gudang') == 'summary' ? 'active' : '' }}"
            href="{{ route('summarybk.index', ['nm_gudang' => 'summary']) }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Summary Wip</a>
    </li>

</ul>
