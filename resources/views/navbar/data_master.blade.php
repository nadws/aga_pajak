<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">
        <div class="row">
            @foreach ($data as $d)
                <div class="col-lg-3 col-6">
                    <a href="{{ route($d['route']) }}">
                        <div style="cursor:pointer;background-color: #8ca3f3" class="card border card-hover text-white">
                            <div class="card-front">
                                <div class="card-body">
                                    <h6 class="card-title text-white text-center" style="white-space: nowrap;"><img
                                            src="/img/{{ $d['img'] }}" width="110" alt=""><br><br>
                                        {{ ucwords($d['judul']) }}
                                    </h6>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="card-body">
                                    <p class=" text-white">{{ ucwords($d['judul']) }}</p>

                                    <p class="card-text">{{ ucwords($d['deskripsi']) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

    </x-slot>

</x-theme.app>
