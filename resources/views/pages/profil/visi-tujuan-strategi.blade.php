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
                    <span class="text-xl lg:text-2xl uppercase">Visi, Tujuan dan Strategi</span>
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
                        <div class="flex flex-col gap-y-10">
                            <div>
                                <h2 class="text-3xl font-display font-bold text-navy-900 mb-4">Visi Keilmuan</h2>
                                <div class="space-y-5 lg:space-y-8 konten-deskripsi">
                                    {!! $visiTujuanStrategi->visi_keilmuan ?? '' !!}
                                </div>
                            </div>
                            <div>
                                <h2 class="text-3xl font-display font-bold text-navy-900 mb-4">Tujuan</h2>
                                <div class="space-y-5 lg:space-y-8 konten-deskripsi">
                                    {!! $visiTujuanStrategi->tujuan ?? '' !!}
                                </div>
                            </div>
                            <div>
                                <h2 class="text-3xl font-display font-bold text-navy-900 mb-4">Strategi</h2>
                                <div class="space-y-5 lg:space-y-8 konten-deskripsi">
                                    {!! $visiTujuanStrategi->strategi ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Konten -->

                <!-- Sidebar -->
                @include('partials.sidebar-in-content')

                <!-- End Sidebar -->
            </div>
        </div>
    </section>
    <!-- End Konten Halaman Utama -->
@endsection
