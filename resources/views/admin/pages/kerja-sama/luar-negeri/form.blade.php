@extends('admin.layouts.app')

@section('content')
    <!-- Konten Header -->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kerjasamaLuarNegeri.index') }}">
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
            action="@if (isset($kerjasamaLuarNegeri)) {{ route('kerjasamaLuarNegeri.update', $kerjasamaLuarNegeri->id) }} @else {{ route('kerjasamaLuarNegeri.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($kerjasamaLuarNegeri))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($kerjasamaLuarNegeri) && $kerjasamaLuarNegeri->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $kerjasamaLuarNegeri->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($kerjasamaLuarNegeri->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
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
                                value="{{ old('instansi', $kerjasamaLuarNegeri->instansi ?? '') }}" autocomplete="off" />
                            @error('instansi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @role('Superadmin|Admin|Kajur')
                            <div class="mb-3">
                                <label class="small mb-1" for="prodiField">
                                    Program Studi
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="program_studi" id="prodiField"
                                    class="form-select @error('program_studi') is-invalid @enderror">
                                    <option value="" selected hidden>-- Pilih Program Studi --</option>
                                    <option value="SISTEM INFORMASI" @if (isset($kerjasamaLuarNegeri) && $kerjasamaLuarNegeri->program_studi == 'SISTEM INFORMASI') selected @endif>
                                        SISTEM INFORMASI
                                    </option>
                                    <option value="PEND. TEKNOLOGI INFORMASI" @if (isset($kerjasamaLuarNegeri) && $kerjasamaLuarNegeri->program_studi == 'PEND. TEKNOLOGI INFORMASI') selected @endif>
                                        PEND. TEKNOLOGI INFORMASI
                                    </option>
                                </select>
                                @error('program_studi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endrole
                        <div class="mb-3">
                            <label class="small mb-1" for="jenisKegiatanField">
                                Jenis Kegiatan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('jenis_kegiatan') is-invalid @enderror" name="jenis_kegiatan"
                                id="jenisKegiatanField" type="text" placeholder="Masukkan jenis kegiatan"
                                value="{{ old('jenis_kegiatan', $kerjasamaLuarNegeri->jenis_kegiatan ?? '') }}"
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
                                value="{{ old('tanggal_mulai', $kerjasamaLuarNegeri->tgl_mulai ?? '') }}"
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
                                value="{{ old('tanggal_berakhir', $kerjasamaLuarNegeri->tgl_berakhir ?? '') }}"
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
                            @if (isset($kerjasamaLuarNegeri))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($kerjasamaLuarNegeri) && $kerjasamaLuarNegeri->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $kerjasamaLuarNegeri->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($kerjasamaLuarNegeri->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($kerjasamaLuarNegeri->updated_at)->isoFormat('H:mm') }}
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
