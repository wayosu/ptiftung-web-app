<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
    id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i
            data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <!-- * * Tip * * You can use text or an image for your navbar brand.-->
    <!-- * * * * * * When using an image, we recommend the SVG format.-->
    <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ route('dasbor') }}">
        @role('Kaprodi|Dosen')
            @if (Auth::user()->dosen->program_studi == "PEND. TEKNOLOGI INFORMASI")
                Dasbor WEB PTI
            @else
                Dasbor WEB SI
            @endif
        @endrole
        @role('Mahasiswa')
            @if (Auth::user()->mahasiswa->program_studi == "PEND. TEKNOLOGI INFORMASI")
                Dasbor WEB PTI
            @else
                Dasbor WEB SI
            @endif
        @endrole
        @role('Superadmin|Admin|Kajur')
            Dasbor WEB PTI - SI
        @endrole
    </a>
    <!-- Navbar Search Input-->
    <!-- * * Note: * * Visible only on and above the lg breakpoint-->
    {{-- <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </form> --}}
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- Navbar Search Dropdown-->
        <!-- * * Note: * * Visible only below the lg breakpoint-->
        {{-- <li class="nav-item dropdown no-caret me-3 d-lg-none">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="searchDropdown" href="#"
                role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                    data-feather="search"></i></a>
            <!-- Dropdown - Search-->
            <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--fade-in-up"
                aria-labelledby="searchDropdown">
                <form class="form-inline me-auto w-100">
                    <div class="input-group input-group-joined input-group-solid">
                        <input class="form-control pe-0" type="text" placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2" />
                        <div class="input-group-text"><i data-feather="search"></i></div>
                    </div>
                </form>
            </div>
        </li> --}}
        <!-- Notifikasi Dropdown-->
        @include('admin.partials.notifikasi')

        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img class="img-fluid" style="object-fit: cover; width: 40px; height: 40px; border-radius: 50%;"
                    src="{{ Auth::user()->foto ? asset('storage/usersProfile/' . Auth::user()->foto) : asset('assets/admin/img/profile-1.png') }}" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" style="object-fit: cover;"
                        src="{{ Auth::user()->foto ? asset('storage/usersProfile/' . Auth::user()->foto) : asset('assets/admin/img/profile-1.png') }}" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="dropdown-user-details-email">
                            @role('Superadmin')
                                {{ Auth::user()->email }}
                            @endrole
                            @role('Admin')
                                {{ Auth::user()->email }}
                            @endrole
                            @role('Kajur')
                                Kepala Jurusan - {{ Auth::user()->dosen->nip }}
                            @endrole
                            @role('Kaprodi')
                                Kaprodi 
                                {{ Auth::user()->dosen->program_studi == "PEND. TEKNOLOGI INFORMASI" ? "PTI" : "SI" }} 
                                - {{ Auth::user()->dosen->nip }}
                            @endrole
                            @role('Dosen')
                                Dosen - {{ Auth::user()->dosen->nip }}
                            @endrole
                            @role('Mahasiswa')
                                Mahasiswa - {{ Auth::user()->mahasiswa->nim }}
                            @endrole
                        </div>
                    </div>
                </h6>
                <a class="dropdown-item" href="{{ route('pengaturanAkun') }}">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Pengaturan Akun
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
