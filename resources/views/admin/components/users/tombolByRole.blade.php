<a class="btn btn-sm btn-light text-primary dropdown-toggle" id="navbarDropdownDocs" href="javascript:void(0);"
    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa-solid fa-users-gear me-1"></i>
    By Role
</a>
<div class="dropdown-menu dropdown-menu-start py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up"
    aria-labelledby="navbarDropdownDocs">
    <a class="dropdown-item small py-2" href="{{ route('users.byRole', 'admin') }}">
        Admin
    </a>
    <div class="dropdown-divider m-0"></div>
    <a class="dropdown-item small py-2" href="{{ route('users.byRole', 'dosen') }}">
        Dosen
    </a>
    <div class="dropdown-divider m-0"></div>
    <a class="dropdown-item small py-2" href="{{ route('users.byRole', 'mahasiswa') }}">
        Mahasiswa
    </a>
</div>
