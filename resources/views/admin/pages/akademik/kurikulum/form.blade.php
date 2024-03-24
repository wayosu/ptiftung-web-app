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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kurikulum.index') }}">
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
            action="@if (isset($kurikulum)) {{ route('kurikulum.update', $kurikulum->id) }} @else {{ route('kurikulum.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($kurikulum))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="kodeMataKuliahField">
                                Kode Mata Kuliah
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('kode_mk') is-invalid @enderror" name="kode_mk"
                                id="kodeMataKuliahField" type="text" placeholder="Masukkan kode mata kuliah"
                                value="{{ old('kode_mk', $kurikulum->kode_mk ?? '') }}" />
                            @error('kode_mk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="mataKuliahField">
                                Mata Kuliah
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('nama_mk') is-invalid @enderror" name="nama_mk"
                                id="mataKuliahField" type="text" placeholder="Masukkan nama mata kuliah"
                                value="{{ old('nama_mk', $kurikulum->nama_mk ?? '') }}" />
                            @error('nama_mk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="sksField">
                                SKS
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('sks') is-invalid @enderror" name="sks" id="sksField"
                                type="text" placeholder="Masukkan sks"
                                value="{{ old('sks', $kurikulum->sks ?? '') }}" />
                            @error('sks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">
                                Sifat
                                <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" id="sifat-wajib" type="radio" name="sifat"
                                        value="Wajib"
                                        {{ isset($kurikulum) && $kurikulum->sifat == 'Wajib' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sifat-wajib">Wajib</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="sifat-pilihan" type="radio" name="sifat"
                                        value="Pilihan"
                                        {{ isset($kurikulum) && $kurikulum->sifat == 'Pilihan' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sifat-pilihan">Pilihan</label>
                                </div>
                            </div>
                            @error('sifat')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="semesterField">
                                Semester
                                <span class="text-danger">*</span>
                            </label>
                            <select name="semester" id="semesterField"
                                class="form-control  @error('semester') is-invalid @enderror">
                                <option value="" hidden>-- Pilih Semester --</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}"
                                        {{ isset($kurikulum) && $kurikulum->semester == $i ? 'selected' : '' }}>Semester
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="prasyaratField">
                                Prasyarat
                            </label>
                            <textarea name="prasyarat" id="prasyaratField" rows="3"
                                class="form-control @error('prasyarat') is-invalid @enderror" placeholder="Masukkan prasyarat">{{ old('prasyarat', $kurikulum->prasyarat ?? '') }}</textarea>
                            @error('prasyarat')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tautanField">
                                Tautan Google Drive
                            </label>
                            <input class="form-control @error('link_gdrive') is-invalid @enderror" name="link_gdrive"
                                id="tautanField" type="text" placeholder="Masukkan tautan google drive"
                                value="{{ old('link_gdrive', $kurikulum->link_gdrive ?? '') }}" />
                            @error('link_gdrive')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($kurikulum))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
