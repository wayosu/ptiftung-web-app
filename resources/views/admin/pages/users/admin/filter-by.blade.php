<a class="btn btn-sm btn-light text-primary dropdown-toggle" id="navbarDropdownDocs" href="javascript:void(0);"
    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa-solid fa-filter me-1"></i>
    Filter By
</a>
<div class="dropdown-menu dropdown-menu-start py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up"
    aria-labelledby="navbarDropdownDocs">
    @if (Route::currentRouteName() != 'users.index')
        <a class="dropdown-item small py-2" href="{{ route('users.index') }}">
            All Users
        </a>
    @endif
    @if (Route::currentRouteName() != 'users.byAdmin')
        <a class="dropdown-item small py-2" href="{{ route('users.byAdmin') }}">
            Admin
        </a>
    @endif
    <div class="dropdown-divider m-0"></div>
    <a class="dropdown-item small py-2" href="#">
        Dosen
    </a>
    <div class="dropdown-divider m-0"></div>
    <a class="dropdown-item small py-2" href="#">
        Mahasiswa
    </a>
</div>
