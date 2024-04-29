@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/summernote/summernote-lite.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
        .note-editor .note-editing-area,
        #preview {
            font-family: Jost, sans-serif !important;
            line-height: 1.8 !important;
        }

        .note-modal-content {
            overflow: hidden;
        }

        .note-modal-footer {
            height: auto !important;
            padding: 0 !important;
            position: relative;
        }

        .note-modal-footer input {
            margin-right: 15px;
            margin-bottom: 15px;
        }

        .note-modal-footer p {
            margin-top: 15px;
        }

        .sn-checkbox-open-in-new-window label input,
        .sn-checkbox-use-protocol label input {
            margin-right: 5px;
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('pendaftaranMahasiswaBaru.index') }}">
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
            action="@if (isset($pendaftaranMahasiswaBaru)) {{ route('pendaftaranMahasiswaBaru.update', $pendaftaranMahasiswaBaru->id) }} @else {{ route('pendaftaranMahasiswaBaru.store') }} @endif"
            method="POST" class="row {{ isset($pendaftaranMahasiswaBaru) ? 'flex-row-reverse' : '' }}">
            @csrf
            @if (isset($pendaftaranMahasiswaBaru))
                @method('PUT')
            @endif

            @if (isset($pendaftaranMahasiswaBaru))
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-body p-0 overflow-hidden">
                            <div class="d-flex border-top flex-column gap-3 p-3">
                                <div>
                                    <h1 class="small fw-bolder mb-2">Singkatan</h1>
                                    <p class="mb-0">{{ $pendaftaranMahasiswaBaru->singkatan }}</p>
                                </div>
                                <div>
                                    <h1 class="small fw-bolder mb-2">Kepanjangan</h1>
                                    <p class="mb-0">{{ $pendaftaranMahasiswaBaru->kepanjangan }}</p>
                                </div>
                                <div>
                                    <h1 class="small fw-bolder mb-2">Deskripsi</h1>
                                    <p class="mb-0">{!! $pendaftaranMahasiswaBaru->deskripsi !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($pendaftaranMahasiswaBaru) && $pendaftaranMahasiswaBaru->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $pendaftaranMahasiswaBaru->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($pendaftaranMahasiswaBaru->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="singkatanField">
                                Singkatan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('singkatan') is-invalid @enderror" name="singkatan"
                                id="singkatanField" type="text" placeholder="Masukkan singkatan"
                                value="{{ old('singkatan', $pendaftaranMahasiswaBaru->singkatan ?? '') }}"
                                autocomplete="off" />
                            @error('singkatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="kepanjanganField">
                                Kepanjangan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('kepanjangan') is-invalid @enderror" name="kepanjangan"
                                id="kepanjanganField" type="text" placeholder="Masukkan jenis kegiatan"
                                value="{{ old('kepanjangan', $pendaftaranMahasiswaBaru->kepanjangan ?? '') }}"
                                autocomplete="off" />
                            @error('kepanjangan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsiField">
                                Deskripsi
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="summernote" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $pendaftaranMahasiswaBaru->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($pendaftaranMahasiswaBaru))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($pendaftaranMahasiswaBaru) && $pendaftaranMahasiswaBaru->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $pendaftaranMahasiswaBaru->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($pendaftaranMahasiswaBaru->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($pendaftaranMahasiswaBaru->updated_at)->isoFormat('H:mm') }}
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
    <script src="{{ asset('assets/admin/libs/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/lang/summernote-id-ID.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // inisialisasi summernote
            $('#summernote').summernote({
                lang: 'id-ID',
                placeholder: '...',
                height: 600,
                focus: true,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['help', ['help']],
                ],
                fontNames: ['Jost', 'Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana'],
                callbacks: {
                    onInit: function() {
                        // Mengatur font default ke font family Jost
                        $('.note-editable').css('font-family', 'Jost, sans-serif');
                    },
                    onPaste: function(e) {
                        // Menangani paste event
                        const bufferText = ((e.originalEvent || e).clipboardData || window
                                .clipboardData)
                            .getData('Text');

                        // Bersihkan konten yang disisipkan dan atur font family
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText.replace(/<[^>]*>/g, ''));
                        $('.note-editable').css('font-family', 'Jost, sans-serif');

                        // Hapus konten image yang disisipkan
                        if (bufferText.includes('<img')) {
                            e.preventDefault();

                            // sweetalert notifikasi
                            Swal.fire({
                                icon: 'error',
                                title: 'Pengunggahan gambar tidak diizinkan',
                                text: 'Pengunggahan gambar tidak diizinkan pada area teks.',
                            });
                        }
                    },
                    onImageUpload: function(files, editor, welEditable) {
                        // sweetalert notifikasi
                        Swal.fire({
                            icon: 'error',
                            title: 'Pengunggahan gambar tidak diizinkan',
                            text: 'Pengunggahan gambar tidak diizinkan pada area teks.',
                        });
                    }
                }
            });
        });
    </script>
@endpush
