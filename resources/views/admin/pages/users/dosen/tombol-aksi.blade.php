@role('Superadmin')
    <div class="dropdown d-inline-block">
        <button class="btn btn-datatable btn-icon btn-transparent-dark" id="dropdownFadeInUp" type="button"
            title="Ubah Role" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-shield"></i>
        </button>
        <div class="dropdown-menu animated--fade-in-up" aria-labelledby="dropdownFadeInUp">
            @if ($users->memilikiPeran('Kajur'))
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setChangeRole({{ $users->id }}, 'Kaprodi')">
                    Kepala Program Studi
                </a>
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setChangeRole({{ $users->id }}, 'Dosen')">
                    Dosen
                </a>
            @elseif ($users->memilikiPeran('Kaprodi'))
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setChangeRole({{ $users->id }}, 'Kajur')">
                    Kepala Jurusan
                </a>
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setChangeRole({{ $users->id }}, 'Dosen')">
                    Dosen
                </a>
            @else
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setChangeRole({{ $users->id }}, 'Kajur')">
                    Kepala Jurusan
                </a>
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setChangeRole({{ $users->id }}, 'Kaprodi')">
                    Kepala Program Studi
                </a>
            @endif
        </div>
    </div>
@endrole

<a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{ route('users.formResetPassword', $users->id) }}"
    title="Atur Ulang Password">
    <i class="fas fa-key"></i>
</a>
<a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{ route('users.editDosen', $users->id) }}"
    title="Edit">
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
