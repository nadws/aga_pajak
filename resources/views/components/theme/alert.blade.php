@props([
    'pesan' => '',
])
<div class="col-lg-4">
    <div class="alert alert-danger">
        <i class="bi bi-file-excel"></i> {{ ucwords($pesan) }}.
    </div>
</div>
