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
                    <span class="text-xl lg:text-2xl uppercase">Fasilitas</span>
                    <br>
                    <span class="font-body font-normal uppercase">Program Studi Pendidikan Teknologi Informasi</span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <!-- Start Konten Halaman Utama -->
    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card -->
                <a style="background-image: url({{ asset('assets/frontpage/img/sarana-card-img.jpg') }})" class="group relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg transition"
                    href="{{ route('fasilitas.sarana.index') }}">
                    <div class="flex-auto p-4 md:p-6">
                        <h3 class="text-xl text-light-100 group-hover:text-light-600 transition"><span class="font-bold">Sarana</span><br>
                            <span class="text-base font-light">
                                Halaman ini menyajikan informasi terperinci tentang fasilitas pendidikan seperti laboratorium dan ruang kuliah yang mendukung kegiatan akademik di program studi.
                            </span>
                        </h3>
                    </div>
                    <div class="pt-0 p-4 md:p-6">
                        <div
                            class="inline-flex items-center gap-2 text-xs uppercase font-semibold text-light-100 group-hover:text-light-600 transition">
                            Lanjutkan
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </div>
                    </div>
                </a>
                <!-- End Card -->

                <!-- Card -->
                <a style="background-image: url({{ asset('assets/frontpage/img/prasarana-card-img.jpg') }})" class="group relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg transition"
                    href="{{ route('fasilitas.prasarana.index') }}">
                    <div class="flex-auto p-4 md:p-6">
                        <h3 class="text-xl text-light-100 group-hover:text-light-600 transition"><span class="font-bold">Prasarana</span><br>
                            <span class="text-base font-light">
                                Halaman ini menyajikan data komprehensif mengenai infrastruktur fisik seperti gedung dan perpustakaan yang mendukung kegiatan administratif dan akademik di program studi.
                            </span>
                        </h3>
                    </div>
                    <div class="pt-0 p-4 md:p-6">
                        <div
                            class="inline-flex items-center gap-2 text-xs uppercase font-semibold text-light-100 group-hover:text-light-600 transition">
                            Lanjutkan
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </div>
                    </div>
                </a>
                <!-- End Card -->

                <!-- Card -->
                <a style="background-image: url({{ asset('assets/frontpage/img/sisteminformasi-card-img.jpg') }})" class="group relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg transition"
                    href="{{ route('fasilitas.sistemInformasi.index') }}">
                    <div class="flex-auto p-4 md:p-6">
                        <h3 class="text-xl text-light-100 group-hover:text-light-600 transition">
                            <span class="font-bold">Sistem Informasi</span><br>
                            <span class="text-base font-light">
                                Halaman ini menyajikan informasi mengenai tautan sistem informasi terkait.
                            </span>
                        </h3>
                    </div>
                    <div class="pt-0 p-4 md:p-6">
                        <div
                            class="inline-flex items-center gap-2 text-xs uppercase font-semibold text-light-100 group-hover:text-light-600 transition">
                            Lanjutkan
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            </div>
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
