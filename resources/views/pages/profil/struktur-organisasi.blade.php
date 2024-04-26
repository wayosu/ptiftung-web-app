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
                    <span class="text-xl lg:text-2xl uppercase">Struktur Organisasi</span>
                    <br>
                    <span class="font-body font-normal uppercase">Program Studi Pendidikan Teknologi Informasi</span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <!-- Start Konten Halaman Utama -->
    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
            <div class="grid lg:grid-cols-3 gap-y-0 lg:gap-y-0 lg:gap-x-6">
                <!-- Konten -->
                <div class="lg:col-span-2">
                    <div class="py-8 lg:pe-8">
                        <img src="{{ asset('assets/frontpage/img/struktur-org.jpg') }}" alt="struktur-organisasi-image">
                    </div>
                </div>
                <!-- End Konten -->

                <!-- Sidebar -->
                <div
                    class="lg:col-span-1 lg:w-full lg:h-full lg:bg-gradient-to-r lg:from-gray-50 lg:via-transparent lg:to-transparent">
                    <div class="sticky top-16 start-0 pt-0 pb-8 lg:py-8 lg:ps-8">
                        <!-- Avatar Media -->
                        <div class="border-t lg:border-t-0 pt-8 lg:pt-0 border-b border-gray-200 pb-8 mb-8">
                            <h2 class="text-xl font-display font-bold">Profil</h2>
                        </div>
                        <!-- End Avatar Media -->

                        <div class="space-y-6">
                            <a class="group flex items-center gap-x-6" href="#">
                                <div>
                                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                                        <i class="ri-arrow-right-line me-2"></i>
                                        Sejarah PTI
                                    </span>
                                </div>
                            </a>
                            <a class="group flex items-center gap-x-6" href="#">
                                <div>
                                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                                        <i class="ri-arrow-right-line me-2"></i>
                                        Visi, Tujuan dan Strategi
                                    </span>
                                </div>
                            </a>
                            <a class="group flex items-center gap-x-6" href="#">
                                <div>
                                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                                        <i class="ri-arrow-right-line me-2"></i>
                                        Struktur Organisasi
                                    </span>
                                </div>
                            </a>
                            <a class="group flex items-center gap-x-6" href="#">
                                <div>
                                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                                        <i class="ri-arrow-right-line me-2"></i>
                                        Dosen
                                    </span>
                                </div>
                            </a>
                            <a class="group flex items-center gap-x-6" href="#">
                                <div>
                                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                                        <i class="ri-arrow-right-line me-2"></i>
                                        Kontak dan Lokasi
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Sidebar -->
            </div>
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
