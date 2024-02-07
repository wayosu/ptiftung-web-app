@if ($users->role_name == 'Admin')
    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{ route('users.editAdmin', $users->id) }}"
        title="Edit">
        <i class="fas fa-edit"></i>
    </a>
@elseif ($users->role_name == 'Dosen')
    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{ route('users.editDosen', $users->id) }}"
        title="Edit">
        <i class="fas fa-edit"></i>
    </a>
@elseif ($users->role_name == 'Mahasiswa')
    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{ route('users.editMahasiswa', $users->id) }}"
        title="Edit">
        <i class="fas fa-edit"></i>
    </a>
@endif

<form id="deleteForm" class="d-none" method="POST">
    @csrf
    @method('DELETE')
</form>
<a class="btn btn-datatable btn-icon btn-transparent-dark tombol-hapus" href="javascript:void(0)" title="Hapus"
    data-user-id="{{ $users->id }}">
    <i class="fas fa-trash"></i>
</a>
