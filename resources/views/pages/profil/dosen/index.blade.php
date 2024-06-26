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
                    <span class="font-body font-normal uppercase">Program Studi Pendidikan Teknologi Informasi</span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <!-- Start Konten Halaman Utama -->
    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
            @if (isset($dosens) && count($dosens) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-8 xl:gap-12 py-8">
                    @foreach ($dosens as $dosen)
                        <a href="{{ route('profil.detailDosen', $dosen['slug'] ?? '') }}"
                            class="group text-center p-4 border rounded-xl hover:shadow transition-all duration-300">
                            @if (isset($dosen['foto']))
                                <img class="rounded-xl sm:size-48 lg:size-60 mx-auto"
                                    src="{{ asset('storage/usersProfile/' . $dosen['foto']) }}" alt="dosen-image">
                            @else
                                <img class="rounded-xl sm:size-48 lg:size-60 mx-auto"
                                    src="{{ asset('assets/admin/img/user-placeholder.svg') }}" alt="dosen-image">
                            @endif
                            <div class="mt-2 sm:mt-4">
                                <h3
                                    class="text-sm font-body font-semibold text-gray-800 sm:text-base lg:text-lg group-hover:text-blue-800">
                                    {{ $dosen['name'] ?? '' }}
                                </h3>
                                <h4
                                    class="text-sm font-body font-normal text-gray-800 sm:text-base lg:text-base group-hover:text-blue-800">
                                    {{ $dosen['nip'] ?? '' }}
                                </h4>
                                <p class="text-xs font-body text-gray-600 sm:text-sm lg:text-base">
                                    {{ $dosen['jafa'] ?? '' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mb-8">
                    {{ $pagination->links('vendor.pagination.simple-tailwind') }}
                </div>
            @else
                <div class="py-40">
                    <p class="text-center text-gray-600 font-body font-semibold border rounded py-4">Tidak ada data dosen yang tersedia.</p>
                </div>
            @endif
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
