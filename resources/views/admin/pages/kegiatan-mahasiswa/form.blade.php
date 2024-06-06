@extends('admin.layouts.app')

@section('content')
    <!-- Header content-->
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kegiatanMahasiswa.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <form
            action="@if (isset($kegiatanMahasiswa)) {{ route('kegiatanMahasiswa.update', $kegiatanMahasiswa->id) }} @else {{ route('kegiatanMahasiswa.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($kegiatanMahasiswa))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($kegiatanMahasiswa) && $kegiatanMahasiswa->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $kegiatanMahasiswa->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($kegiatanMahasiswa->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="namaKegiatanField">
                                Nama Kegiatan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('nama_kegiatan') is-invalid @enderror" name="nama_kegiatan"
                                id="namaKegiatanField" type="text" placeholder="Masukkan nama kegiatan"
                                value="{{ old('nama_kegiatan', $kegiatanMahasiswa->nama_kegiatan ?? '') }}" />
                            @error('nama_kegiatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsiField">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" id="deskripsiField" class="form-control @error('deskripsi') is-invalid @enderror" rows="5" placeholder="Masukkan deskripsi kegiatan">{{ old('deskripsi', $kegiatanMahasiswa->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @role('Superadmin|Admin|Kajur')
                            <div class="mb-3">
                                <label class="small mb-1" for="prodiField">
                                    Program Studi
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('program_studi') is-invalid @enderror" name="program_studi"
                                    id="prodiField">
                                    <option value="" selected hidden>-- Pilih Program Studi --</option>
                                    <option value="SISTEM INFORMASI" @if (isset($kegiatanMahasiswa) && $kegiatanMahasiswa->program_studi == 'SISTEM INFORMASI') selected @endif>
                                        SISTEM INFORMASI
                                    </option>
                                    <option value="PEND. TEKNOLOGI INFORMASI" @if (isset($kegiatanMahasiswa) && $kegiatanMahasiswa->program_studi == 'PEND. TEKNOLOGI INFORMASI') selected @endif>
                                        PEND. TEKNOLOGI INFORMASI
                                    </option>
                                </select>
                                @error('program_studi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="program_studi" value="{{ Auth::user()->dosen->program_studi ?? '' }}">
                        @endrole
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($kegiatanMahasiswa))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($kegiatanMahasiswa) && $kegiatanMahasiswa->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $kegiatanMahasiswa->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($kegiatanMahasiswa->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($kegiatanMahasiswa->updated_at)->isoFormat('H:mm') }}
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
