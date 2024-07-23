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
                    <a href="{{ route('fasilitas.index') }}" class="text-xl lg:text-2xl uppercase">Fasilitas</a>
                    <br>
                    <span class="font-body font-normal uppercase">Sistem Informasi</span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <!-- Start Konten Halaman Utama -->
    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <!-- List Group -->
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                Daftar Tautan Sistem Informasi
            </h3>
            <ul class="flex flex-col justify-end text-start -space-y-px">
                @if (count($datas) == 0)
                    <li
                        class="flex items-center gap-x-2 p-3 text-sm bg-white border text-gray-800 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">
                        <div class="w-full text-center">
                            Data tidak ditemukan.
                        </div>
                    </li>
                @else
                    @foreach ($datas as $data)
                        <li
                            class="flex items-center gap-x-2 p-3 text-sm bg-white border text-gray-800 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">
                            <div class="w-full flex justify-between truncate">
                                <span class="me-3 flex-1 w-0 truncate">
                                    {{ $data['sistem_informasi'] }}
                                </span>
                                <a href="{{ $data['link'] }}" target="_blank" role="button"
                                    class="flex items-center gap-x-2 text-gray-500 hover:text-blue-600 whitespace-nowrap dark:text-neutral-500 dark:hover:text-blue-500">
                                    <i class="ri-share-forward-line"></i>
                                    Kunjungi
                                </a>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
            <!-- End List Group -->
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
