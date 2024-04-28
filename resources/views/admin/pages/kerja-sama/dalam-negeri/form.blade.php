@extends('admin.layouts.app')

@section('content')
    <!-- Konten Header -->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kerjasamaDalamNegeri.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-xl px-4 mt-4">
        <form
            action="@if (isset($kerjasamaDalamNegeri)) {{ route('kerjasamaDalamNegeri.update', $kerjasamaDalamNegeri->id) }} @else {{ route('kerjasamaDalamNegeri.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($kerjasamaDalamNegeri))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($kerjasamaDalamNegeri) && $kerjasamaDalamNegeri->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $kerjasamaDalamNegeri->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($kerjasamaDalamNegeri->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="instansiField">
                                Instansi
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('instansi') is-invalid @enderror" name="instansi"
                                id="instansiField" type="text" placeholder="Masukkan instansi"
                                value="{{ old('instansi', $kerjasamaDalamNegeri->instansi ?? '') }}" autocomplete="off" />
                            @error('instansi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="jenisKegiatanField">
                                Jenis Kegiatan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('jenis_kegiatan') is-invalid @enderror" name="jenis_kegiatan"
                                id="jenisKegiatanField" type="text" placeholder="Masukkan jenis kegiatan"
                                value="{{ old('jenis_kegiatan', $kerjasamaDalamNegeri->jenis_kegiatan ?? '') }}"
                                autocomplete="off" />
                            @error('jenis_kegiatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tanggalMulaiField">
                                Tanggal Mulai
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai"
                                id="tanggalMulaiField" type="date"
                                value="{{ old('tanggal_mulai', $kerjasamaDalamNegeri->tgl_mulai ?? '') }}"
                                autocomplete="off" />
                            @error('tanggal_mulai')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tanggalBerakhirField">
                                Tanggal Berakhir
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                                name="tanggal_berakhir" id="tanggalBerakhirField" type="date"
                                value="{{ old('tanggal_berakhir', $kerjasamaDalamNegeri->tgl_berakhir ?? '') }}"
                                autocomplete="off" />
                            @error('tanggal_berakhir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($kerjasamaDalamNegeri))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($kerjasamaDalamNegeri) && $kerjasamaDalamNegeri->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $kerjasamaDalamNegeri->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($kerjasamaDalamNegeri->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($kerjasamaDalamNegeri->updated_at)->isoFormat('H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
