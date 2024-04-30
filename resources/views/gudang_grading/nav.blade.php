<ul class="nav nav-pills float-start" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ Request::route()->getName() == 'gudang_grading.index' ? 'active' : '' }}"
            href="{{ route('gudang_grading.index') }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Siap Grade</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ Request::route()->getName() == 'gudang_grading.selesai' ? 'active' : '' }}"
            href="{{ route('gudang_grading.selesai') }}" type="button" role="tab"
            aria-controls="pills-home" aria-selected="true">Selesai Grade</a>
    </li>
</ul>
