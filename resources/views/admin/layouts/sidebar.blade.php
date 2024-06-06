<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            {{-- <div class="sidenav-menu-heading d-sm-none">Notifikasi</div>
            <a class="nav-link d-sm-none" href="#!">
                <div class="nav-link-icon"><i data-feather="bell"></i></div>
                Notifikasi
                <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
            </a> --}}

            {{-- Sidenav Menu Heading - Dasbor --}}
            <div class="sidenav-menu-heading">Dasbor</div>

            {{-- Start Dasbor --}}
            <a class="nav-link {{ isset($active) && $active == 'dasbor' ? 'active' : '' }}"
                href="{{ route('dasbor') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dasbor
            </a>
            {{-- End Dasbor --}}

            {{-- Data Master --}}
            @role('Superadmin')
                <div class="sidenav-menu-heading">Data Master</div>
                <a class="nav-link {{ isset($active) && ($active == 'users' || $active == 'admin' || $active == 'dosen' || $active == 'mahasiswa') ? 'active' : 'collapsed' }}"
                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#userdata"
                aria-expanded="false" aria-controls="userdata">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    Pengguna
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'users' || $active == 'admin' || $active == 'dosen' || $active == 'mahasiswa') ? 'show' : '' }}"
                    id="userdata" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        <a class="nav-link {{ isset($active) && $active == 'users' ? 'active' : '' }}"
                            href="{{ route('users.index') }}">Semua Pengguna</a>
                        <a class="nav-link {{ isset($active) && ($active == 'admin' || $active == 'dosen' || $active == 'mahasiswa') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#byRole"
                            aria-expanded="false" aria-controls="byRole">
                            Berdasarkan Peran
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ isset($active) && ($active == 'admin' || $active == 'dosen' || $active == 'mahasiswa') ? 'show' : '' }}"
                            id="byRole" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link {{ isset($active) && $active == 'admin' ? 'active' : '' }}"
                                    href="{{ route('users.byAdmin') }}">Admin</a>
                                <a class="nav-link {{ isset($active) && $active == 'dosen' ? 'active' : '' }}"
                                    href="{{ route('users.byDosen') }}">Dosen</a>
                                <a class="nav-link {{ isset($active) && $active == 'mahasiswa' ? 'active' : '' }}"
                                    href="{{ route('users.byMahasiswa') }}">Mahasiswa</a>
                            </nav>
                        </div>
                    </nav>
                </div>
                <a class="nav-link {{ isset($active) && $active == 'bidang-kepakaran' ? 'active' : '' }}"
                    href="{{ route('bidangKepakaran.index') }}">
                    <div class="nav-link-icon"><i class="fa-regular fa-lightbulb"></i></div>
                    Bidang Kepakaran
                </a>
                <a class="nav-link {{ isset($active) && $active == 'kegiatan-mahasiswa' ? 'active' : '' }}"
                    href="{{ route('kegiatanMahasiswa.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                    Kegiatan Mahasiswa
                </a>
            @endrole
            @role('Admin|Kajur|Kaprodi')
                <div class="sidenav-menu-heading">Data Master</div>
                <a class="nav-link {{ isset($active) && $active == 'kegiatan-mahasiswa' ? 'active' : '' }}"
                    href="{{ route('kegiatanMahasiswa.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                    Kegiatan Mahasiswa
                </a>
            @endrole

            {{-- Layanan Informasi --}}
            <div class="sidenav-menu-heading">Layanan Administrasi</div>
            @role('Superadmin|Admin|Kajur')
                @role('Superadmin|Admin|Kajur')
                    <a class="nav-link {{ isset($active) && $active == 'kegiatan' ? 'active' : '' }}"
                        href="{{ route('kegiatan.index') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-people-line"></i></div>
                        Kegiatan, DPL dan Mahasiswa
                    </a>
                @endrole
                @if (isset($dataKegiatanMahasiswas) && $dataKegiatanMahasiswas->count() > 0)
                    @php
                        $isActivePTI = false;
                        $isActiveSI = false;

                        foreach ($dataKegiatanMahasiswas as $dataKegiatanMahasiswa) {
                            if (isset($active) && $active == $dataKegiatanMahasiswa->slug) {
                                if ($dataKegiatanMahasiswa->program_studi == 'PEND. TEKNOLOGI INFORMASI') {
                                    $isActivePTI = true;
                                }
                                if ($dataKegiatanMahasiswa->program_studi == 'SISTEM INFORMASI') {
                                    $isActiveSI = true;
                                }
                            }
                        }

                        $isProdiPTI = $dataKegiatanMahasiswas->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->isNotEmpty();
                        $isProdiSI = $dataKegiatanMahasiswas->where('program_studi', 'SISTEM INFORMASI')->isNotEmpty();
                    @endphp
                    <a class="nav-link {{ ($isActivePTI || $isActiveSI) ? 'active' : 'collapsed' }}" href="javascript:void(0);"
                        data-bs-toggle="collapse" data-bs-target="#laporanmhs" aria-expanded="false"
                        aria-controls="laporanmhs">
                        <div class="nav-link-icon"><i class="fa-solid fa-file-text"></i></div>
                        Lampiran Kegiatan
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ ($isActivePTI || $isActiveSI) ? 'show' : '' }}" id="laporanmhs" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            @if ($isProdiPTI)
                                <a class="nav-link {{ $isActivePTI ? 'active' : 'collapsed' }}"
                                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pti"
                                    aria-expanded="false" aria-controls="pti">
                                    PEND. TEKNOLOGI INFORMASI
                                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ $isActivePTI ? 'show' : '' }}"
                                    id="pti" data-bs-parent="#accordionSidenavAppsMenu">
                                    <nav class="sidenav-menu-nested nav">
                                        @foreach ($dataKegiatanMahasiswas->where('program_studi', 'PEND. TEKNOLOGI INFORMASI') as $dataKegiatanMahasiswa)
                                            <a class="nav-link {{ isset($active) && $active == $dataKegiatanMahasiswa->slug ? 'active' : '' }}"
                                            href="{{ route('lampiranKegiatan.index', $dataKegiatanMahasiswa->slug) }}">{{ $dataKegiatanMahasiswa->nama_kegiatan }}</a>
                                        @endforeach
                                    </nav>
                                </div>
                            @endif

                            @if ($isProdiSI)
                                <a class="nav-link {{ $isActiveSI ? 'active' : 'collapsed' }}"
                                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#si"
                                    aria-expanded="false" aria-controls="si">
                                    SISTEM INFORMASI
                                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ $isActiveSI ? 'show' : '' }}"
                                    id="si" data-bs-parent="#accordionSidenavAppsMenu">
                                    <nav class="sidenav-menu-nested nav">
                                        @foreach ($dataKegiatanMahasiswas->where('program_studi', 'SISTEM INFORMASI') as $dataKegiatanMahasiswa)
                                            <a class="nav-link {{ isset($active) && $active == $dataKegiatanMahasiswa->slug ? 'active' : '' }}"
                                            href="{{ route('lampiranKegiatan.index', $dataKegiatanMahasiswa->slug) }}">{{ $dataKegiatanMahasiswa->nama_kegiatan }}</a>
                                        @endforeach
                                    </nav>
                                </div>
                            @endif
                        </nav>
                    </div>
                @endif
                @role('Kajur')
                    @if (isset($dataKegiatanMahasiswas) && $dataKegiatanMahasiswas->count() == 0)
                        <span class="sidenav-menu-heading py-2 fw-normal text-wrap bg-light">Belum ada data Kegiatan Mahasiswa yang diinput.</span>
                    @endif
                @endrole
            @endrole
            @role('Kaprodi')
                <a class="nav-link {{ isset($active) && $active == 'kegiatan' ? 'active' : '' }}"
                    href="{{ route('kegiatan.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-people-line"></i></div>
                    Kegiatan, DPL dan Mahasiswa
                </a>
                @if (isset($dataKegiatanMahasiswas) && $dataKegiatanMahasiswas->count() > 0)
                    @php
                        $isActive = false;
                        foreach ($dataKegiatanMahasiswas as $dataKegiatanMahasiswa) {
                            if (isset($active) && $active == $dataKegiatanMahasiswa->slug) {
                                $isActive = true;
                                break;
                            }
                        }
                    @endphp
                    <a class="nav-link {{ $isActive ? 'active' : 'collapsed' }}" href="javascript:void(0);"
                        data-bs-toggle="collapse" data-bs-target="#laporanmhs" aria-expanded="false"
                        aria-controls="laporanmhs">
                        <div class="nav-link-icon"><i class="fa-solid fa-file-text"></i></div>
                        Lampiran Kegiatan
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="laporanmhs" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            @foreach ($dataKegiatanMahasiswas as $dataKegiatanMahasiswa)
                                <a class="nav-link {{ isset($active) && $active == $dataKegiatanMahasiswa->slug ? 'active' : '' }}"
                                    href="{{ route('lampiranKegiatan.index', $dataKegiatanMahasiswa->slug) }}">{{ $dataKegiatanMahasiswa->nama_kegiatan }}</a>
                            @endforeach
                        </nav>
                    </div>
                @endif
            @endrole
            @role('Dosen|Mahasiswa')
                @if (isset($dataKegiatanMahasiswas) && $dataKegiatanMahasiswas->count() > 0)
                    @php
                        $isActive = false;
                        foreach ($dataKegiatanMahasiswas as $dataKegiatanMahasiswa) {
                            if (isset($active) && $active == $dataKegiatanMahasiswa->slug) {
                                $isActive = true;
                                break;
                            }
                        }
                    @endphp
                    <a class="nav-link {{ $isActive ? 'active' : 'collapsed' }}" href="javascript:void(0);"
                        data-bs-toggle="collapse" data-bs-target="#laporanmhs" aria-expanded="false"
                        aria-controls="laporanmhs">
                        <div class="nav-link-icon"><i class="fa-solid fa-file-text"></i></div>
                        Lampiran Kegiatan
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="laporanmhs" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            @foreach ($dataKegiatanMahasiswas as $dataKegiatanMahasiswa)
                                <a class="nav-link {{ isset($active) && $active == $dataKegiatanMahasiswa->slug ? 'active' : '' }}"
                                    href="{{ route('lampiranKegiatan.index', $dataKegiatanMahasiswa->slug) }}">{{ $dataKegiatanMahasiswa->nama_kegiatan }}</a>
                            @endforeach
                        </nav>
                    </div>
                @else
                    <span class="sidenav-menu-heading py-2 fw-normal text-wrap bg-light">Belum ada kegiatan yang diikuti</span>
                @endif
            @endrole

            @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
                <div class="sidenav-menu-heading">Informasi</div>
            @endrole
            {{-- Profil Program Studi --}}
            @role('Superadmin|Admin|Kajur|Kaprodi')
                <a class="nav-link {{ isset($active) && ($active == 'sejarah-pti' || $active == 'visi-keilmuan-tujuan-strategi-pti' || $active == 'struktur-organisasi-pti' || $active == 'kontak-lokasi-pti' || $active == 'video-profil-pti' || $active == 'sejarah-si' || $active == 'visi-keilmuan-tujuan-strategi-si' || $active == 'struktur-organisasi-si' || $active == 'kontak-lokasi-si' || $active == 'video-profil-si') ? 'active' : 'collapsed' }}" href="javascript:void(0);"
                    data-bs-toggle="collapse" data-bs-target="#profilProgramStudi" aria-expanded="false"
                    aria-controls="profilProgramStudi">
                    <div class="nav-link-icon"><i class="fa-solid fa-landmark"></i></div>
                    Profil Program Studi
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'sejarah-pti' || $active == 'visi-keilmuan-tujuan-strategi-pti' || $active == 'struktur-organisasi-pti' || $active == 'kontak-lokasi-pti' || $active == 'video-profil-pti' || $active == 'sejarah-si' || $active == 'visi-keilmuan-tujuan-strategi-si' || $active == 'struktur-organisasi-si' || $active == 'kontak-lokasi-si' || $active == 'video-profil-si') ? 'show' : '' }}" id="profilProgramStudi" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @role('Superadmin|Admin|Kajur')
                            <a class="nav-link {{ isset($active) && ($active == 'sejarah-pti' || $active == 'visi-keilmuan-tujuan-strategi-pti' || $active == 'struktur-organisasi-pti' || $active == 'kontak-lokasi-pti' || $active == 'video-profil-pti') ? 'active' : 'collapsed' }}"
                                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#profilPTI"
                                aria-expanded="false" aria-controls="profilPTI">
                                PEND. TEKNOLOGI INFORMASI
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ isset($active) && ($active == 'sejarah-pti' || $active == 'visi-keilmuan-tujuan-strategi-pti' || $active == 'struktur-organisasi-pti' || $active == 'kontak-lokasi-pti' || $active == 'video-profil-pti') ? 'show' : '' }}"
                                id="profilPTI" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link {{ isset($active) && $active == 'sejarah-pti' ? 'active' : '' }}" href="{{ route('sejarah.index', 'pti') }}">Sejarah</a>
                                    <a class="nav-link {{ isset($active) && $active == 'visi-keilmuan-tujuan-strategi-pti' ? 'active' : '' }}" href="{{ route('visiKeilmuanTujuanStrategi.index', 'pti') }}">Visi Keilmuan, Tujuan dan Strategi</a>
                                    <a class="nav-link {{ isset($active) && $active == 'struktur-organisasi-pti' ? 'active' : '' }}" href="{{ route('strukturOrganisasi.index', 'pti') }}">Struktur Organisasi</a>
                                    <a class="nav-link {{ isset($active) && $active == 'kontak-lokasi-pti' ? 'active' : '' }}" href="{{ route('kontakLokasi.index', 'pti') }}">Kontak dan Lokasi</a>
                                    <a class="nav-link {{ isset($active) && $active == 'video-profil-pti' ? 'active' : '' }}" href="{{ route('videoProfil.index', 'pti') }}">Video Profil</a>
                                </nav>
                            </div>

                            <a class="nav-link {{ isset($active) && ($active == 'sejarah-si' || $active == 'visi-keilmuan-tujuan-strategi-si' || $active == 'struktur-organisasi-si' || $active == 'kontak-lokasi-si' || $active == 'video-profil-si') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#profilSI"
                                aria-expanded="false" aria-controls="profilSI">
                                SISTEM INFORMASI
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ isset($active) && ($active == 'sejarah-si' || $active == 'visi-keilmuan-tujuan-strategi-si' || $active == 'struktur-organisasi-si' || $active == 'kontak-lokasi-si' || $active == 'video-profil-si') ? 'show' : '' }}"
                                id="profilSI" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link {{ isset($active) && $active == 'sejarah-si' ? 'active' : '' }}" href="{{ route('sejarah.index', 'si') }}">Sejarah</a>
                                    <a class="nav-link {{ isset($active) && $active == 'visi-keilmuan-tujuan-strategi-si' ? 'active' : '' }}" href="{{ route('visiKeilmuanTujuanStrategi.index', 'si') }}">Visi Keilmuan, Tujuan dan Strategi</a>
                                    <a class="nav-link {{ isset($active) && $active == 'struktur-organisasi-si' ? 'active' : '' }}" href="{{ route('strukturOrganisasi.index', 'si') }}">Struktur Organisasi</a>
                                    <a class="nav-link {{ isset($active) && $active == 'kontak-lokasi-si' ? 'active' : '' }}" href="{{ route('kontakLokasi.index', 'si') }}">Kontak dan Lokasi</a>
                                    <a class="nav-link {{ isset($active) && $active == 'video-profil-si' ? 'active' : '' }}" href="{{ route('videoProfil.index', 'si') }}">Video Profil</a>
                                </nav>
                            </div>
                        @endrole

                        @role('Kaprodi')
                            @if (Auth::user()->dosen->program_studi == 'PEND. TEKNOLOGI INFORMASI')
                                <a class="nav-link {{ isset($active) && $active == 'sejarah-pti' ? 'active' : '' }}" href="{{ route('sejarah.index', 'pti') }}">Sejarah</a>
                                <a class="nav-link {{ isset($active) && $active == 'visi-keilmuan-tujuan-strategi-pti' ? 'active' : '' }}" href="{{ route('visiKeilmuanTujuanStrategi.index', 'pti') }}">Visi Keilmuan, Tujuan dan Strategi</a>
                                <a class="nav-link {{ isset($active) && $active == 'struktur-organisasi-pti' ? 'active' : '' }}" href="{{ route('strukturOrganisasi.index', 'pti') }}">Struktur Organisasi</a>
                                <a class="nav-link {{ isset($active) && $active == 'kontak-lokasi-pti' ? 'active' : '' }}" href="{{ route('kontakLokasi.index', 'pti') }}">Kontak dan Lokasi</a>
                                <a class="nav-link {{ isset($active) && $active == 'video-profil-pti' ? 'active' : '' }}" href="{{ route('videoProfil.index', 'pti') }}">Video Profil</a>
                            @endif

                            @if (Auth::user()->dosen->program_studi == 'SISTEM INFORMASI')
                                <a class="nav-link {{ isset($active) && $active == 'sejarah-si' ? 'active' : '' }}" href="{{ route('sejarah.index', 'si') }}">Sejarah</a>
                                <a class="nav-link {{ isset($active) && $active == 'visi-keilmuan-tujuan-strategi-si' ? 'active' : '' }}" href="{{ route('visiKeilmuanTujuanStrategi.index', 'si') }}">Visi Keilmuan, Tujuan dan Strategi</a>
                                <a class="nav-link {{ isset($active) && $active == 'struktur-organisasi-si' ? 'active' : '' }}" href="{{ route('strukturOrganisasi.index', 'si') }}">Struktur Organisasi</a>
                                <a class="nav-link {{ isset($active) && $active == 'kontak-lokasi-si' ? 'active' : '' }}" href="{{ route('kontakLokasi.index', 'si') }}">Kontak dan Lokasi</a>
                                <a class="nav-link {{ isset($active) && $active == 'video-profil-si' ? 'active' : '' }}" href="{{ route('videoProfil.index', 'si') }}">Video Profil</a>
                            @endif
                        @endrole
                    </nav>
                </div>
            @endrole

            {{-- Fasilitas --}}
            @role('Superadmin|Admin|Kajur|Kaprodi')
                <a class="nav-link {{ isset($active) && ($active == 'sarana' || $active == 'kategori-sarana' || $active == 'prasarana' || $active == 'kategori-prasarana' || $active == 'sistem-informasi') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#fasilitas"
                    aria-expanded="false" aria-controls="fasilitas">
                    <div class="nav-link-icon"><i class="fa-regular fa-hospital"></i></div>
                    Fasilitas
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'sarana' || $active == 'kategori-sarana' || $active == 'prasarana' || $active == 'kategori-prasarana' || $active == 'sistem-informasi') ? 'show' : '' }}"
                    id="fasilitas" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link {{ isset($active) && ($active == 'sarana' || $active == 'kategori-sarana') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#sarana"
                            aria-expanded="false" aria-controls="sarana">
                            Sarana
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ isset($active) && ($active == 'sarana' || $active == 'kategori-sarana') ? 'show' : '' }}"
                            id="sarana" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link {{ isset($active) && $active == 'sarana' ? 'active' : '' }}"
                                    href="{{ route('sarana.index') }}">Sarana</a>
                                <a class="nav-link {{ isset($active) && $active == 'kategori-sarana' ? 'active' : '' }}"
                                    href="{{ route('kategoriSarana.index') }}">Kategori Sarana</a>
                            </nav>
                        </div>
                        <a class="nav-link {{ isset($active) && ($active == 'prasarana' || $active == 'kategori-prasarana') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#prasarana"
                            aria-expanded="false" aria-controls="prasarana">
                            Prasarana
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ isset($active) && ($active == 'prasarana' || $active == 'kategori-prasarana') ? 'show' : '' }}"
                            id="prasarana" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link {{ isset($active) && $active == 'prasarana' ? 'active' : '' }}"
                                    href="{{ route('prasarana.index') }}">Prasarana</a>
                                <a class="nav-link {{ isset($active) && $active == 'kategori-prasarana' ? 'active' : '' }}"
                                    href="{{ route('kategoriPrasarana.index') }}">Kategori Prsarana</a>
                            </nav>
                        </div>
                        <a class="nav-link {{ isset($active) && $active == 'sistem-informasi' ? 'active' : '' }}"
                            href="{{ route('sistemInformasi.index') }}">Sistem Informasi</a>
                    </nav>
                </div>
            @endrole

            {{-- Akademik --}}
            @role('Superadmin|Admin|Kajur|Kaprodi')
                <a class="nav-link {{ isset($active) && ($active == 'profil-lulusan' || $active == 'capaian-pembelajaran' || $active == 'kurikulum' || $active == 'dokumen-kurikulum' || $active == 'kalender-akademik' || $active == 'kegiatan-perkuliahan') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#akademik"
                    aria-expanded="false" aria-controls="akademik">
                    <div class="nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
                    Akademik
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'profil-lulusan' || $active == 'capaian-pembelajaran' || $active == 'kurikulum' || $active == 'dokumen-kurikulum' || $active == 'kalender-akademik' || $active == 'kegiatan-perkuliahan') ? 'show' : '' }}"
                    id="akademik" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link {{ isset($active) && $active == 'profil-lulusan' ? 'active' : '' }}"
                            href="{{ route('profilLulusan.index') }}">Profil Lulusan</a>
                        <a class="nav-link {{ isset($active) && $active == 'capaian-pembelajaran' ? 'active' : '' }}"
                            href="{{ route('capaianPembelajaran.index') }}">Capaian Pembelajaran</a>
                        <a class="nav-link {{ isset($active) && ($active == 'kurikulum' || $active == 'dokumen-kurikulum') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#kurikulum"
                            aria-expanded="false" aria-controls="kurikulum">
                            Kurikulum
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ isset($active) && ($active == 'kurikulum' || $active == 'dokumen-kurikulum') ? 'show' : '' }}"
                            id="kurikulum" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link {{ isset($active) && $active == 'kurikulum' ? 'active' : '' }}"
                                    href="{{ route('kurikulum.index') }}">Kurikulum</a>
                                <a class="nav-link {{ isset($active) && $active == 'dokumen-kurikulum' ? 'active' : '' }}"
                                    href="{{ route('dokumenKurikulum.index') }}">Dokumen Kurikulum</a>
                            </nav>
                        </div>
                        <a class="nav-link {{ isset($active) && $active == 'kalender-akademik' ? 'active' : '' }}"
                                href="{{ route('kalenderAkademik.index') }}">Kalender Akademik</a>
                        <a class="nav-link {{ isset($active) && $active == 'kegiatan-perkuliahan' ? 'active' : '' }}"
                            href="{{ route('kegiatanPerkuliahan.index') }}">Kegiatan Perkuliahan</a>
                    </nav>
                </div>
            @endrole

            {{-- Penelitian dan PKM --}}
            @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
                <a class="nav-link {{ isset($active) && ($active == 'penelitian' || $active == 'pengabdian-masyarakat' || $active == 'publikasi') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#penelitian"
                    aria-expanded="false" aria-controls="penelitian">
                    <div class="nav-link-icon"><i data-feather="search"></i></div>
                    Penelitian dan PKM
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'penelitian' || $active == 'pengabdian-masyarakat' || $active == 'publikasi') ? 'show' : '' }}"
                    id="penelitian" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        <a class="nav-link {{ isset($active) && $active == 'penelitian' ? 'active' : '' }}"
                            href="{{ route('penelitian.index') }}">Penelitian</a>
                        <a class="nav-link {{ isset($active) && $active == 'pengabdian-masyarakat' ? 'active' : '' }}"
                            href="{{ route('pengabdianMasyarakat.index') }}">Pengabdian Masyarakat</a>
                        <a class="nav-link {{ isset($active) && $active == 'publikasi' ? 'active' : '' }}"
                            href="{{ route('publikasi.index') }}">Publikasi</a>
                    </nav>
                </div>
            @endrole

            {{-- Kerja Sama --}}
            @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
                <a class="nav-link {{ isset($active) && ($active == 'kerja-sama-dalam-negeri' || $active == 'kerja-sama-luar-negeri') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#kerjasama"
                    aria-expanded="false" aria-controls="kerjasama">
                    <div class="nav-link-icon"><i class="far fa-handshake"></i></div>
                    Kerja Sama
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'kerja-sama-dalam-negeri' || $active == 'kerja-sama-luar-negeri') ? 'show' : '' }}"
                    id="kerjasama" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        <a class="nav-link {{ isset($active) && $active == 'kerja-sama-dalam-negeri' ? 'active' : '' }}"
                            href="{{ route('kerjasamaDalamNegeri.index') }}">Dalam Negeri</a>
                        <a class="nav-link {{ isset($active) && $active == 'kerja-sama-luar-negeri' ? 'active' : '' }}"
                            href="{{ route('kerjasamaLuarNegeri.index') }}">Luar Negeri</a>
                    </nav>
                </div>
            @endrole

            {{-- Mahasiswa dan Alumni --}}
            @role('Superadmin|Admin|Kajur|Kaprodi')
                <a class="nav-link {{ isset($active) && ($active == 'pendaftaran-mahasiswa-baru' || $active == 'prestasi-mahasiswa' || $active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja' || $active == 'organisasi-mahasiswa') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#mhsalumni"
                    aria-expanded="false" aria-controls="mhsalumni">
                    <div class="nav-link-icon"><i class="fas fa-users-rays"></i></div>
                    Mahasiswa dan Alumni
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'pendaftaran-mahasiswa-baru' || $active == 'prestasi-mahasiswa' || $active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja' || $active == 'organisasi-mahasiswa') ? 'show' : '' }}"
                    id="mhsalumni" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @role('Superadmin|Admin|Kajur')
                            <a class="nav-link {{ isset($active) && $active == 'pendaftaran-mahasiswa-baru' ? 'active' : '' }}" href="{{ route('pendaftaranMahasiswaBaru.index') }}">Pendaftaran Mahasiswa Baru</a>
                        @endrole

                        <a class="nav-link {{ isset($active) && $active == 'prestasi-mahasiswa' ? 'active' : '' }}"
                            href="{{ route('prestasiMahasiswa.index') }}">Prestasi Mahasiswa</a>
                        <a class="nav-link {{ isset($active) && ($active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#peluangmhs"
                            aria-expanded="false" aria-controls="peluangmhs">
                            Peluang Mahasiswa
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ isset($active) && ($active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja') ? 'show' : '' }}"
                            id="peluangmhs" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link {{ isset($active) && $active == 'beasiswa' ? 'active' : '' }}"
                                    href="{{ route('beasiswa.index') }}">Beasiswa</a>
                                <a class="nav-link {{ isset($active) && $active == 'exchange-dan-double-degree' ? 'active' : '' }}"
                                    href="{{ route('edd.index') }}">Exchange dan Double Degree</a>
                                <a class="nav-link {{ isset($active) && $active == 'seminar-dan-kompetisi' ? 'active' : '' }}"
                                    href="{{ route('seminarDanKompetisi.index') }}">Seminar dan Kompetisi</a>
                                <a class="nav-link {{ isset($active) && $active == 'magang-atau-praktik-industri' ? 'active' : '' }}"
                                    href="{{ route('mapi.index') }}">Magang atau Praktik Industri</a>
                                <a class="nav-link {{ isset($active) && $active == 'lowongan-kerja' ? 'active' : '' }}"
                                    href="{{ route('lowonganKerja.index') }}">Lowongan Kerja</a>
                            </nav>
                        </div>

                        <a class="nav-link {{ isset($active) && $active == 'organisasi-mahasiswa' ? 'active' : '' }}"
                            href="{{ route('organisasiMahasiswa.index') }}">Organisasi Mahasiswa</a>
                        <a class="nav-link text-muted disabled" href="#">Alumni <i class="fas fa-lock fa-xs ms-1"></i></a>
                    </nav>
                </div>
            @endrole
            @role('Dosen')
                <a class="nav-link {{ isset($active) && ($active == 'prestasi-mahasiswa' || $active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#mhsalumni"
                    aria-expanded="false" aria-controls="mhsalumni">
                    <div class="nav-link-icon"><i class="fas fa-users-rays"></i></div>
                    Mahasiswa dan Alumni
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'prestasi-mahasiswa' || $active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja') ? 'show' : '' }}"
                    id="mhsalumni" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link {{ isset($active) && $active == 'prestasi-mahasiswa' ? 'active' : '' }}"
                            href="{{ route('prestasiMahasiswa.index') }}">Prestasi Mahasiswa</a>
                        <a class="nav-link {{ isset($active) && ($active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja') ? 'active' : 'collapsed' }}"
                            href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#peluangmhs"
                            aria-expanded="false" aria-controls="peluangmhs">
                            Peluang Mahasiswa
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ isset($active) && ($active == 'beasiswa' || $active == 'exchange-dan-double-degree' || $active == 'seminar-dan-kompetisi' || $active == 'magang-atau-praktik-industri' || $active == 'lowongan-kerja') ? 'show' : '' }}"
                            id="peluangmhs" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link {{ isset($active) && $active == 'beasiswa' ? 'active' : '' }}"
                                    href="{{ route('beasiswa.index') }}">Beasiswa</a>
                                <a class="nav-link {{ isset($active) && $active == 'exchange-dan-double-degree' ? 'active' : '' }}"
                                    href="{{ route('edd.index') }}">Exchange dan Double Degree</a>
                                <a class="nav-link {{ isset($active) && $active == 'seminar-dan-kompetisi' ? 'active' : '' }}"
                                    href="{{ route('seminarDanKompetisi.index') }}">Seminar dan Kompetisi</a>
                                <a class="nav-link {{ isset($active) && $active == 'magang-atau-praktik-industri' ? 'active' : '' }}"
                                    href="{{ route('mapi.index') }}">Magang atau Praktik Industri</a>
                                <a class="nav-link {{ isset($active) && $active == 'lowongan-kerja' ? 'active' : '' }}"
                                    href="{{ route('lowonganKerja.index') }}">Lowongan Kerja</a>
                            </nav>
                        </div>
                        <a class="nav-link text-muted disabled" href="#">Alumni <i class="fas fa-lock fa-xs ms-1"></i></a>
                    </nav>
                </div>
            @endrole

            {{-- Repositori --}}
            @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
                <a class="nav-link {{ isset($active) && ($active == 'dokumen-kebijakan' || $active == 'dokumen-lainnya' || $active == 'data-dukung-akreditasi') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#repositori"
                    aria-expanded="false" aria-controls="repositori">
                    <div class="nav-link-icon"><i data-feather="archive"></i></div>
                    Repositori
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'dokumen-kebijakan' || $active == 'dokumen-lainnya' || $active == 'data-dukung-akreditasi') ? 'show' : '' }}"
                    id="repositori" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        <a class="nav-link {{ isset($active) && $active == 'dokumen-kebijakan' ? 'active' : '' }}"
                            href="{{ route('dokumenKebijakan.index') }}">Dokumen Kebijakan</a>
                        <a class="nav-link {{ isset($active) && $active == 'dokumen-lainnya' ? 'active' : '' }}"
                            href="{{ route('dokumenLainnya.index') }}">Dokumen Lainnya</a>
                        <a class="nav-link {{ isset($active) && $active == 'data-dukung-akreditasi' ? 'active' : '' }}"
                            href="{{ route('dataDukungAkreditasi.index') }}">
                            @role('Superadmin|Admin|Kajur')
                                Data Dukung Akreditasi
                            @endrole
                            @role('Kaprodi|Dosen')
                                @if (Auth::user()->dosen->program_studi == 'PEND. TEKNOLOGI INFORMASI')
                                    Data Dukung Akreditasi 2023
                                @else
                                    Data Dukung Akreditasi 2024
                                @endif
                            @endrole
                        </a>
                    </nav>
                </div>
            @endrole

            {{-- Konten --}}
            @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
                <a class="nav-link {{ isset($active) && ($active == 'banner' || $active == 'berita' || $active == 'agenda' || $active == 'jurnal') ? 'active' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#konten"
                    aria-expanded="false" aria-controls="konten">
                    <div class="nav-link-icon"><i data-feather="layout"></i></div>
                    Konten
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ isset($active) && ($active == 'banner' || $active == 'berita' || $active == 'agenda' || $active == 'jurnal') ? 'show' : '' }}"
                    id="konten" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        @role('Superadmin|Admin|Kajur|Kaprodi')
                            <a class="nav-link {{ isset($active) && $active == 'banner' ? 'active' : '' }}"
                                href="{{ route('banner.index') }}">Banner</a>
                        @endrole
                        <a class="nav-link {{ isset($active) && $active == 'berita' ? 'active' : '' }}"
                            href="{{ route('berita.index') }}">Berita</a>
                        @role('Superadmin|Admin|Kajur|Kaprodi')
                            <a class="nav-link {{ isset($active) && $active == 'agenda' ? 'active' : '' }}"
                                href="{{ route('agenda.index') }}">Agenda</a>
                        @endrole
                        @role('Superadmin|Admin|Kajur')
                            <a class="nav-link {{ isset($active) && $active == 'jurnal' ? 'active' : '' }}"
                                href="{{ route('jurnal.index') }}">Jurnal</a>
                        @endrole
                    </nav>
                </div>
            @endrole

            <div class="sidenav-menu-heading">Pengaturan</div>

            {{-- Pengaturan Akun --}}
            <a class="nav-link {{ isset($active) && $active == 'pengaturan-akun' ? 'active' : '' }}"
                href="{{ route('pengaturanAkun') }}">
                <div class="nav-link-icon"><i class="fas fa-user-gear"></i></div>
                Akun
            </a>

            {{-- Pengaturan Sistem --}}
            @role('Superadmin')
                <a class="nav-link {{ isset($active) && $active == 'pengaturan-sistem' ? 'active' : '' }}"
                    href="{{ route('pengaturanSistem') }}">
                    <div class="nav-link-icon"><i class="fas fa-gear"></i></div>
                    Sistem
                </a>
            @endrole
        </div>
    </div>
    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Masuk sebagai:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
        </div>
    </div>
</nav>
