@extends('admin.layouts.app')

@push('css')
    <style>
        .my-card {
            transition: all 0.3s ease-in-out;
        }

        .my-card:hover {
            box-shadow: none !important;
        }
    </style>
@endpush

@section('content')
    <!-- Header content-->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <p class="mb-0 small mt-1">
                            {{ $subtitle ?? '' }}
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ request()->fullUrl() }}" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        @role('Superadmin|Admin|Kajur')
            @if ((isset($kmsi) && $kmsi->count() > 0) || (isset($kmpti) && $kmpti->count() > 0))
                <div class="row g-4">
                    @foreach ($kmsi as $item)
                        <div class="col-12">
                            <a href="{{ route('kegiatan.show', $item->id) }}" class="card shadow-sm text-decoration-none my-card">
                                <div class="card-body overflow-hidden">
                                    <span class="badge bg-primary">{{ $item->program_studi }}</span>
                                    <div class="text-dark my-2">
                                        <h1 class="fs-4 fw-bolder">{{ $item->nama_kegiatan }}</h1>
                                        <p class="mb-0 small">{{ $item->deskripsi }}</p>
                                    </div>
                                    <hr class="mt-3 mb-2 text-muted" />
                                    <div class="text-dark badge bg-light text-sm">
                                        <span>Total Peserta</span>
                                        <span class="pe-2">:</span>
                                        <span>{{ $kmsiTotals[$item->id] ?? 0 }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    @foreach ($kmpti as $item)
                        <div class="col-12">
                            <a href="{{ route('kegiatan.show', $item->id) }}" class="card shadow-sm text-decoration-none my-card">
                                <div class="card-body overflow-hidden">
                                    <span class="badge bg-primary">{{ $item->program_studi }}</span>
                                    <div class="text-dark my-2">
                                        <h1 class="fs-4 fw-bolder">{{ $item->nama_kegiatan }}</h1>
                                        <p class="mb-0 small">{{ $item->deskripsi }}</p>
                                    </div>
                                    <hr class="mt-3 mb-2 text-muted" />
                                    <div class="text-dark badge bg-light text-sm">
                                        <span>Total Peserta</span>
                                        <span class="pe-2">:</span>
                                        <span>{{ $kmptiTotals[$item->id] ?? 0 }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card shadow-none">
                            <div class="card-body text-center">
                                <div class="d-flex flex-column gap-3 align-items-center">
                                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning"></i>
                                    <div>
                                        <h5 class="mb-1 fw-bolder text-capitalize">Tidak ada data</h5>
                                        <p class="mb-0 small">Belum ada data Kegiatan Mahasiswa yang diinput.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            @if (isset($km) && $km->count() > 0)
                <div class="row g-4">
                    @foreach ($km as $item)
                        <div class="col-12">
                            <a href="{{ route('kegiatan.show', $item->id) }}" class="card shadow-sm text-decoration-none my-card">
                                <div class="card-body overflow-hidden">
                                    <span class="badge bg-primary">{{ $item->program_studi }}</span>
                                    <div class="text-dark my-2">
                                        <h1 class="fs-4 fw-bolder">{{ $item->nama_kegiatan }}</h1>
                                        <p class="mb-0 small">{{ $item->deskripsi }}</p>
                                    </div>
                                    <hr class="mt-3 mb-2 text-muted" />
                                    <div class="text-dark badge bg-light text-sm">
                                        <span>Total Peserta</span>
                                        <span class="pe-2">:</span>
                                        <span>{{ $kmTotals[$item->id] ?? 0 }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div> 
            @else
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card shadow-none">
                            <div class="card-body text-center">
                                <div class="d-flex flex-column gap-3 align-items-center">
                                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning"></i>
                                    <div>
                                        <h5 class="mb-1 fw-bolder text-capitalize">Tidak ada data</h5>
                                        <p class="mb-0 small">Belum ada data Kegiatan Mahasiswa yang diinput.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            @endif
        @endrole
    </div>
@endsection
