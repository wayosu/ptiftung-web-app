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
                    <span class="text-xl lg:text-2xl uppercase">Sejarah</span>
                    <br>
                    <span class="font-body font-normal uppercase">Program Studi Pendidikan Teknologi Informasi</span>
                </h2>
                {{-- <p class="mt-2 text-light-100 text-base lg:text-lg font-body">
                    Program Studi Pendidikan Teknologi Informasi
                </p> --}}
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
                        <div class="space-y-5 lg:space-y-8">
                            <p class="text-lg text-gray-800 font-body text-justify">
                                Program Studi S1 Pendidikan Teknologi Informasi Universitas Negeri Gorontalo berdiri pada
                                tanggal 9 Mei 2012 berdasarkan Surat Mandat Direktur Jenderal Pendidikan Tinggi Nomor
                                743/E/T/2012. Surat Mandat tersebut ditindaklanjuti oleh Universitas Negeri Gorontalo dengan
                                menjawab kesediaan menerima mandat tersebut melalui Surat Kesediaan Rektor Universitas
                                Negeri Gorontalo Nomor 3156/UN47/AK/2012 tanggal 2 Juli 2012. Selanjutnya PS PTI mendapatkan
                                izin Penyelenggaran Program Studi pada tanggal 22 Januari 2013 berdasarkan Keputusan Menteri
                                Pendidikan dan Kebudayaan Republik Indonesia Nomor 24/E/O/2013.
                            </p>
                            <p class="text-lg text-gray-800 font-body text-justify">
                                Program Studi S1 Pendidikan Teknologi Informasi berada di bawah naungan Jurusan Teknik
                                Informatika Fakultas Teknik Universitas Negeri Gorontalo, sehingga pengelolaan SDM dan
                                fasilitas pendukung Tridharma Perguruan Tinggi dikoordinir oleh pihak Jurusan yang juga
                                membawahi Program Studi S1 Sistem Informasi. Sampai saat ini, Program Studi S1 Pendidikan
                                Teknologi Informasi merupakan satu-satunya Program Studi kependidikan bidang teknologi
                                informasi di Provinsi Gorontalo. Program Studi S1 Pendidikan Teknologi Informasi terus
                                berkembang dan sudah mulai dapat pengakuan dari berbagai pihak. Salah satunya adalah
                                pengakuan dari masyarakat yang ditunjukkan dengan meningkatnya jumlah mahasiswa. Jumlah
                                mahasiswa pada semester ganjil 2022/2023 sejumlah 372 orang. Program Studi S1 Pendidikan
                                Teknologi Informasi telah mendapatkan pengakuan dari BAN-PT sebgai Program Studi
                                Terakreditasi B, berdasarkan SK BAN PT Nomor 1119/SK/BAN-PT/Akred/S/IV/2018. Pengakuan
                                lainnya adalah Program Studi S1 Pendidikan Teknologi Informasi mendapatkan Hibah Kerja Sama
                                MBKM tahun 2021 dari Kemristekdikti. Selain itu, Program Studi S1 Pendidikan Teknologi
                                Informasi mendapat kepercayaan dalam penyelenggaraan Fresh Graduate Academy Digital Talent
                                Scholarship (FGA DTS) Kementerian Komunikasi dan Informatika.
                            </p>
                            <p class="text-lg text-gray-800 font-body text-justify">
                                Sejak berdirinya pada tahun 2012, Program Studi S1 Pendidikan Teknologi Informasi dipimpin
                                oleh 3 orang Ketua yaitu:
                            </p>
                            <ul class="list-decimal ps-4">
                                <li>Lillyan Hadjaratie, S.Kom., M.Si. (2012-2014)</li>
                                <li>Dian Novian, S.Kom., M.T. (2014-2019)</li>
                                <li>Sitti Suhada, S.Kom., M.T. (2019-2023)</li>
                            </ul>
                        </div>
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
