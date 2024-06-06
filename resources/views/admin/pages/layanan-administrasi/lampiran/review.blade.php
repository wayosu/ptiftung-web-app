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
                            <div class="page-header-icon"><i data-feather="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <p class="mb-0 small mt-1">
                            {{ $subtitle ?? '' }}
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('lampiranKegiatan.index', $active) }}" role="button">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4">
        @if ($lampiranKegiatans->count() > 0)
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
                @foreach ($lampiranKegiatans->sortByDesc('created_at') as $item)
                    <div class="col">
                        <a href="{{ route('lampiranKegiatan.detail', $item->id) }}" class="card shadow-sm text-decoration-none my-card">
                            <div class="card-body overflow-hidden">
                                <div class="d-flex aling-items-center justify-content-between my-2">
                                    <div class="text-dark">
                                        <h1 class="fs-4 fw-bolder">{{ $item->keterangan_lampiran }}</h1>
                                        <div class="text-muted d-flex gap-3 flex-row">
                                            <span class="text-xs d-flex gap-1 align-items-center">
                                                <i class="fa-solid fa-calendar fa-sm"></i>
                                                {{ $item->created_at->isoFormat('dddd, D MMMM Y') }}
                                            </span>
                                            <span class="text-xs d-flex gap-1 align-items-center">
                                                <i class="fa-solid fa-clock fa-sm"></i>
                                                Pukul {{ $item->created_at->isoFormat('HH:mm') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <table class="small text-dark">
                                    <tr>
                                        <td>Dosen Pembimbing Lapangan</td>
                                        <td class="pe-1">:</td>
                                        <td>{{ $item->kegiatan->dosen->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mahasiswa</td>
                                        <td class="pe-1">:</td>
                                        <td>
                                            {{ $item->kegiatan->mahasiswa->nim }} - 
                                            {{ $item->kegiatan->mahasiswa->user->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Status Lampiran</td>
                                        <td class="pe-1">:</td>
                                        <td>
                                            @if ($item->status === 'belum direview')
                                                <span class="px-2 py-0 rounded-1 small bg-warning text-light">
                                                    <i class="fa-solid fa-hourglass-half fa-sm me-1"></i>
                                                    Belum Direview
                                                </span>
                                            @elseif ($item->status === 'disetujui')
                                                <span class="px-2 py-0 rounded-1 small bg-success text-light">
                                                    <i class="fa-solid fa-check fa-sm me-1"></i>
                                                    Disetujui
                                                </span>
                                            @elseif ($item->status === 'ditolak')
                                                <span class="px-2 py-0 rounded-1 small bg-danger text-light">
                                                    <i class="fa-solid fa-xmark fa-sm me-1"></i>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card shadow-none">
                <div class="card-body text-center">
                    <div class="d-flex flex-column gap-3 align-items-center">
                        <i class="fa-solid fa-triangle-exclamation fa-3x text-warning"></i>
                        <div>
                            <h5 class="mb-1 fw-bolder text-capitalize">Tidak ada data</h5>
                            <p class="mb-0 small">Mahasiswa belum melampirkan dokumen kegiatan.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
