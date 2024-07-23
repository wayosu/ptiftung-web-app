@extends('layouts.app')

@section('content')
    <!-- Slider -->
    <div class="swiper w-[100%] h-[full]">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            @if (isset($banners) && count($banners) > 0)
                @foreach ($banners as $banner)                    
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/konten/banner/' . $banner) }}" alt="banner" class="w-full h-full object-cover">
                    </div>
                @endforeach
            @else
                <div class="swiper-slide">
                    <img src="{{ asset('assets/admin/img/no-image-placeholder.png') }}" alt="banner" class="w-full h-[80vh] object-cover">
                </div>
            @endif
        </div>
    </div>
    <!-- End Slider -->

    <!-- Fun Fact -->
    <section id="fun-fact" class="bg-navy-900">
        <div class="relative max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto overflow-hidden">
            <div class="flex flex-col gap-10">
                <div class="w-full max-w-5xl mx-auto text-center">
                    <p class="text-xl md:text-2xl font-body font-normal text-light-100">
                        <span class="uppercase font-semibold">Visi Keilmuan</span>
                        "{{ htmlspecialchars(trim(strip_tags($visiKeilmuan ?? ''))) }}"
                    </p>
                </div>
                <div class="flex flex-row flex-wrap w-full mx-auto items-center justify-center gap-6 md:gap-20">
                    <div class="flex items-center gap-3">
                        <span
                            class="text-2xl md:text-3xl bg-light-100 text-navy-900 rounded-full w-14 md:w-16 h-14 md:h-16 flex items-center justify-center hover:bg-blue-800 hover:text-light-100 transition-all">
                            <i class="ri-user-fill"></i>
                        </span>
                        <div class="flex flex-col">
                            <h1 class="text-3xl md:text-4xl text-light-100 font-body font-bold">1,100±</h1>
                            <span class="text-sm md:text-base text-light-100 font-body">Mahasiswa</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="text-2xl md:text-3xl bg-light-100 text-navy-900 rounded-full w-14 md:w-16 h-14 md:h-16 flex items-center justify-center hover:bg-blue-800 hover:text-light-100 transition-all">
                            <i class="ri-graduation-cap-fill"></i>
                        </span>
                        <div class="flex flex-col">
                            <h1 class="text-3xl md:text-4xl text-light-100 font-body font-bold">1,000±</h1>
                            <span class="text-sm md:text-base text-light-100 font-body">Lulusan</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="text-2xl md:text-3xl bg-light-100 text-navy-900 rounded-full w-14 md:w-16 h-14 md:h-16 flex items-center justify-center hover:bg-blue-800 hover:text-light-100 transition-all">
                            <i class="ri-briefcase-fill"></i>
                        </span>
                        <div class="flex flex-col">
                            <h1 class="text-3xl md:text-4xl text-light-100 font-body font-bold">17</h1>
                            <span class="text-sm md:text-base text-light-100 font-body">Tanaga Pengajar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Fun Fact -->

    <!-- Start Sambutan Ketua Program Studi -->
    <section id="sambutan-ketua" class="relative overflow-hidden">
        {{-- start pattern --}}
        {{-- <div class="absolute top-0 end-0 lg:start-0 z-[0] opacity-50 lg:opacity-100">
            <img src="{{ asset('assets/frontpage/img/square-pattern.png') }}" alt="pattern-png" class="w-[60px]">
        </div> --}}
        {{-- end pattern --}}
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="md:flex md:gap-10 lg:gap-16 md:items-center">
                <div class="hidden md:block mb-24 md:mb-0 sm:px-6">
                    <div class="relative w-full">
                        <img class="h-full" src="{{ asset('assets/frontpage/img/arifdwinanto-3.png') }}"
                            alt="Image Description">
                    </div>
                </div>

                <div>
                    <!-- Blockquote -->
                    <blockquote class="relative">
                        <svg class="absolute top-0 start-0 transform -translate-x-8 -translate-y-4 size-24 text-gray-200"
                            width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z"
                                fill="currentColor" />
                        </svg>

                        <div class="relative z-10">
                            <p class="text-xs font-display font-semibold text-gray-500 tracking-wide uppercase mb-3">
                                Sambutan Ketua Program Studi
                            </p>

                            <p
                                class="text-lg font-body font-semibold italic text-gray-800 md:text-xl md:leading-normal xl:text-2xl xl:leading-normal">
                                Selamat datang di halaman resmi Program Studi Pendidikan Teknologi Informasi!.
                                Kami bertujuan untuk memberikan pendidikan yang
                                berkualitas dan relevan dalam bidang teknologi informasi.
                            </p>
                        </div>

                        <footer class="mt-6">
                            <div class="flex items-center">
                                <div class="md:hidden flex-shrink-0">
                                    <img class="size-12 rounded-full"
                                        src="{{ asset('assets/frontpage/img/arifdwinanto-2.png') }}"
                                        alt="Image Description">
                                </div>
                                <div class="ms-4 md:ms-0">
                                    <div class="text-base font-display font-semibold text-gray-800">Arif Dwinanto,
                                        S.Si, M.Pd
                                    </div>
                                    <div class="text-xs text-gray-500 font-body">Ketua Program Studi Pendidikan Teknologi
                                        Informasi
                                    </div>
                                </div>
                            </div>
                        </footer>

                        {{-- <div class="mt-8 lg:mt-14">
                            <a class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-navy-900 text-white hover:bg-blue-800 disabled:opacity-50 disabled:pointer-events-none transition-all"
                                href="#">
                                <span class="rounded-lg py-2 px-4 text-light-100 font-body">
                                    Selengkapnya
                                    <i class="ri-arrow-right-line ms-2"></i>
                                </span>
                            </a>
                        </div> --}}
                    </blockquote>
                    <!-- End Blockquote -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Sambutan Ketua Program Studi -->

    <!-- Start Program -->
    <section id="program" class="relative bg-gray-100 overflow-hidden">
        <div class="relative z-10 max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <!-- Grid -->
            <div class="grid sm:grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6">
                <!-- Card -->
                <a class="group flex flex-col bg-light-100 border shadow-sm rounded-xl hover:shadow-md transition"
                    href="#">
                    <div class="p-4 md:p-5">
                        <div class="flex flex-row lg:flex-col items-start lg:items-center gap-0 lg:gap-4">
                            <div
                                class="group-hover:text-blue-800 flex-shrink-0 size-5 text-2xl text-gray-800 lg:bg-navy-900 lg:rounded-full lg:flex lg:justify-center lg:items-center lg:w-12 lg:h-12 lg:text-light-100 group-hover:lg:bg-blue-800 group-hover:lg:text-light-100 transition-all">
                                <i class="ri-edit-2-line"></i>
                            </div>

                            <div class="grow ms-5 lg:ms-0 text-start lg:text-center">
                                <h3
                                    class="group-hover:text-blue-800 font-display font-semibold text-navy-900 mb-1 lg:text-lg transition-all">
                                    Pendaftaran
                                </h3>
                                <p class="text-sm text-gray-500 font-body">
                                    Pendaftarana mahasiswa baru di Program Studi Pendidikan Teknologi Informasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- End Card -->

                <!-- Card -->
                <a class="group flex flex-col bg-light-100 border shadow-sm rounded-xl hover:shadow-md transition"
                    href="{{ route('fasilitas.index') }}">
                    <div class="p-4 md:p-5">
                        <div class="flex flex-row lg:flex-col items-start lg:items-center gap-0 lg:gap-4">
                            <div
                                class="group-hover:text-blue-800 flex-shrink-0 size-5 text-2xl text-gray-800 lg:bg-navy-900 lg:rounded-full lg:flex lg:justify-center lg:items-center lg:w-12 lg:h-12 lg:text-light-100 group-hover:lg:bg-blue-800 group-hover:lg:text-light-100 transition-all">
                                <i class="ri-building-line"></i>
                            </div>

                            <div class="grow ms-5 lg:ms-0 text-start lg:text-center">
                                <h3
                                    class="group-hover:text-blue-800 font-display font-semibold text-navy-900 mb-1 lg:text-lg transition-all">
                                    Fasilitas
                                </h3>
                                <p class="text-sm text-gray-500 font-body">
                                    Fasilitas yang tersedia di Program Studi Pendidikan Teknologi Informasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- End Card -->

                <!-- Card -->
                <a class="group flex flex-col bg-light-100 border shadow-sm rounded-xl hover:shadow-md transition"
                    href="#">
                    <div class="p-4 md:p-5">
                        <div class="flex flex-row lg:flex-col items-start lg:items-center gap-0 lg:gap-4">
                            <div
                                class="group-hover:text-blue-800 flex-shrink-0 size-5 text-2xl text-gray-800 lg:bg-navy-900 lg:rounded-full lg:flex lg:justify-center lg:items-center lg:w-12 lg:h-12 lg:text-light-100 group-hover:lg:bg-blue-800 group-hover:lg:text-light-100 transition-all">
                                <i class="ri-open-arm-line"></i>
                            </div>

                            <div class="grow ms-5 lg:ms-0 text-start lg:text-center">
                                <h3
                                    class="group-hover:text-blue-800 font-display font-semibold text-navy-900 mb-1 lg:text-lg transition-all">
                                    Beasiswa
                                </h3>
                                <p class="text-sm text-gray-500 font-body">
                                    Beasiswa yang tersedia di Program Studi Pendidikan Teknologi Informasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            </div>
            <!-- End Grid -->
        </div>
    </section>
    <!-- End Program -->

    <!-- Start Video Profil -->
    <section id="video-profil" class="relative">
        {{-- start pattern --}}
        {{-- <div class="absolute bottom-0 start-0 z-[0] opacity-50 lg:opacity-100">
            <img src="{{ asset('assets/frontpage/img/square-pattern.png') }}" alt="pattern-png" class="w-[60px]">
        </div> --}}
        {{-- end pattern --}}
        <div class="relative overflow-hidden py-10">
            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 mb-6 lg:mb-14">
                <div class="flex items-center gap-x-3 w-full">
                    <div class="border-yellow-900 border-b-2 w-full sm:w-1/2"></div>
                    <h1
                        class="block text-2xl text-navy-900 font-display font-bold md:text-4xl md:leading-tight text-nowrap">
                        Video Profil
                    </h1>
                    <div class="border-yellow-900 border-b-2 w-full sm:w-1/2"></div>
                </div>
                <p class="mt-1 text-lg text-gray-600 font-body text-center">
                    Video Profil Program Studi Pendidikan Teknologi Informasi.
                </p>
            </div>

            <div class="relative max-w-5xl mx-auto">
                <div class="w-full object-cover h-[280px] sm:h-[480px] bg-no-repeat bg-center bg-cover rounded-xl">
                    <iframe src="{{ $videoProfil ?? '' }}"
                        class="w-full h-full rounded-none xl:rounded-xl" title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>

                <div
                    class="absolute bottom-12 -start-20 -z-[1] size-48 bg-gradient-to-b from-yellow-900 to-light-100 p-px rounded-lg">
                    <div class="bg-light-100 size-48 rounded-lg"></div>
                </div>

                <div
                    class="absolute -top-12 -end-20 -z-[1] size-48 bg-gradient-to-t from-blue-900 to-navy-400 p-px rounded-full">
                    <div class="bg-light-100 size-48 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Video Profil -->

    <!-- Start Berita -->
    <section id="berita" class="relative bg-gray-100 overflow-hidden">
        {{-- start pattern --}}
        {{-- <div class="absolute bottom-0 end-0 z-[0] rotate-180 opacity-50 lg:opacity-100">
            <img src="{{ asset('assets/frontpage/img/square-pattern.png') }}" alt="pattern-png" class="w-[60px]">
        </div> --}}
        {{-- end pattern --}}

        <div class="relative max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto z-10">
            <!-- Title -->
            <div class="w-full text-start mb-6 lg:mb-14">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-2 w-full">
                        <h2 class="text-2xl text-nowrap text-navy-900 font-display font-bold md:text-4xl md:leading-tight">
                            Rilis Berita
                        </h2>
                        <div class="border-yellow-900 border-b-2 w-full sm:w-1/2"></div>
                    </div>
                    <a class="hidden lg:flex justify-between w-auto text-sm font-body font-semibold py-2 border-b border-navy-900 text-navy-900 disabled:opacity-50 disabled:pointer-events-none transition-all"
                        href="#">
                        <span class="text-nowrap uppercase">
                            Lihat Berita Lainnya
                        </span>
                        <span class="flex-shrink-0">
                            <i class="ri-arrow-right-line ms-2"></i>
                        </span>
                    </a>
                </div>

                <p class="mt-1 font-body text-gray-600">Berisi informasi maupun pengumuman terkait
                    Program Studi Pendidikan Teknologi Informasi.</p>
            </div>
            <!-- End Title -->

            <!-- Grid -->
            @if (isset($beritas) && count($beritas) > 0)
                <div class="grid sm:grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach ($beritas as $berita)
                        <!-- Card -->
                        <a class="group flex flex-row lg:flex-col h-full bg-light-100 border border-gray-200 hover:border-transparent hover:shadow-lg transition-all duration-300 rounded-xl p-0 lg:p-5 overflow-hidden"
                            href="#">
                            <div class="w-[50%] sm:w-[20%] lg:w-auto lg:aspect-w-16 lg:aspect-h-11">
                                <img class="w-full h-full object-cover rounded-r-none lg:rounded-xl"
                                    src="{{ asset('storage/konten/berita/' . $berita->thumbnail ?? '') }}" alt="{{ $berita->judul ?? 'img' . '-Thumbnail' }}">
                            </div>
                            <div class="flex flex-col p-3 lg:p-0 lg:mt-6 w-full lg:w-auto">
                                <div>
                                    <h3
                                        class="text-base lg:text-xl font-display font-semibold text-gray-800 group-hover:text-blue-800 transition-all">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($berita->judul ?? ''), 40, ' ...') }}
                                    </h3>
                                    <p class="hidden lg:block lg:mb-0 lg:mt-5 font-body text-gray-600">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($berita->deskripsi ?? ''), 135, ' ...') }}
                                    </p>
                                </div>
        
                                <div class="mt-3 flex items-center gap-x-2 border-t pt-2 lg:mt-6">
                                    <i class="ri-calendar-line text-gray-600 text-sm"></i>
                                    <div>
                                        <h5 class="text-sm font-body text-gray-600">
                                            {{ \Carbon\Carbon::parse($berita->created_at)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Card -->
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-600 border rounded p-3">Tidak ada berita.</p>
            @endif
            <!-- End Grid -->

            <!-- Card -->
            <div class="mt-6 lg:mt-12 lg:text-center lg:hidden">
                <a class="flex justify-between w-full text-sm font-body font-semibold py-2 border-b border-navy-900 text-navy-900 disabled:opacity-50 disabled:pointer-events-none transition-all"
                    href="#">
                    <span class="uppercase">
                        Lihat Berita Lainnya
                    </span>
                    <span class="flex-shrink-0">
                        <i class="ri-arrow-right-line ms-2"></i>
                    </span>
                </a>
            </div>
            <!-- End Card -->
        </div>
    </section>
    <!-- End Berita -->

    <!-- Start Mahasiswa dan Alumni -->
    <section id="mahasiswa-dan-alumni" class="relative bg-navy-900 overflow-hidden">
        {{-- start pattern --}}
        {{-- <div class="absolute bottom-1 lg:top-1/2 -start-32 z-[0] opacity-20">
            <img src="{{ asset('assets/frontpage/img/circle-pattern.png') }}" alt="pattern-png" class="w-[200px]">
        </div>
        <div class="absolute bottom-1/3 end-0 lg:top-1/4 lg:start-0 z-[0] opacity-20">
            <img src="{{ asset('assets/frontpage/img/circle-pattern.png') }}" alt="pattern-png" class="w-[160px]">
        </div>
        <div class="absolute hidden lg:block lg:top-3/4 start-14 z-[0] opacity-20">
            <img src="{{ asset('assets/frontpage/img/circle-pattern.png') }}" alt="pattern-png" class="w-[130px]">
        </div> --}}
        {{-- end pattern --}}

        <div
            class="relative z-10 flex flex-col gap-10 lg:flex-row items-center max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <!-- Grid -->
            <div class="w-full flex flex-col gap-5">
                <a href="#" class="group w-full p-5 rounded-lg bg hover:bg-light-100 transition-all duration-300">
                    <h3
                        class="text-lg font-display font-bold text-light-100 group-hover:text-blue-800 transition-all duration-300">
                        Prestasi Mahasiswa
                    </h3>
                    <p class="mt-2 font-body text-gray-400 group-hover:text-gray-600 transition-all duration-300">
                        Di Program Studi Pendidikan Teknologi Informasi, mahasiswa telah menorehkan berbagai prestasi yang
                        telah diraih.
                    </p>
                </a>
                <a href="#" class="group w-full p-5 rounded-lg hover:bg-light-100 transition-all duration-300">
                    <h3
                        class="text-lg font-display font-bold text-light-100 group-hover:text-blue-800 transition-all duration-300">
                        Organisasi Mahasiswa
                    </h3>
                    <p class="mt-2 font-body text-gray-400 group-hover:text-gray-600 transition-all duration-300">
                        Organisasi mahasiswa di Program Studi Pendidikan Teknologi Informasi menjadi wadah penting bagi
                        mahasiswa untuk mengembangkan minat dan bakat mereka dalam bidang teknologi informasi.
                    </p>
                </a>
                <a href="#" class="group w-full p-5 rounded-lg hover:bg-light-100 transition-all duration-300">
                    <h3
                        class="text-lg font-display font-bold text-light-100 group-hover:text-blue-800 transition-all duration-300">
                        Alumni
                    </h3>
                    <p class="mt-2 font-body text-gray-400 group-hover:text-gray-600 transition-all duration-300">
                        Para alumni dari program studi kami yang telah menyelesaikan studi pendidikannya.
                    </p>
                </a>
            </div>
            <!-- End Grid -->

            <div class="w-auto">
                <img class="w-[500px] lg:w-full object-cover" src="{{ asset('assets/frontpage/img/bg-mahasiswa.png') }}"
                    alt="bg-mahasiswa-dan-alumni">
            </div>
        </div>
    </section>
    <!-- End Mahasiswa dan Alumni -->

    <!-- Start Agenda -->
    <section id="agenda" class="relative bg-gray-100 overflow-hidden">
        {{-- start pattern --}}
        {{-- <div class="absolute -top-0.5 -start-0.5 z-[0] rotate-180 opacity-50 lg:opacity-100">
            <img src="{{ asset('assets/frontpage/img/square-pattern-2.png') }}" alt="pattern-png" class="w-[60px]">
        </div> --}}
        {{-- end pattern --}}

        <div class="relative max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto z-10">
            <!-- Title -->
            <div class="w-full text-start mb-6 lg:mb-14">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-2 w-full">
                        <h2 class="text-2xl text-nowrap text-navy-900 font-display font-bold md:text-4xl md:leading-tight">
                            Agenda Terbaru
                        </h2>
                        <div class="border-yellow-900 border-b-2 w-full sm:w-1/2"></div>
                    </div>
                    <a class="hidden lg:flex justify-between w-auto text-sm font-body font-semibold py-2 border-b border-navy-900 text-navy-900 disabled:opacity-50 disabled:pointer-events-none transition-all"
                        href="#">
                        <span class="text-nowrap uppercase">
                            Lihat Agenda Lainnya
                        </span>
                        <span class="flex-shrink-0">
                            <i class="ri-arrow-right-line ms-2"></i>
                        </span>
                    </a>
                </div>

                <p class="mt-1 font-body text-gray-600">
                    Agenda terkait Program Studi Pendidikan Teknologi Informasi.
                </p>
            </div>
            <!-- End Title -->

            <!-- Grid -->
            @if (isset($agendas) && count($agendas) > 0)
                <div class="grid sm:grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach ($agendas as $agenda)
                        <!-- Card -->
                        <a class="group flex flex-row lg:flex-col h-full bg-light-100 border border-gray-200 hover:border-transparent hover:shadow-lg transition-all duration-300 rounded-xl p-0 lg:p-5 overflow-hidden"
                            href="#">
                            <div class="flex flex-col p-3 lg:p-0 w-full lg:w-auto">
                                <div>
                                    <h3
                                        class="text-base lg:text-xl font-display font-semibold text-gray-800 group-hover:text-blue-800 transition-all">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($agenda->judul ?? ''), 40, ' ...') }}
                                    </h3>
                                    <p class="mb-0 mt-3 lg:mt-5 font-body text-gray-600">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($agenda->deskripsi ?? ''), 135, ' ...') }}
                                    </p>
                                </div>

                                <div class="mt-3 flex items-center gap-x-2 border-t pt-2 lg:mt-6">
                                    <i class="ri-calendar-line text-gray-600 text-sm"></i>
                                    <div>
                                        <h5 class="text-sm font-body text-gray-600">
                                            {{ \Carbon\Carbon::parse($agenda->created_at)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Card -->                    
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-600 border rounded p-3">Tidak ada agenda.</p>
            @endif
            <!-- End Grid -->

            <!-- Card -->
            <div class="mt-6 lg:mt-12 lg:text-center lg:hidden">
                <a class="flex justify-between w-full text-sm font-body font-semibold py-2 border-b border-navy-900 text-navy-900 disabled:opacity-50 disabled:pointer-events-none transition-all"
                    href="#">
                    <span class="uppercase">
                        Lihat Agenda Lainnya
                    </span>
                    <span class="flex-shrink-0">
                        <i class="ri-arrow-right-line ms-2"></i>
                    </span>
                </a>
            </div>
            <!-- End Card -->
        </div>
    </section>
    <!-- End Agenda -->

    <!-- Start FAQ -->
    <section id="faq" class="relative overflow-hidden">
        {{-- start pattern --}}
        {{-- <div class="absolute bottom-0 end-0 z-[0] rotate-180 opacity-50 lg:opacity-100">
            <img src="{{ asset('assets/frontpage/img/square-pattern.png') }}" alt="pattern-png" class="w-[60px]">
        </div> --}}
        {{-- end pattern --}}
        <!-- FAQ -->
        <div class="relative z-10 max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto bg-light-100">
            <!-- Grid -->
            <div class="grid md:grid-cols-5 gap-10">
                <div class="md:col-span-2">
                    <div class="max-w-sm">
                        <h2 class="text-2xl font-display font-bold md:text-4xl md:leading-tight text-navy-900">
                            Pertanyaan<br>yang sering diajukan
                        </h2>
                        <p class="mt-3 hidden md:block text-gray-600">
                            Jawaban atas pertanyaan yang paling sering diajukan.
                        </p>
                    </div>
                    <div>
                        <img src="{{ asset('assets/frontpage/img/faq-icon.png') }}" alt="faq-icon"
                            class="w-[400px] hidden md:block">
                    </div>
                </div>
                <!-- End Col -->

                <div class="md:col-span-3">
                    <!-- Accordion -->
                    <div class="hs-accordion-group divide-y divide-gray-200">
                        <div class="hs-accordion pb-3 active" id="hs-basic-with-title-and-arrow-stretched-heading-one">
                            <button
                                class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-body font-semibold text-start text-gray-800 rounded-lg transition hover:text-blue-800"
                                aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-one">
                                Apa yang membedakan Program Studi Pendidikan Teknologi Informasi di Universitas Negeri
                                Gorontalo?
                                <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>
                            </button>
                            <div id="hs-basic-with-title-and-arrow-stretched-collapse-one"
                                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300"
                                aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-one">
                                <p class="text-gray-600 font-body">
                                    Program Studi Pendidikan Teknologi Informasi di Universitas Negeri Gorontalo menawarkan
                                    pendekatan yang holistik dalam pendidikan teknologi informasi. Kami menggabungkan
                                    pengetahuan tentang teknologi informasi dengan pendekatan pedagogis yang inovatif untuk
                                    mempersiapkan para calon guru yang mampu mengajar materi teknologi informasi secara
                                    efektif
                                </p>
                            </div>
                        </div>

                        <div class="hs-accordion pt-6 pb-3" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <button
                                class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-body font-semibold text-start text-gray-800 rounded-lg transition hover:text-blue-800"
                                aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                                Apakah kurikulum Program Studi Pendidikan Teknologi Informasi menyesuaikan diri dengan
                                perkembangan teknologi terkini?
                                <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>
                            </button>
                            <div id="hs-basic-with-title-and-arrow-stretched-collapse-two"
                                class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                                <p class="text-gray-600 font-body">
                                    Ya, kami selalu memperbarui kurikulum kami untuk mencerminkan perkembangan terbaru dalam
                                    teknologi informasi. Kami memastikan mahasiswa mendapatkan pemahaman yang kuat tentang
                                    konsep dasar teknologi informasi serta keterampilan praktis dalam penggunaan perangkat
                                    lunak dan aplikasi terkini.
                                </p>
                            </div>
                        </div>

                        <div class="hs-accordion pt-6 pb-3" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <button
                                class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-body font-semibold text-start text-gray-800 rounded-lg transition hover:text-blue-800"
                                aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                                Bagaimana Program Studi Pendidikan Teknologi Informasi mempersiapkan mahasiswa untuk menjadi
                                guru yang berkualitas?
                                <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>
                            </button>
                            <div id="hs-basic-with-title-and-arrow-stretched-collapse-two"
                                class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                                <p class="text-gray-600 font-body">
                                    Kami menyediakan beragam mata kuliah dan pengalaman praktis yang dirancang khusus untuk
                                    membekali mahasiswa dengan keterampilan mengajar yang efektif, pemahaman tentang
                                    kebutuhan siswa dalam pembelajaran teknologi informasi, dan kemampuan untuk merancang
                                    dan mengevaluasi kurikulum yang relevan.
                                </p>
                            </div>
                        </div>

                        <div class="hs-accordion pt-6 pb-3" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <button
                                class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-body font-semibold text-start text-gray-800 rounded-lg transition hover:text-blue-800"
                                aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                                Apakah ada kesempatan untuk mahasiswa terlibat dalam pengalaman lapangan atau magang selama
                                studi?
                                <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>
                            </button>
                            <div id="hs-basic-with-title-and-arrow-stretched-collapse-two"
                                class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                                <p class="text-gray-600 font-body">
                                    Ya, kami mendorong mahasiswa untuk mengambil bagian dalam pengalaman lapangan di
                                    sekolah-sekolah mitra atau lembaga pendidikan lainnya sebagai bagian dari kurikulum
                                    kami. Ini memberikan kesempatan bagi mahasiswa untuk mengaplikasikan pengetahuan dan
                                    keterampilan yang mereka pelajari di lingkungan pendidikan yang nyata.
                                </p>
                            </div>
                        </div>

                        <div class="hs-accordion pt-6 pb-3" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <button
                                class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-body font-semibold text-start text-gray-800 rounded-lg transition hover:text-blue-800"
                                aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                                Bagaimana proses penerimaan mahasiswa baru di Program Studi Pendidikan Teknologi Informasi?
                                <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                                <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-gray-800 group-hover:text-blue-800"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 15-6-6-6 6" />
                                </svg>
                            </button>
                            <div id="hs-basic-with-title-and-arrow-stretched-collapse-two"
                                class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                                <p class="text-gray-600 font-body">
                                    Informasi tentang proses penerimaan mahasiswa baru dapat ditemukan di situs web resmi
                                    Universitas Negeri Gorontalo atau dengan menghubungi unit penerimaan mahasiswa.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- End Accordion -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </div>
        <!-- End FAQ -->
    </section>
    <!-- End FAQ -->
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: {
                delay: 8000,
            },
            effect: 'fade',
        });
    </script>
@endpush
