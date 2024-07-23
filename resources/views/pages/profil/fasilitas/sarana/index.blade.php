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
                    <span class="font-body font-normal uppercase">Sarana</span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <!-- Start Konten Halaman Utama -->
    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto" x-data="liveSearch()"
            x-init="fetchResults()">
            <!-- List Group -->
            <div class="flex flex-col md:flex-row gap-3 md:items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">
                    Daftar Sarana
                </h3>
                {{-- search --}}
                <div class="relative">
                    <div class="relative flex rounded-lg group">
                        <input type="text" x-model="query" @input.debounce.500ms="fetchResults"
                            id="hs-trailing-button-add-on-with-icon-and-button"
                            name="hs-trailing-button-add-on-with-icon-and-button"
                            class="group py-3 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:z-10 focus:border-navy-500 focus:ring-navy-500 disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="Cari Sarana">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <svg class="flex-shrink-0 size-4 text-gray-400 group-focus-within:text-navy-500"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <template x-if="isLoading">
                <div class="w-full text-center p-3">
                    <div class="animate-spin inline-block size-8 border-[3px] border-blue-600 border-t-blue-600/0 text-blue-600 rounded-full" role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                      </div>
                </div>
            </template>

            <template x-if="!isLoading && results.length === 0">
                <div class="w-full text-center p-3 text-sm bg-light-100 border text-gray-800 rounded-lg">
                    Data tidak ditemukan.
                </div>
            </template>

            <template x-if="!isLoading && results.length > 0">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="result in results" :key="result.id">
                            <a :href="'{{ route('fasilitas.sarana.detail', '') }}' + '/' + result.slug"
                                class="group w-full flex flex-col border shadow-sm rounded-xl hover:shadow-md transition-all">
                                <div class="p-4 md:p-5">
                                    <span class="text-sm text-gray-600">KATEGORI</span>
                                    <h3 class="text-lg font-bold text-gray-800" x-text="result.keterangan"></h3>
                                </div>
                            </a>
                        </template>
                    </div>
    
                    <div class="flex justify-between items-center mt-4">
                        <!-- Pagination -->
                        <nav class="flex items-center gap-x-1" aria-label="Pagination">
                            <button type="button" @click="fetchResults(current_page - 1)" :disabled="current_page === 1" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-1.5 text-sm rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" aria-label="Previous">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                                <span class="hidden sm:block">Sebelumnya</span>
                            </button>
                            <button type="button" @click="fetchResults(current_page + 1)" :disabled="current_page === last_page" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-1.5 text-sm rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" aria-label="Next">
                                <span class="hidden sm:block">Selanjutnya</span>
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </button>
                        </nav>

                        <div class="text-sm text-gray-500">
                            <span>Menampilkan <span x-text="current_page"></span> Dari <span x-text="last_page"></span></span>
                        </div>
                    </div>
                </div>
            </template>
            <!-- End List Group -->
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection

@push('js')
    <script>
        function liveSearch() {
            return {
                query: '',
                results: [],
                current_page: 1,
                last_page: 1,
                isLoading: false,
                fetchResults(page = 1) {
                    if (typeof page === 'object' && page.target) {
                        page = 1;
                    }
                    this.current_page = page;
                    this.isLoading = true;
                    fetch(`/api/v1/sarana/search?query=${this.query}&page=${page}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-API-TOKEN': 'fc8f099328037b48e30e1e53071feb53770d11f882f01dc9ad44b8d343cbfd11'
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.results = data.data;
                        this.current_page = data.current_page;
                        this.last_page = data.last_page;
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                }
            }
        }
    </script>
@endpush
