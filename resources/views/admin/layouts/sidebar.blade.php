<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading d-sm-none">Notifikasi</div>
            <a class="nav-link d-sm-none" href="#!">
                <div class="nav-link-icon"><i data-feather="bell"></i></div>
                Alerts
                <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
            </a>

            <div class="sidenav-menu-heading">Dashboard</div>
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>

            <div class="sidenav-menu-heading">Master Data</div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#userdata" aria-expanded="false" aria-controls="userdata">
                <div class="nav-link-icon"><i data-feather="users"></i></div>
                Users
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="userdata" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    <a class="nav-link" href="{{ route('users.index') }}">All Users</a>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#byRole" aria-expanded="false" aria-controls="byRole">
                        By Role
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="byRole" data-bs-parent="#accordionSidenavAppsMenu">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('users.byAdmin') }}">Admin</a>
                            <a class="nav-link" href="#">Dosen</a>
                            <a class="nav-link" href="#">Mahasiswa</a>
                        </nav>
                    </div>
                </nav>
            </div>

            <div class="sidenav-menu-heading">Layanan Administrasi</div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#laporanmhs" aria-expanded="false" aria-controls="laporanmhs">
                <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                Laporan Akhir Kegiatan Mahasiswa
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="laporanmhs" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                    <a class="nav-link" href="#">Pengenalan Lapangan Persekolahan (PLP)</a>
                    <a class="nav-link" href="#">Kuliah Kerja Nyata (KKN)</a>
                    <a class="nav-link" href="#">Magang MSIB</a>
                    <a class="nav-link" href="#">Magang Mandiri</a>
                </nav>
            </div>

            <div class="sidenav-menu-heading">Informasi</div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="nav-link-icon"><i data-feather="info"></i></div>
                Profil
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                    <a class="nav-link" href="#">Sejarah</a>
                    <a class="nav-link" href="#">Visi, Tujuan dan Strategi</a>
                    <a class="nav-link" href="#">Struktur Organisasi</a>
                    <a class="nav-link" href="#">Kontak dan Lokasi</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#fasilitas" aria-expanded="false" aria-controls="fasilitas">
                <div class="nav-link-icon"><i data-feather="home"></i></div>
                Fasilitas
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="fasilitas" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#sarana" aria-expanded="false" aria-controls="sarana">
                        Sarana
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="sarana" data-bs-parent="#accordionSidenavAppsMenu">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="#">Sarana</a>
                            <a class="nav-link" href="#">Kategori Sarana</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#prasarana" aria-expanded="false" aria-controls="prasarana">
                        Prasarana
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="prasarana" data-bs-parent="#accordionSidenavAppsMenu">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="#">Prasarana</a>
                            <a class="nav-link" href="#">Kategori Prsarana</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="#">Sistem Informasi</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#akademik" aria-expanded="false" aria-controls="akademik">
                <div class="nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
                Akademik
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="akademik" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                    <a class="nav-link" href="#">Profil Lulusan</a>
                    <a class="nav-link" href="#">Capaian Pembelajaran</a>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#kurikulum" aria-expanded="false" aria-controls="kurikulum">
                        Kurikulum
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="kurikulum" data-bs-parent="#accordionSidenavAppsMenu">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="#">Kurikulum</a>
                            <a class="nav-link" href="#">Dokumen Kurikulum</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="#">Kalender Akademik</a>
                    <a class="nav-link" href="#">Kegiatan Perkuliahan</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#penelitian" aria-expanded="false" aria-controls="penelitian">
                <div class="nav-link-icon"><i data-feather="search"></i></div>
                Penelitian dan PKM
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="penelitian" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    <a class="nav-link" href="#">Penelitian</a>
                    <a class="nav-link" href="#">Pengabdian Masyarakat</a>
                    <a class="nav-link" href="#">Publikasi</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#kerjasama" aria-expanded="false" aria-controls="kerjasama">
                <div class="nav-link-icon"><i class="far fa-handshake"></i></div>
                Kerja Sama
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="kerjasama" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    <a class="nav-link" href="#">Dalam Negeri</a>
                    <a class="nav-link" href="#">Luar Negeri</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#mhsalumni" aria-expanded="false" aria-controls="mhsalumni">
                <div class="nav-link-icon"><i class="fas fa-users-rays"></i></div>
                Mahasiswa dan Alumni
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="mhsalumni" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                    <a class="nav-link" href="#">Pendaftaran Mahasiswa Baru</a>
                    <a class="nav-link" href="#">Prestasi Mahasiswa</a>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#peluangmhs" aria-expanded="false" aria-controls="peluangmhs">
                        Peluang Mahasiswa
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="peluangmhs" data-bs-parent="#accordionSidenavAppsMenu">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="#">Beasiswa</a>
                            <a class="nav-link" href="#">Exchange dan Double Degree</a>
                            <a class="nav-link" href="#">Seminar dan Kompetisi</a>
                            <a class="nav-link" href="#">Magang atau Praktik Industri</a>
                            <a class="nav-link" href="#">Lowongan Kerja</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="#">Organisasi Mahasiswa</a>
                    <a class="nav-link" href="#">Alumni</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#repositori" aria-expanded="false" aria-controls="repositori">
                <div class="nav-link-icon"><i data-feather="archive"></i></div>
                Repositori
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="repositori" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    <a class="nav-link" href="#">Dokumen Kebijakan</a>
                    <a class="nav-link" href="#">Dokumen Lainnya</a>
                    <a class="nav-link" href="#">Data Dukung Akreditasi 2023</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                data-bs-target="#konten" aria-expanded="false" aria-controls="konten">
                <div class="nav-link-icon"><i data-feather="layout"></i></div>
                Konten
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="konten" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    <a class="nav-link" href="#">Banner</a>
                    <a class="nav-link" href="#">Berita</a>
                    <a class="nav-link" href="#">Agenda</a>
                    <a class="nav-link" href="#">Jurnal</a>
                </nav>
            </div>

            <div class="sidenav-menu-heading">Pengaturan</div>
            <a class="nav-link" href="#">
                <div class="nav-link-icon"><i class="fas fa-user-gear"></i></div>
                Akun
            </a>
            <a class="nav-link" href="#">
                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                Pengaturan
            </a>
        </div>
    </div>
    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
        </div>
    </div>
</nav>
