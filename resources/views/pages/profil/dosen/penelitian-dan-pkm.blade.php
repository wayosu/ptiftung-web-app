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
                    <span class="text-xl lg:text-2xl uppercase">{{ $namaKategori ?? null }}</span>
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
            <div class="flex flex-col py-8">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg divide-y divide-gray-200">
                            <div class="py-3 px-4">
                                <div class="relative max-w-xs">
                                    <label class="sr-only">Cari</label>
                                    <input type="text" name="hs-table-with-pagination-search"
                                        id="hs-table-with-pagination-search"
                                        class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm font-body focus:z-10 focus:border-blue-600 focus:ring-blue-600 disabled:opacity-50 disabled:pointer-events-none"
                                        placeholder="Cari ...">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                        <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-start text-xs font-body font-semibold text-gray-500 uppercase">
                                                Nomor
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-start text-xs font-body font-semibold text-gray-500 uppercase">
                                                {{ $namaKategori ?? null }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-body font-semibold text-gray-800">
                                                1
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-body font-semibold text-gray-800">
                                                The Application Of Cooperative Learning Methods In The Developing And
                                                Analyzing
                                                The Quality Of An Educational Game
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-body font-semibold text-gray-800">
                                                2
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-body font-semibold text-gray-800">
                                                Pendampingan masyarakat Desa Lakeya dalam Pencapaian SDGs Desa
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-body font-semibold text-gray-800">
                                                3
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-body font-semibold text-gray-800">
                                                The Application Of Cooperative Learning Methods In The Developing And
                                                Analyzing
                                                The Quality Of An Educational Game
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="py-1 px-4">
                                <nav class="flex items-center space-x-1">
                                    <button type="button"
                                        class="p-2.5 inline-flex items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
                                        <span aria-hidden="true">«</span>
                                        <span class="sr-only">Previous</span>
                                    </button>
                                    <button type="button"
                                        class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none"
                                        aria-current="page">1</button>
                                    <button type="button"
                                        class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none">2</button>
                                    <button type="button"
                                        class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none">3</button>
                                    <button type="button"
                                        class="p-2.5 inline-flex items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
                                        <span class="sr-only">Next</span>
                                        <span aria-hidden="true">»</span>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
