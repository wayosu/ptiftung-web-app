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
                    <a class="nav-link {{ isset($active) && $active == 'pengabdian-masyarakat' ? 'active' : '' }}"
                        href="{{ route('pengabdianMasyarakat.index') }}">Pengabdian Masyarakat</a>
                    <a class="nav-link {{ isset($active) && $active == 'publikasi' ? 'active' : '' }}"
                        href="{{ route('publikasi.index') }}">Publikasi</a>
                </nav>
            </div>
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
                    <a class="nav-link {{ isset($active) && $active == 'pendaftaran-mahasiswa-baru' ? 'active' : '' }}"
                        href="{{ route('pendaftaranMahasiswaBaru.index') }}">Pendaftaran Mahasiswa Baru</a>
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
                    <a class="nav-link text-muted disabled" href="#">Alumni <i
                            class="fas fa-lock fa-xs ms-1"></i></a>
                </nav>
            </div>
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
                        href="{{ route('dataDukungAkreditasi.index') }}">Data Dukung Akreditasi 2023</a>
                </nav>
            </div>
            <a class="nav-link {{ isset($active) && ($active == 'banner' || $active == 'berita' || $active == 'agenda' || $active == 'jurnal') ? 'active' : 'collapsed' }}"
                href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#konten" aria-expanded="false"
                aria-controls="konten">
                <div class="nav-link-icon"><i data-feather="layout"></i></div>
                Konten
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ isset($active) && ($active == 'banner' || $active == 'berita' || $active == 'agenda' || $active == 'jurnal') ? 'show' : '' }}"
                id="konten" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                    <a class="nav-link {{ isset($active) && $active == 'banner' ? 'active' : '' }}"
                        href="{{ route('banner.index') }}">Banner</a>
                    <a class="nav-link {{ isset($active) && $active == 'berita' ? 'active' : '' }}"
                        href="#">Berita</a>
                    <a class="nav-link {{ isset($active) && $active == 'agenda' ? 'active' : '' }}"
                        href="#">Agenda</a>
                    <a class="nav-link {{ isset($active) && $active == 'jurnal' ? 'active' : '' }}"
                        href="#">Jurnal</a>
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
