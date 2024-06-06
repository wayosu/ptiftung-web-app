@extends('admin.layouts.app')

@section('content')
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $subtitle ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            @role('Superadmin|Admin|Kajur|Kaprodi')
            <div class="col-xxl-5 col-xl-12 mb-4">
            @endrole
            @role('Dosen')
            <div class="col-xxl-4 col-xl-12 mb-4">
            @endrole
            @role('Mahasiswa')
            <div class="col-xxl-6 col-xl-12 mb-4">
            @endrole
                <div class="card h-100">
                    <div class="card-body h-100 p-5">
                        <div class="row align-items-center">
                            <div class="col-xl-8 col-xxl-12">
                                <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                    <h1 class="text-primary">Selamat Datang di Dasbor Web</h1>
                                    <h4 class="text-primary text-uppercase">{{ $program_studi ?? 'Pend. Teknologi Informasi - Sistem Informasi' }}</h4>
                                    <hr>
                                    <p class="text-gray-700 mb-0">
                                        Dasbor ini dibuat untuk memudahkan pengelolaan layanan informasi dan layanan administrasi pendidikan. Sehingga pengelolaan layanan informasi dan layanan administrasi pendidikan dapat dilakukan dengan mudah dan cepat.
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xxl-12 text-center">
                                <img class="img-fluid" src="{{ asset('assets/admin/img/at-work.svg') }}"
                                    style="max-width: 26rem" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @role('Superadmin|Admin|Kajur|Kaprodi')
            <div class="col-xxl-7 col-xl-12 mb-4">
            @endrole
            @role('Dosen')
            <div class="col-xxl-8 col-xl-12 mb-4">
            @endrole
            @role('Mahasiswa')
            <div class="col-xxl-6 col-xl-12 mb-4">
            @endrole
                @role('Superadmin|Admin|Kajur|Kaprodi')
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Dosen</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalDosen ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-user fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Mahasiswa</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalMahasiswa ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-user fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Kegiatan Mahasiswa</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalKegiatanMahasiswa ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-people-line fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Penelitian</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalPenelitian ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-search fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Pengabdian Masyarakat</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalPengabdianMasyarakat ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-search fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Publikasi</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalPublikasi ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-search fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Kerja Sama Dalam Negeri</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalKerjasamaDalamNegeri ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-handshake fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Kerja Sama Luar Negeri</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalKerjasamaLuarNegeri ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-handshake fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Berita</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalBerita ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-newspaper fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-primary small">Agenda</div>
                                                    <div class="text-primary text-lg fw-bold">{{ $totalBerita ?? 0 }}</div>
                                                </div>
                                                <i class="fa-solid fa-calendar-days fa-xl text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole
                @role('Dosen')
                    <div class="row">
                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Penelitian</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalPenelitian ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-search fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Pengabdian Masyarakat</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalPengabdianMasyarakat ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-search fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Publikasi</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalPublikasi ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-search fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Total Berita</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalBerita ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-newspaper fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Kegiatan Diikuti</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalKegiatan ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-people-line fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Mahasiswa Yang Didamping</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalMahasiswa ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-user-group fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Lampiran Disetujui</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalLampiranDisetujui ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-check fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Lampiran Ditolak</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalLampiranDitolak ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-xmark fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole
                @role('Mahasiswa')
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Kegiatan Diikuti</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalKegiatan ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-people-line fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Total Lampiran</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalLampiran ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-file-text fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Lampiran Disetujui</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalLampiranDisetujui ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-check fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary small">Lampiran Ditolak</div>
                                            <div class="text-primary text-lg fw-bold">{{ $totalLampiranDitolak ?? 0 }}</div>
                                        </div>
                                        <i class="fa-solid fa-xmark fa-xl text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole
            </div>
        </div>
    </div>
@endsection
