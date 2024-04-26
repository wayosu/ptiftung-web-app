<!-- ========== HEADER ========== -->
<header id="stickyNavbar"
    class="flex flex-wrap sm:justify-start sm:flex-nowrap z-50 w-full bg-light-100 text-sm py-3 sm:py-0 sticky top-0 transition-all">
    <nav class="relative max-w-[85rem] flex flex-wrap basis-full items-center w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8 py-0 sm:py-2"
        aria-label="Global">
        <div class="flex items-center justify-between">
            <a class="flex-none text-xl font-semibold" href="{{ route('beranda') }}" aria-label="Brand">
                <img src="{{ asset('assets/frontpage/img/logo-pti.png') }}" alt="logo"
                    class="w-[360px] lg:w-[380px] hidden sm:block">
                <img src="{{ asset('assets/frontpage/img/new-logo-mini.png') }}" alt="logo"
                    class="w-[100px] block sm:hidden">
            </a>
        </div>

        <div class="flex items-center ms-auto sm:ms-0 sm:order-2">
            <div class="xl:hidden">
                <button type="button"
                    class="p-2 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-800 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-navy-900 transition-all text-xs"
                    data-hs-overlay="#navbarOffCanvas" aria-controls="navbarOffCanvas" aria-label="Toggle navigation">
                    <i class="ri-menu-line hs-overlay-open:hidden size-4"></i>
                </button>
            </div>
        </div>

        <div class="order-2 static hidden xl:block h-auto max-w-none w-auto transition-none translate-x-0 z-40 basis-auto bg-light-100"
            tabindex="-1">
            <div
                class="flex flex-col gap-y-4 gap-x-0 mt-5 xl:flex-row xl:items-center xl:justify-end xl:gap-y-0 xl:gap-x-7 xl:mt-0 xl:ps-7">

                <div
                    class="hs-dropdown [--strategy:static] xl:[--strategy:fixed] [--adaptive:none] xl:[--trigger:hover] xl:py-4">
                    <button type="button"
                        class="flex items-center justify-between xl:justify-start w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6 xl:px-0">
                        Profil
                        <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 bg-light-100 xl:shadow-md py-2 px-3 xl:px-2 before:absolute top-full xl:border before:-top-5 before:start-0 before:w-full before:h-5">
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="{{ route('profil.sejarah') }}">
                            Sejarah PTI
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="{{ route('profil.visiTujuanStrategi') }}">
                            Visi, Tujuan dan Strategi
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="{{ route('profil.strukturOrganisasi') }}">
                            Struktur Organisasi
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="{{ route('profil.dosen') }}">
                            Dosen
                        </a>

                        <div
                            class="hs-dropdown relative [--strategy:static] xl:[--strategy:absolute] [--adaptive:none] xl:[--trigger:hover]">
                            <button type="button"
                                class="w-full flex justify-between items-center rounded-lg text-sm text-gray-800 py-2 px-3 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200">
                                Fasilitas
                                <span class="xl:-rotate-90 flex-shrink-0 ms-2 size-4">
                                    <i class="ri-arrow-down-s-line"></i>
                                </span>
                            </button>

                            <div
                                class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 xl:mt-2 bg-light-100 xl:shadow-md p-2 before:absolute xl:border before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="{{ route('fasilitas.sarana.index') }}">
                                    Sarana
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="{{ route('fasilitas.prasarana.index') }}">
                                    Prasarana
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="{{ route('fasilitas.sistemInformasi.index') }}">
                                    Sistem Informasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="hs-dropdown [--strategy:static] xl:[--strategy:fixed] [--adaptive:none] xl:[--trigger:hover] xl:py-4">
                    <button type="button"
                        class="flex items-center justify-between xl:justify-start w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6 xl:px-0">
                        Akademik
                        <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 bg-light-100 xl:shadow-md py-2 px-3 xl:px-2 before:absolute top-full xl:border before:-top-5 before:start-0 before:w-full before:h-5">
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Profil Lulusan
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Capaian Pembelajaran
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Kurikulum
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Kalender Akademik
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Kegiatan Perkuliahan
                        </a>
                    </div>
                </div>

                <div
                    class="hs-dropdown [--strategy:static] xl:[--strategy:fixed] [--adaptive:none] xl:[--trigger:hover] xl:py-4">
                    <button type="button"
                        class="flex items-center justify-between xl:justify-start w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6 xl:px-0">
                        Penelitian dan PKM
                        <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 bg-light-100 xl:shadow-md py-2 px-3 xl:px-2 before:absolute top-full xl:border before:-top-5 before:start-0 before:w-full before:h-5">
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Penelitan
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Pengabdian Masyarakat
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Publikasi
                        </a>
                    </div>
                </div>

                <div
                    class="hs-dropdown [--strategy:static] xl:[--strategy:fixed] [--adaptive:none] xl:[--trigger:hover] xl:py-4">
                    <button type="button"
                        class="flex items-center justify-between xl:justify-start w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6 xl:px-0">
                        Mahasiswa dan Alumni
                        <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 bg-light-100 xl:shadow-md py-2 px-3 xl:px-2 before:absolute top-full xl:border before:-top-5 before:start-0 before:w-full before:h-5">
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Pendaftaran Mahasiswa Baru
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Prestasi Mahasiswa
                        </a>

                        <div
                            class="hs-dropdown relative [--strategy:static] xl:[--strategy:absolute] [--adaptive:none] xl:[--trigger:hover]">
                            <button type="button"
                                class="w-full flex justify-between items-center rounded-lg text-sm text-gray-800 py-2 px-3 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200">
                                Peluang Mahasiswa
                                <span class="xl:-rotate-90 flex-shrink-0 ms-2 size-4">
                                    <i class="ri-arrow-down-s-line"></i>
                                </span>
                            </button>

                            <div
                                class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 xl:mt-2 bg-light-100 xl:shadow-md p-2 before:absolute xl:border before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Beasiswa
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Exchange dan Double Degree
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Seminar dan Kompetisi
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Magang/Praktik Industri
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Lowongan Kerja
                                </a>
                            </div>
                        </div>

                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Organisasi Mahasiswa
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Alumni
                        </a>
                    </div>
                </div>

                <div
                    class="hs-dropdown [--strategy:static] xl:[--strategy:fixed] [--adaptive:none] xl:[--trigger:hover] xl:py-4">
                    <button type="button"
                        class="flex items-center justify-between xl:justify-start w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6 xl:px-0">
                        Repository
                        <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 bg-light-100 xl:shadow-md py-2 px-3 xl:px-2 before:absolute top-full xl:border before:-top-5 before:start-0 before:w-full before:h-5">
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Dokumen Kebijakan
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Dokumen Lainnya
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Data Dukung Akreditasi 2023
                        </a>
                    </div>
                </div>

                <div
                    class="hs-dropdown [--strategy:static] xl:[--strategy:fixed] [--adaptive:none] xl:[--trigger:hover] xl:py-4">
                    <button type="button"
                        class="flex items-center justify-between xl:justify-start w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6 xl:px-0">
                        Informasi
                        <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 bg-light-100 xl:shadow-md py-2 px-3 xl:px-2 before:absolute top-full xl:border before:-top-5 before:start-0 before:w-full before:h-5">
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Berita
                        </a>
                        <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                            href="#">
                            Agenda
                        </a>

                        <div
                            class="hs-dropdown relative [--strategy:static] xl:[--strategy:absolute] [--adaptive:none] xl:[--trigger:hover]">
                            <button type="button"
                                class="w-full flex justify-between items-center rounded-lg text-sm text-gray-800 py-2 px-3 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200">
                                Jurnal
                                <span class="xl:-rotate-90 flex-shrink-0 ms-2 size-4">
                                    <i class="ri-arrow-down-s-line"></i>
                                </span>
                            </button>

                            <div
                                class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 xl:mt-2 bg-light-100 xl:shadow-md p-2 before:absolute xl:border before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Jurnal Inverted
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Jurnal JJI
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Jurnal Teknik
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Jurnal Diffusion
                                </a>
                                <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                    href="#">
                                    Jurnal Devotion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<div id="navbarOffCanvas"
    class="hs-overlay hs-overlay-open:translate-x-0 hidden translate-x-full fixed top-0 end-0 transition-all duration-300 transform h-full max-w-xs w-full z-[80] bg-light-100 border-s"
    tabindex="-1" [--body-scroll:true] data-hs-overlay-close-on-resize>
    <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
        <h3 class="font-body font-bold text-gray-800 text-xl sm:text-base">
            Menu
        </h3>
        <button type="button"
            class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-navy-900 transition-all"
            data-hs-overlay="#navbarOffCanvas">
            <span class="sr-only">Close modal</span>
            <span class="flex-shrink-0">
                <i class="ri-close-line"></i>
            </span>
        </button>
    </div>

    <div
        class="order-2 static block h-auto max-w-none w-auto transition-none translate-x-0 z-40 basis-auto bg-light-100">
        <div class="flex flex-col gap-y-4 gap-x-0 mt-5">
            <div class="hs-dropdown [--strategy:static]">
                <button type="button"
                    class="flex items-center justify-between w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6">
                    Profil
                    <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                </button>

                <div
                    class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 py-2 px-3 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Sejarah PTI
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Visi, Tujuan dan Strategi
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Struktur Organisasi
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Dosen
                    </a>

                    <div class="hs-dropdown relative [--strategy:static] [--adaptive:none]">
                        <button type="button"
                            class="w-full flex justify-between items-center rounded-lg text-sm text-gray-800 py-2 px-3 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200">
                            Fasilitas
                            <span class="flex-shrink-0 ms-2 size-4">
                                <i class="ri-arrow-down-s-line"></i>
                            </span>
                        </button>

                        <div
                            class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 p-2 before:absolute before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Sarana
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Prasarana
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Sistem Informasi
                            </a>
                        </div>
                    </div>

                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Kontak dan Lokasi
                    </a>
                </div>
            </div>

            <div class="hs-dropdown [--strategy:static]">
                <button type="button"
                    class="flex items-center justify-between w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6">
                    Akademik
                    <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                </button>

                <div
                    class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 py-2 px-3 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Profil Lulusan
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Capaian Pembelajaran
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Kurikulum
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Kalender Akademik
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Kegiatan Perkuliahan
                    </a>
                </div>
            </div>

            <div class="hs-dropdown [--strategy:static]">
                <button type="button"
                    class="flex items-center justify-between w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6">
                    Penelitian dan PKM
                    <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                </button>

                <div
                    class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 py-2 px-3 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Penelitan
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Pengabdian Masyarakat
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Publikasi
                    </a>
                </div>
            </div>

            <div class="hs-dropdown [--strategy:static]">
                <button type="button"
                    class="flex items-center justify-between w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6">
                    Mahasiswa dan Alumni
                    <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                </button>

                <div
                    class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 py-2 px-3 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Pendaftaran Mahasiswa Baru
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Prestasi Mahasiswa
                    </a>

                    <div class="hs-dropdown relative [--strategy:static] [--adaptive:none]">
                        <button type="button"
                            class="w-full flex justify-between items-center rounded-lg text-sm text-gray-800 py-2 px-3 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200">
                            Peluang Mahasiswa
                            <span class="flex-shrink-0 ms-2 size-4">
                                <i class="ri-arrow-down-s-line"></i>
                            </span>
                        </button>

                        <div
                            class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 p-2 before:absolute before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Beasiswa
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Exchange dan Double Degree
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Seminar dan Kompetisi
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Magang/Praktik Industri
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Lowongan Kerja
                            </a>
                        </div>
                    </div>

                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Organisasi Mahasiswa
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Alumni
                    </a>
                </div>
            </div>

            <div class="hs-dropdown [--strategy:static]">
                <button type="button"
                    class="flex items-center justify-between w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6">
                    Repository
                    <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                </button>

                <div
                    class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 py-2 px-3 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Dokumen Kebijakan
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Dokumen Lainnya
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Data Dukung Akreditasi 2023
                    </a>
                </div>
            </div>

            <div class="hs-dropdown [--strategy:static]">
                <button type="button"
                    class="flex items-center justify-between w-full text-base sm:text-sm text-dark-600 hover:text-navy-800 font-body font-semibold px-6">
                    Informasi
                    <i class="ri-arrow-down-s-line flex-shrink-0 ms-2"></i>
                </button>

                <div
                    class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-light-100 py-2 px-3 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Berita
                    </a>
                    <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 font-body text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                        href="#">
                        Agenda
                    </a>

                    <div
                        class="hs-dropdown relative [--strategy:static] xl:[--strategy:absolute] [--adaptive:none] xl:[--trigger:hover]">
                        <button type="button"
                            class="w-full flex justify-between items-center rounded-lg text-sm text-gray-800 py-2 px-3 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200">
                            Jurnal
                            <span class="xl:-rotate-90 flex-shrink-0 ms-2 size-4">
                                <i class="ri-arrow-down-s-line"></i>
                            </span>
                        </button>

                        <div
                            class="hs-dropdown-menu rounded-lg transition-[opacity,margin] duration-[0.1ms] xl:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 xl:w-48 hidden z-10 xl:mt-2 bg-light-100 xl:shadow-md p-2 before:absolute xl:border before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Jurnal Inverted
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Jurnal JJI
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Jurnal Teknik
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Jurnal Diffusion
                            </a>
                            <a class="flex items-center gap-x-3.5 rounded-lg py-2 px-3 text-sm text-gray-800 hover:bg-gray-200 focus:ring-2 focus:ring-gray-200"
                                href="#">
                                Jurnal Devotion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ========== END HEADER ========== -->
