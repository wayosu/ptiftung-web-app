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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('sistemInformasi.index') }}">
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
            action="@if (isset($sistemInformasi)) {{ route('sistemInformasi.update', $sistemInformasi->id) }} @else {{ route('sistemInformasi.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($sistemInformasi))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form Sistem Informasi</div>
                    @if (isset($sistemInformasi) && $sistemInformasi->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $sistemInformasi->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($sistemInformasi->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="sistemInformasiField">
                                Nama Sistem Informasi
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('sistem_informasi') is-invalid @enderror"
                                name="sistem_informasi" id="sistemInformasiField" type="text"
                                placeholder="Masukkan nama sistem informasi"
                                value="{{ old('sistem_informasi', $sistemInformasi->sistem_informasi ?? '') }}" />
                            @error('sistem_informasi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tautanField">
                                Tautan Sistem Informasi
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('link') is-invalid @enderror" name="link" id="tautanField"
                                type="text" placeholder="Masukkan tautan sistem informasi"
                                value="{{ old('link', $sistemInformasi->link ?? '') }}" />
                            @error('link')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($sistemInformasi))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($sistemInformasi) && $sistemInformasi->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $sistemInformasi->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($sistemInformasi->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($sistemInformasi->updated_at)->isoFormat('H:mm') }}
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
