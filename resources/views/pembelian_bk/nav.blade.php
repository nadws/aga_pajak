@php
    $rot = request()
        ->route()
        ->getName();
@endphp
<ul class="nav nav-pills float-start" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $rot == 'pembelian_bk' ? 'active' : '' }}" href="{{ route('pembelian_bk') }}" type="button"
            role="tab" aria-controls="pills-home" aria-selected="true">Pembelian</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $rot == 'buku_campur' ? 'active' : '' }}" href="{{ route('buku_campur') }}" type="button"
            role="tab" aria-controls="pills-home" aria-selected="true">Buku Campur</a>
    </li>

</ul>
