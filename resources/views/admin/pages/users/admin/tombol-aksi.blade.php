{{-- @if (Auth::user()->memilikiPeran('admin') && Auth::user()->memilikiHakIstimewa('super-admin'))
    <div class="dropdown d-inline-block">
        <button class="btn btn-datatable btn-icon btn-transparent-dark" id="dropdownFadeInUp" type="button"
            title="Hak Istimewah" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-shield"></i>
        </button>
        <div class="dropdown-menu animated--fade-in-up" aria-labelledby="dropdownFadeInUp">
            <form method="POST" id="hakIstimewaForm" class="d-none">
                @csrf
            </form>
            @if ($users->hak_istimewa == 'super-admin')
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setHakIstimewa({{ $users->id }}, null)">
                    Hapus Hak Istimewa
                </a>
            @else
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="return setHakIstimewa({{ $users->id }}, 'super-admin')">
                    Super Admin
                </a>
            @endif
        </div>
    </div>
@endif --}}

<a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{ route('users.formResetPassword', $users->id) }}"
    title="Atur Ulang Password">
    <i class="fas fa-key"></i>
</a>
<a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{ route('users.editAdmin', $users->id) }}"
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
