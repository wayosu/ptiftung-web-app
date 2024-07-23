@extends('layouts.app')

@section('content')
    <header class="relative">
        <img class="h-72 lg:h-96 w-full object-cover blur-[3px]"
            src="{{ asset('assets/frontpage/img/bg-banner-akademik.jpg') }}" alt="bg-header-image">
        <div class="absolute inset-0 bg-dark-800 opacity-60"></div>
        <div class="relative max-w-[85rem] w-full mx-auto">
            <div class="absolute bottom-0 start-0 px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
                <h2 class="text-light-100 text-2xl lg:text-3xl font-display font-bold">
                    <span class="text-xl lg:text-2xl uppercase">Profil Lulusan</span>
                    <br>
                    <span class="font-body font-normal uppercase">Program Studi Pendidikan Teknologi Informasi</span>
                </h2>
            </div>
        </div>
    </header>

    <section id="kontenUtama">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto" x-data="profilLulusanData()" x-init="fetchResults()">
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 xl:gap-12">    
                    <template x-for="result in results" :key="result.id">
                        <div class="flex flex-col group bg-white border shadow-sm rounded-xl overflow-hidden hover:shadow-lg transition"
                            href="#">
                            <div class="relative pt-80 rounded-t-xl overflow-hidden">
                                <img class="w-full absolute top-0 start-0 object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out rounded-t-xl"
                                    :src="result.gambar" :alt="result.judul + ' image'">
                            </div>
                            <div class="h-auto max-h-60 overflow-y-auto p-4 md:p-5 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                                <h3 class="text-lg font-bold text-dark-900 dark:text-white" x-text="result.judul"></h3>
                                <h4 class="text-sm font-semibold text-dark-600 dark:text-white" x-text="result.subjudul"></h4>
                                <p class="mt-2 text-gray-600 dark:text-neutral-400 text-justify" x-text="result.deskripsi"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function profilLulusanData() {
            return {
                results: [],
                isLoading: false,
                fetchResults() {
                    this.isLoading = true;
                    fetch('/api/v1/profil-lulusan', {
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
                        this.results = data || [];
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
