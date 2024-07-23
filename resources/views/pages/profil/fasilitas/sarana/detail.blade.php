@extends('layouts.app')

@section('content')
    <!-- Start Konten Header-->
    <header class="relative">
        <img class="h-72 lg:h-96 w-full object-cover blur-[3px]" src="{{ asset('assets/frontpage/img/bg-banner2.jpg') }}" alt="bg-header-image">
        <div class="absolute inset-0 bg-dark-800 opacity-60"></div>
        <div class="relative max-w-[85rem] w-full mx-auto">
            <div class="absolute bottom-0 start-0 px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
                <h2 class="text-light-100 text-2xl lg:text-3xl font-display font-bold">
                    <a href="{{ route('fasilitas.sarana.index') }}" class="text-xl lg:text-2xl uppercase">Sarana</a>
                    <br>
                    <span class="font-body font-normal uppercase">{{ $subtitle }}</span>
                </h2>
            </div>
        </div>
    </header>
    <!-- End Konten Header-->

    <section id="kontenUtama" x-data="saranaImages()" x-init="fetchResults()">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <template x-if="isLoading">
                <div class="w-full text-center p-3">
                    <div class="animate-spin inline-block size-8 border-[3px] border-blue-600 border-t-blue-600/0 text-blue-600 rounded-full"
                        role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </template>

            <template x-if="!isLoading && results.length > 0">
                <!-- Image Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <template x-for="(result, index) in results" :key="result.id">
                        <div>
                            <a class="group block relative overflow-hidden rounded-lg" href="javascript:void(0)" role="button"
                                aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-vertically-centered-modal"
                                :data-hs-overlay="'#hs-vertically-centered-modal' + (index)">
                                <img class="w-full size-60 object-cover bg-gray-100 rounded-lg dark:bg-neutral-800"
                                    :src="'{{ asset('storage/fasilitas/sarana/') }}/' + result.gambar"
                                    alt="fasilitas-sarana-image" />
                                <div class="absolute bottom-1 end-1 opacity-0 group-hover:opacity-100 transition">
                                    <div
                                        class="flex items-center gap-x-1 py-1 px-2 bg-light-100 border border-gray-200 text-gray-800 rounded-lg">
                                        <i class="ri-zoom-in-line ri-sm"></i>
                                        <span class="text-xs">Zoom</span>
                                    </div>
                                </div>
                            </a>
                            <div :id="'hs-vertically-centered-modal' + (index)"
                                class="hs-overlay hs-overlay-backdrop-open:bg-dark-900/50 hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
                                role="dialog" tabindex="-1" aria-labelledby="hs-vertically-centered-modal-label">
                                <div
                                    class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                                    <div
                                        class="w-full bg-light-100/0 overflow-hidden rounded-lg pointer-events-auto">
                                        <img class="w-full object-cover bg-gray-100 rounded-lg dark:bg-neutral-800"
                                            :src="'{{ asset('storage/fasilitas/sarana/') }}/' + result.gambar"
                                            alt="fasilitas-sarana-image" />
                                        <div class="flex flex-row justify-between my-1">
                                            <h1 class="text-xs text-light-100 font-bold">{{ $sarana['keterangan'] }}</h1>
                                            <h2 class="text-xs text-gray-400" x-text="result.gambar"></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- End Image Grid -->
            </template>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function saranaImages() {
            return {
                isLoading: false,
                results: [],
                fetchResults() {
                    this.isLoading = true;
                    fetch(`/api/v1/sarana/{{ $sarana['slug'] }}/images`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-API-TOKEN': 'fc8f099328037b48e30e1e53071feb53770d11f882f01dc9ad44b8d343cbfd11'
                        },
                    })
                    .then(response => response.json())
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
