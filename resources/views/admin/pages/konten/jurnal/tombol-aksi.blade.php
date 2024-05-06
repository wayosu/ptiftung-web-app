<form id="deleteForm" class="d-none" method="POST">
    @csrf
    @method('DELETE')
</form>
<a class="btn btn-datatable btn-icon btn-transparent-dark tombol-hapus" href="javascript:void(0)" title="Hapus"
    data-id="{{ $jurnals->id }}">
    <i class="fas fa-trash"></i>
</a>
