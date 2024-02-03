<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#" title="Detail">
    <i class="fas fa-eye"></i>
</a>

<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#" title="Edit">
    <i class="fas fa-edit"></i>
</a>

<form id="deleteForm" class="d-none" method="POST">
    @csrf
    @method('DELETE')
</form>
<a class="btn btn-datatable btn-icon btn-transparent-dark tombol-hapus" href="javascript:void(0)" title="Hapus"
    data-user-id="{{ $users->id }}">
    <i class="fas fa-trash"></i>
</a>
