@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/yearpicker/yearpicker.css') }}">
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('prestasiMahasiswa.index') }}">
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
            action="@if (isset($prestasiMahasiswa)) {{ route('prestasiMahasiswa.update', $prestasiMahasiswa->id) }} @else {{ route('prestasiMahasiswa.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($prestasiMahasiswa))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($prestasiMahasiswa) && $prestasiMahasiswa->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $prestasiMahasiswa->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($prestasiMahasiswa->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="namaMahasiswaField">
                                Nama Mahasiswa
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('nama_mahasiswa') is-invalid @enderror" name="nama_mahasiswa"
                                id="namaMahasiswaField" type="text" placeholder="Masukkan nama mahasiswa"
                                value="{{ old('nama_mahasiswa', $prestasiMahasiswa->nama_mahasiswa ?? '') }}"
                                autocomplete="off" />
                            @error('nama_mahasiswa')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="predikatField">
                                Predikat
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('predikat') is-invalid @enderror" name="predikat"
                                id="predikatField" type="text" placeholder="Masukkan predikat"
                                value="{{ old('predikat', $prestasiMahasiswa->predikat ?? '') }}" autocomplete="off" />
                            @error('predikat')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tingkatField">
                                Tingkat
                                <span class="text-danger">*</span>
                            </label>
                            <select name="tingkat" id="tingkatField"
                                class="form-select @error('tingkat') is-invalid @enderror">
                                <option value="" hidden>-- Pilih Tingkat --</option>
                                @foreach ($tingkats as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('tingkat', $prestasiMahasiswa->tingkat ?? '') == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @error('tingkat')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tahunField">
                                Tahun
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('tahun') is-invalid @enderror" name="tahun" id="tahunField"
                                type="text" placeholder="{{ $prestasiMahasiswa->tahun ?? 'Masukkan tahun' }}"
                                autocomplete="off" />
                            @if (isset($prestasiMahasiswa))
                                <input type="hidden" name="tahun_lama" value="{{ $prestasiMahasiswa->tahun ?? '' }}">
                            @endif
                            @error('tahun')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="kegiatanField">
                                Kegiatan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('kegiatan') is-invalid @enderror" name="kegiatan"
                                id="kegiatanField" type="text" placeholder="Masukkan kegiatan"
                                value="{{ old('kegiatan', $prestasiMahasiswa->kegiatan ?? '') }}" autocomplete="off" />
                            @error('kegiatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($prestasiMahasiswa))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($prestasiMahasiswa) && $prestasiMahasiswa->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $prestasiMahasiswa->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($prestasiMahasiswa->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($prestasiMahasiswa->updated_at)->isoFormat('H:mm') }}
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
    <script src="{{ asset('assets/admin/libs/yearpicker/yearpicker.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tahunField').yearpicker();
        });
    </script>
@endpush
