<div class="row">
    <div class="col-lg-12">
        <hr>
    </div>
    <div class="col-lg-3">
        <h5>{{ $title }}</h5>
    </div>
    <div class="col-lg-9"></div>
    <div class="col-lg-3">
        <button class="btn btn-warning kembali">Kembali</button>
    </div>
    <div class="col-lg-6">

    </div>
    <div class="col-lg-3">
        <table class="float-end">
            <tr>
                <td></td>
                <td>
                    <button class="btn float-end btn-primary btn-sm history" lokasi='sortir' data-bs-toggle="modal"
                        data-bs-target="#load_history"><i class="fas fa-history"></i>
                        History
                    </button>
                </td>
            </tr>
            <tr>
                <td>Search :</td>
                <td><input type="text" id="pencarian" class="form-control float-end"></td>
            </tr>
        </table>
    </div>

    <style>
        .tdhide {
            display: none;
            overflow: hidden;
        }
    </style>
    <div class="col-lg-12 mt-2">
        <div class="table-container table-responsive">
            <table class="table table-hover table-bordered" id="tableSearch" width="100%">
                <thead>
                    <tr>
                        <th class="dhead text-center" colspan="4">Bk Awal</th>
                        <th class="dhead text-center" colspan="3">Bk Sortir</th>
                        <th class="dhead text-center" colspan="3">Bk Susut</th>
                        <th class="dhead text-center" colspan="3">Bk Sisa</th>
                        <th class="dhead text-center" colspan="5">Sortir</th>
                        <th class="dhead text-center" colspan="3">Bk Sisa Pgws</th>
                    </tr>
                    <tr>
                        <th class="dhead">Grade</th>
                        <th class="dhead text-end">Pcs</th>
                        <th class="dhead text-end">Gr</th>
                        <th class="dhead text-end">Ttl Rp</th>

                        <th class="dhead text-end">Pcs</th>
                        <th class="dhead text-end">Gr</th>
                        <th class="dhead text-end">Ttl Rp</th>
                        
                        <th class="dhead text-end">Pcs</th>
                        <th class="dhead text-end">Gr</th>
                        <th class="dhead text-end">Ttl Rp</th>
                        
                        <th class="dhead text-end">Pcs</th>
                        <th class="dhead text-end">Gr</th>
                        <th class="dhead text-end">Ttl Rp</th>

                        <th class="dhead text-end">Pcs Awal</th>
                        <th class="dhead text-end">Gr Awal</th>
                        <th class="dhead text-end">Pcs Akhir</th>
                        <th class="dhead text-end">Gr Akhir</th>
                        <th class="dhead text-end">Cost Sortir</th>

                        <th class="dhead text-end">Pcs</th>
                        <th class="dhead text-end">Gr</th>
                        <th class="dhead text-end">Ttl Rp</th>
                    </tr>
                    
                </thead>
                <tbody>
                    @foreach ($wipSortir as $no => $d)
                    <tr>
                        <td>{{ $d->grade }}</td>
                        <td align="right">{{ number_format($d->pcs,0) }}</td>
                        <td align="right">{{ number_format($d->gr,0) }}</td>
                        <td align="right">{{ number_format($d->ttl_rp,0) }}</td>
                        
                        <td align="right">{{ number_format($d->pcs_bk,0) }}</td>
                        <td align="right">{{ number_format($d->gr_bk,0) }}</td>
                        <td align="right">{{ number_format($d->ttl_rp_bk,0) }}</td>
                        
                        <td align="right">-</td>
                        <td align="right">-</td>
                        <td align="right">-</td>

                        @php
                            $pcsSisa = $d->pcs - $d->pcs_bk;
                            $grSisa = $d->gr - $d->gr_bk;
                            $ttl_rp = $d->ttl_rp - $d->ttl_rp_bk;
                        @endphp
                        <td align="right">{{ number_format($pcsSisa,0) }}</td>
                        <td align="right">{{ number_format($grSisa,0) }}</td>
                        <td align="right">{{ number_format($ttl_rp,0) }}</td>

                        <td align="right">{{ number_format($d->pcs_awal ?? 0,0) }}</td>
                        <td align="right">{{ number_format($d->gr_awal ?? 0,0) }}</td>
                        <td align="right">{{ number_format($d->pcs_akhir ?? 0,0) }}</td>
                        <td align="right">{{ number_format($d->gr_akhir ?? 0,0) }}</td>
                        <td align="right">{{ number_format($d->cost_sortir ?? 0,0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
