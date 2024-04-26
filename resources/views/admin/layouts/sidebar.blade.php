<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading d-sm-none">Notifikasi</div>
            <a class="nav-link d-sm-none" href="#!">
                <div class="nav-link-icon"><i data-feather="bell"></i></div>
                Alerts
                <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
            </a>

            <div class="sidenav-menu-heading">Dasbor</div>
            <a class="nav-link {{ isset($active) && $active == 'dasbor' ? 'active' : '' }}"
                href="{{ route('dasbor') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dasbor
            </a>

            <div class="sidenav-menu-heading">Data Master</div>
            <a class="nav-link {{ isset($active) && ($active == 'users' || $active == 'admin' || $active == 'dosen' || $active == 'mahasiswa') ? 'active' : 'collapsed' }}"
                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#userdata" aria-expanded="false"
                aria-controls="userdata">
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
            <a class="nav-link @if (isset($active) && $active == 'bidang-kepakaran') active @endif"
                href="{{ route('bidangKepakaran.index') }}">
                <div class="nav-link-icon"><i class="fa-regular fa-lightbulb"></i></div>
                Bidang Kepakaran
            </a>

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
            <a class="nav-link {{ isset($active) && ($active == 'sejarah' || $active == 'visi-keilmuan-tujuan-strategi' || $active == 'struktur-organisasi' || $active == 'kontak-lokasi') ? 'active' : 'collapsed' }}"
                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                aria-expanded="false" aria-controls="collapseLayouts">
                <div class="nav-link-icon"><i class="fa-solid fa-landmark"></i></div>
                Profil Program Studi
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ isset($active) && ($active == 'sejarah' || $active == 'visi-keilmuan-tujuan-strategi' || $active == 'struktur-organisasi' || $active == 'kontak-lokasi') ? 'show' : '' }}"
                id="collapseLayouts" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                    <a class="nav-link {{ isset($active) && $active == 'sejarah' ? 'active' : '' }}"
                        href="{{ route('sejarah.index') }}">Sejarah</a>
                    <a class="nav-link {{ isset($active) && $active == 'visi-keilmuan-tujuan-strategi' ? 'active' : '' }}"
                        href="{{ route('visiKeilmuanTujuanStrategi.index') }}">Visi Keilmuan, Tujuan
                        dan Strategi</a>
                    <a class="nav-link {{ isset($active) && $active == 'struktur-organisasi' ? 'active' : '' }}"
                        href="{{ route('strukturOrganisasi.index') }}">Struktur Organisasi</a>
                    <a class="nav-link {{ isset($active) && $active == 'kontak-lokasi' ? 'active' : '' }}"
                        href="{{ route('kontakLokasi.index') }}">Kontak dan Lokasi</a>
                </nav>
            </div>
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
            <a class="nav-link {{ isset($active) && $active == 'pengaturan-akun' ? 'active' : '' }}"
                href="{{ route('pengaturanAkun') }}">
                <div class="nav-link-icon"><i class="fas fa-user-gear"></i></div>
                Akun
            </a>
            <a class="nav-link {{ isset($active) && $active == 'pengaturan-sistem' ? 'active' : '' }}"
                href="{{ route('pengaturanSistem') }}">
                <div class="nav-link-icon"><i class="fas fa-gear"></i></div>
                Sistem
            </a>
            {{-- <a class="nav-link" href="#">
                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                Pengaturan
            </a> --}}
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
