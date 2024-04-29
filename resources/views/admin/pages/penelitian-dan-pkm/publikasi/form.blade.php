@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2-bootstrap-5-theme.min.css') }}" />

    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: calc(1.5em + 0.75rem + 15px) !important;
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            padding: 0.775rem 1.1rem !important;
        }
    </style>
@endpush

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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('publikasi.index') }}">
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
            action="@if (isset($publikasi)) {{ route('publikasi.update', $publikasi->id) }} @else {{ route('publikasi.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($publikasi))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($publikasi) && $publikasi->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $publikasi->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($publikasi->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="dosenField">
                                Dosen
                                <span class="text-danger">*</span>
                            </label>
                            <select name="dosen_id" id="dosenField"
                                class="form-select select2 @error('dosen_id') is-invalid @enderror">
                                <option></option>
                                @foreach ($namaDosen as $item)
                                    <option value="{{ $item['id'] }}"
                                        {{ old('dosen_id', $publikasi->dosen_id ?? '') == $item['id'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="judulField">
                                Judul
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('judul') is-invalid @enderror" name="judul" id="judulField"
                                type="text" placeholder="Masukkan judul"
                                value="{{ old('judul', $publikasi->judul ?? '') }}" autocomplete="off" />
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tautanPublikasiField">
                                Tautan Publikasi
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-joined">
                                <span class="input-group-text">
                                    <i data-feather="link"></i>
                                </span>
                                <input class="form-control ps-0  @error('link_publikasi') is-invalid @enderror"
                                    name="link_publikasi" id="tautanPublikasiField" type="text" placeholder="..."
                                    value="{{ old('link_publikasi', $publikasi->link_publikasi ?? '') }}">
                            </div>
                            @error('link_publikasi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($publikasi))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($publikasi) && $publikasi->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $publikasi->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($publikasi->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($publikasi->updated_at)->isoFormat('H:mm') }}
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

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // inisialisasi select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                dropdownCssClass: "select2--small",
                width: '100%',
                placeholder: "-- Pilih Dosen --",
                allowClear: true
            });
        });
    </script>
@endpush
