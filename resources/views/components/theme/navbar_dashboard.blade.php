<header class="mb-2">
    @include('components.theme.header2')

    <style>
        .layout-horizontal .main-navbar ul .menu-link {
            color: white !important;
            padding: 1rem 0;
        }
    </style>
    @php
        $navbar = DB::table('navbar')->orderBy('urutan', 'ASC')->get();
    @endphp
    <nav class="main-navbar " style="display: block;">
        <div class="container font-bold">
            <ul>
                <li class="menu-item">
                    <a href="dashboard"
                        class='menu-link {{ request()->route()->getName() == 'dashboard' ? 'active_navbar_new' : '' }}'>
                        <span>Dashboard</span>
                    </a>
                </li>

                @foreach ($navbar as $d)
                    @php
                        $string = $d->isi;
                        $string = str_replace(['[', ']', "'"], '', $string);
                        $array = explode(', ', $string);
                    @endphp
                    <li class="menu-item">
                        <a href="{{ route($d->route) }}"
                            class='menu-link 
                    {{ in_array(request()->route()->getName(), $array) ? 'active_navbar_new' : '' }}'>
                            <span>{{ ucwords($d->nama) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

</header>
