@extends('layouts.app')

@section('content')
    <!-- Start Konten Header-->
    <header class="relative">
        <img class="h-72 lg:h-96 w-full object-cover blur-[3px]" src="{{ asset('assets/frontpage/img/bg-banner2.jpg') }}"
            alt="bg-header-image">
        <div class="absolute inset-0 bg-dark-800 opacity-60"></div>
        <div class="relative max-w-[85rem] w-full mx-auto">
            <div class="absolute bottom-0 start-0 px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
                <h2 class="text-light-100 text-2xl lg:text-3xl font-display font-bold">
                    <span class="text-xl lg:text-2xl uppercase">Dosen</span>
                    <br>
                    <span class="font-body font-normal uppercase">
                        Dr. Arip Mulyanto, S.Kom., M.Kom., MCE
                    </span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <!-- Start Konten Halaman Utama -->
    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
            <div class="flex flex-col md:flex-row gap-5 md:gap-8 xl:gap-12 py-8">
                <div class="flex flex-col gap-5">
                    <img class="rounded-xl" src="{{ asset('assets/frontpage/img/aripmulyanto.jpeg') }}" alt="dosen-image">
                    <div>
                        <h5 class="font-display font-semibold text-sm text-gray-800">
                            Jenis Kelamin
                        </h5>
                        <p class="mt-1 font-body text-sm text-gray-600">Laki-laki</p>
                    </div>
                    <div>
                        <h5 class="font-display font-semibold text-sm text-gray-800">
                            Umur
                        </h5>
                        <p class="mt-1 font-body text-sm text-gray-600">47 Tahun</p>
                    </div>
                    <div>
                        <h5 class="font-display font-semibold text-sm text-gray-800">
                            Email
                        </h5>
                        <p class="mt-1 font-body text-sm text-gray-600">arip.mulyanto@ung.ac.id</p>
                    </div>
                    <div class="flex flex-col gap-y-3 border-t border-gray-300 pb-5 pt-5 md:pb-0">
                        <a href="#" target="_blank"
                            class="bg-blue-800 text-light-100 w-full py-2 px-4 rounded-lg inline-flex items-center justify-center gap-x-1 text-sm font-body font-semibold">
                            Profil Scopus
                            <i class="ri-external-link-line"></i>
                        </a>
                        <a href="#" target="_blank"
                            class="bg-blue-800 text-light-100 w-full py-2 px-4 rounded-lg inline-flex items-center justify-center gap-x-1 text-sm font-body font-semibold">
                            Profil SINTA
                            <i class="ri-external-link-line"></i>
                        </a>
                        <a href="#" target="_blank"
                            class="bg-blue-800 text-light-100 w-full py-2 px-4 rounded-lg inline-flex items-center justify-center gap-x-1 text-sm font-body font-semibold">
                            Google Scholar
                            <i class="ri-external-link-line"></i>
                        </a>
                    </div>
                </div>
                <div class="flex flex-col gap-5">
                    <div>
                        <h3 class="text-3xl font-display font-bold text-gray-800">
                            Dr. Arip Mulyanto, S.Kom., M.Kom., MCE
                        </h3>
                        <p class="mt-1 text-lg font-body text-gray-600">
                            Lektor Kepala
                        </p>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-gray-800">
                            Biografi
                        </h4>
                        <p class="mt-1 font-body text-gray-600">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nihil, officiis hic eveniet
                            laborum neque? Ut, doloribus aperiam nemo doloremque placeat commodi animi itaque minima quis
                            praesentium in pariatur enim!
                        </p>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-gray-800">
                            Pendidikan
                        </h4>
                        <ul class="mt-1 pl-4 list-disc font-body text-gray-600">
                            <li>S.Kom (Universitas Budi Luhur)</li>
                            <li>M.Kom (Universitas Indonesia)</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-gray-800">
                            Penelitian dan PKM
                        </h4>
                        <div class="mt-3 flex flex-col gap-3">
                            <nav class="flex flex-row flex-nowrap space-x-1" aria-label="Tabs" role="tablist">
                                <button type="button"
                                    class="hs-tab-active:bg-blue-800 hs-tab-active:text-light-100 hs-tab-active:border-blue-800 hs-tab-active:hover:text-light-100 py-2 px-4 inline-flex items-center gap-x-2 bg-light-100 text-sm font-body font-semibold text-center text-gray-800 border border-gray-300 hover:text-blue-800 rounded-lg disabled:opacity-50 disabled:pointer-events-none active"
                                    id="pills-with-brand-color-item-1" data-hs-tab="#pills-with-brand-color-1"
                                    aria-controls="pills-with-brand-color-1" role="tab">
                                    Publikasi Pilihan
                                </button>
                                <button type="button"
                                    class="hs-tab-active:bg-blue-800 hs-tab-active:text-light-100 hs-tab-active:border-blue-800 hs-tab-active:hover:text-light-100 py-2 px-4 inline-flex items-center gap-x-2 bg-light-100 text-sm font-body font-semibold text-center text-gray-800 border border-gray-300 hover:text-blue-800 rounded-lg disabled:opacity-50 disabled:pointer-events-none"
                                    id="pills-with-brand-color-item-2" data-hs-tab="#pills-with-brand-color-2"
                                    aria-controls="pills-with-brand-color-2" role="tab">
                                    Penelitian
                                </button>
                                <button type="button"
                                    class="hs-tab-active:bg-blue-800 hs-tab-active:text-light-100 hs-tab-active:border-blue-800 hs-tab-active:hover:text-light-100 py-2 px-4 inline-flex items-center gap-x-2 bg-light-100 text-sm font-body font-semibold text-center text-gray-800 border border-gray-300 hover:text-blue-800 rounded-lg disabled:opacity-50 disabled:pointer-events-none"
                                    id="pills-with-brand-color-item-3" data-hs-tab="#pills-with-brand-color-3"
                                    aria-controls="pills-with-brand-color-3" role="tab">
                                    Pengabdian Masyarakat
                                </button>
                            </nav>

                            <div class="w-full">
                                <div id="pills-with-brand-color-1" role="tabpanel"
                                    aria-labelledby="pills-with-brand-color-item-1" class="relative">
                                    <div class="flex flex-col gap-3">
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                The Application Of Cooperative Learning Methods In The Developing And
                                                Analyzing
                                                The Quality Of An Educational Game
                                            </span>
                                        </a>
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pendampingan masyarakat Desa Lakeya dalam Pencapaian SDGs Desa
                                            </span>
                                        </a>
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pendampingan masyarakat Desa Lakeya dalam Pencapaian SDGs Desa
                                            </span>
                                        </a>
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pendampingan masyarakat Desa Lakeya dalam Pencapaian SDGs Desa
                                            </span>
                                        </a>
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                The Application Of Cooperative Learning Methods In The Developing And
                                                Analyzing
                                                The Quality Of An Educational Game
                                            </span>
                                        </a>
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pendampingan masyarakat Desa Lakeya dalam Pencapaian SDGs Desa
                                            </span>
                                        </a>
                                    </div>
                                    <div
                                        class="absolute bottom-0 right-0 bg-gradient-to-t from-light-100 to-transparent backdrop-blur-[1px] rounded-lg w-full py-5 text-center">
                                        <a href="{{ route('profil.penelitianDosen', ['slug' => 'dr-arip-mulyanto-skom-mkom-mce', 'kategori' => 'publikasi-pilihan']) }}"
                                            class="inline-flex items-center gap-x-1 text-sm font-body font-semibold text-gray-600 hover:text-blue-800">
                                            Lihat Selengkapnya
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div id="pills-with-brand-color-2" class="hidden" role="tabpanel"
                                    aria-labelledby="pills-with-brand-color-item-2">
                                    <div class="flex flex-col gap-3">
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pengembangan Multimedia Pembelajaran Interaktif Berbasis Android Menggunakan
                                                Smart Apps Creator (SAC)
                                            </span>
                                        </a>
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pembangunan Portal Open Data untuk Mendukung Open Government dan Smart City
                                                (Studi Kasus: Pemerintah Daerah Kota Gorontalo)
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div id="pills-with-brand-color-3" class="hidden" role="tabpanel"
                                    aria-labelledby="pills-with-brand-color-item-3">
                                    <div class="flex flex-col gap-3">
                                        <a href="#" class="group inline-flex items-start gap-x-3">
                                            <i class="ri-bookmark-line text-blue-800"></i>
                                            <span class="font-body text-gray-600 group-hover:text-blue-800 transition">
                                                Pemberdayaan Masyarakat Dalam Pelestarian Budaya Gorontalo Menggunakan
                                                Aplikasi Repositori Budaya Gorontalo di Desa Mootilango Kecamatan Duhiadaa
                                                Kabupaten Gorontalo
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-gray-800">
                            Minat Penelitian
                        </h4>
                        <p class="mt-1 font-body text-gray-600">
                            Analisis Proses Bisnis Aplikasi
                        </p>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-gray-800">
                            Bidang Kepakaran
                        </h4>
                        <div class="inline-flex flex-wrap gap-2 mt-3">
                            <div>
                                <span
                                    class="py-2 px-3 inline-flex items-center gap-x-1 text-xs font-body font-semibold bg-blue-100 text-navy-900 rounded-full">
                                    <i class="ri-lightbulb-line flex-shrink-0"></i>
                                    <span>
                                        Sistem Informasi
                                    </span>
                                </span>
                            </div>
                            <div>
                                <span
                                    class="py-2 px-3 inline-flex items-center gap-x-1 text-xs font-body font-semibold bg-blue-100 text-navy-900 rounded-full">
                                    <i class="ri-lightbulb-line flex-shrink-0"></i>
                                    <span>
                                        Business Process Management
                                    </span>
                                </span>
                            </div>
                            <div>
                                <span
                                    class="py-2 px-3 inline-flex items-center gap-x-1 text-xs font-body font-semibold bg-blue-100 text-navy-900 rounded-full">
                                    <i class="ri-lightbulb-line flex-shrink-0"></i>
                                    <span>
                                        Vocational Education
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
